<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Tag1\ScoltaLaravel\Searchable;
use Tag1\Scolta\Export\ContentItem;

class Medication extends Model
{
    use Searchable;

    protected $fillable = [
        'generic_name', 'slug', 'brand_names', 'drug_class', 'mechanism', 'indications',
        'dosing_standard', 'dosing_lunar', 'storage_standard', 'storage_lunar',
        'supply_chain_notes', 'interactions', 'contraindications', 'side_effects',
        'alternatives', 'who_essential', 'lunar_critical', 'search_keywords', 'enriched',
    ];

    protected $casts = [
        'who_essential' => 'boolean',
        'lunar_critical' => 'boolean',
        'enriched' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeByClass(Builder $query, string $class): Builder
    {
        return $query->where('drug_class', $class);
    }

    public function scopeLunarCritical(Builder $query): Builder
    {
        return $query->where('lunar_critical', true);
    }

    public function toSearchableContent(): ContentItem
    {
        $body = implode("\n\n", array_filter([
            "Drug Class: {$this->drug_class}",
            "Mechanism: {$this->mechanism}",
            "Indications: {$this->indications}",
            "Standard Dosing: {$this->dosing_standard}",
            $this->dosing_lunar ? "Lunar Dosing: {$this->dosing_lunar}" : null,
            $this->storage_lunar ? "Lunar Storage: {$this->storage_lunar}" : null,
            $this->supply_chain_notes ? "Supply Notes: {$this->supply_chain_notes}" : null,
            $this->interactions ? "Interactions: {$this->interactions}" : null,
            $this->side_effects ? "Side Effects: {$this->side_effects}" : null,
        ]));

        return new ContentItem(
            id: "medication-{$this->id}",
            title: $this->generic_name,
            bodyHtml: nl2br(e($body)),
            url: route('medications.show', $this->slug),
            date: $this->updated_at->format('Y-m-d'),
            siteName: config('scolta.site_name', 'Medical On The Moon'),
        );
    }
}
