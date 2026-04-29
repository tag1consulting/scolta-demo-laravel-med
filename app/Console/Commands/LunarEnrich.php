<?php

namespace App\Console\Commands;

use App\Models\Anatomy;
use App\Models\Article;
use App\Models\Condition;
use App\Models\Medication;
use App\Models\Procedure;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class LunarEnrich extends Command
{
    protected $signature = 'lunar:enrich
        {--model=conditions : Model to enrich (conditions|medications|procedures|anatomy|articles|all)}
        {--batch=50 : Number of records per AI batch}
        {--resume : Skip already-enriched records}
        {--dry-run : Show what would be enriched without making API calls}';

    protected $description = 'AI enrichment pass — adds narrative framing, cross-references, and fiction easter eggs (requires ANTHROPIC_API_KEY)';

    private string $apiKey = '';

    public function handle(): int
    {
        $this->apiKey = config('anthropic.api_key', env('ANTHROPIC_API_KEY', ''));

        if (empty($this->apiKey) && ! $this->option('dry-run')) {
            $this->error('ANTHROPIC_API_KEY not set. Set it in .env or pass --dry-run to preview.');
            return self::FAILURE;
        }

        $model = $this->option('model');
        $models = $model === 'all'
            ? ['conditions', 'medications', 'procedures', 'anatomy', 'articles']
            : [$model];

        foreach ($models as $m) {
            $this->enrichModel($m);
        }

        return self::SUCCESS;
    }

    private function enrichModel(string $type): void
    {
        $modelClass = match ($type) {
            'conditions'  => Condition::class,
            'medications' => Medication::class,
            'procedures'  => Procedure::class,
            'anatomy'     => Anatomy::class,
            'articles'    => Article::class,
            default       => null,
        };

        if (! $modelClass) {
            $this->error("Unknown model: {$type}");
            return;
        }

        $query = $modelClass::query();
        if ($this->option('resume')) {
            $query->where('enriched', false);
        }

        $total = $query->count();
        $this->info("Enriching {$total} {$type}...");

        if ($this->option('dry-run')) {
            $this->line("  [dry-run] Would enrich {$total} {$type} in batches of " . $this->option('batch'));
            return;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->chunkById((int) $this->option('batch'), function ($records) use ($bar, $type) {
            foreach ($records as $record) {
                try {
                    $enriched = $this->enrichRecord($record, $type);
                    if ($enriched) {
                        $record->enriched = true;
                        $record->save();
                    }
                } catch (\Throwable $e) {
                    $this->warn("\n  Failed to enrich {$type} #{$record->id}: {$e->getMessage()}");
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("{$type} enrichment complete.");
    }

    private function enrichRecord(object $record, string $type): bool
    {
        $prompt = $this->buildPrompt($record, $type);

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 500,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException('API error: ' . $response->status());
        }

        $text = $response->json('content.0.text', '');
        if (empty($text)) {
            return false;
        }

        // Apply the enrichment text to search_keywords
        $existing = $record->search_keywords ?? '';
        $record->search_keywords = trim($existing . ' ' . $text);

        return true;
    }

    private function buildPrompt(object $record, string $type): string
    {
        $title = $record->name ?? $record->generic_name ?? $record->title ?? 'Unknown';
        $desc  = $record->description ?? $record->abstract ?? $record->mechanism ?? '';

        return <<<EOT
        You are enriching search keywords for a lunar medical reference site called "Medical On The Moon".

        Record type: {$type}
        Title: {$title}
        Description: {$desc}

        Generate 5-10 additional search keywords that would help lunar residents find this record.
        Include: medical synonyms, lay terms, related conditions, lunar context terms.
        Subtly include one sci-fi concept reference if relevant (no copyrighted character names — concepts only).

        Return ONLY a comma-separated list of keywords, no other text.
        EOT;
    }
}
