<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LunarSeedAll extends Command
{
    protected $signature = 'lunar:seed-all {--truncate : Truncate all tables before seeding}';
    protected $description = 'Run all lunar content seeders in order';

    public function handle(): int
    {
        $this->info('Starting full lunar content seed...');
        $this->newLine();

        $truncate = $this->option('truncate') ? ['--truncate' => true] : [];

        $this->call('lunar:seed-conditions', $truncate);
        $this->call('lunar:seed-medications', $truncate);
        $this->call('lunar:seed-procedures', $truncate);
        $this->call('lunar:seed-anatomy', $truncate);
        $this->call('lunar:seed-articles', $truncate);

        $this->newLine();
        $this->info('All content seeded. Run `php artisan scolta:build` to index for search.');
        $this->info('Run `php artisan lunar:enrich` to add AI narrative enrichment (requires ANTHROPIC_API_KEY).');

        return self::SUCCESS;
    }
}
