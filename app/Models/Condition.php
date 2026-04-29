<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Tag1\ScoltaLaravel\Searchable;
use Tag1\Scolta\Export\ContentItem;

class Condition extends Model
{
    use Searchable;

    protected $fillable = [
        'name', 'slug', 'lunar_variant_name', 'icd10_code', 'body_system', 'severity',
        'description', 'lunar_risk_factors', 'symptoms', 'lunar_symptoms',
        'diagnosis', 'treatment', 'treatment_lunar', 'evacuation_criteria',
        'prevention', 'cross_references', 'search_keywords', 'is_emergency', 'enriched',
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
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

    public function scopeBySystem(Builder $query, string $system): Builder
    {
        return $query->where('body_system', $system);
    }

    public function scopeEmergency(Builder $query): Builder
    {
        return $query->where('is_emergency', true);
    }

    public function toSearchableContent(): ContentItem
    {
        $body = implode("\n\n", array_filter([
            $this->description,
            $this->lunar_risk_factors ? "Lunar Risk Factors: {$this->lunar_risk_factors}" : null,
            "Symptoms: {$this->symptoms}",
            $this->lunar_symptoms ? "Lunar Presentation: {$this->lunar_symptoms}" : null,
            "Diagnosis: {$this->diagnosis}",
            "Treatment: {$this->treatment}",
            $this->treatment_lunar ? "Lunar Treatment: {$this->treatment_lunar}" : null,
            $this->prevention ? "Prevention: {$this->prevention}" : null,
        ]));

        return new ContentItem(
            id: "condition-{$this->id}",
            title: $this->lunar_variant_name ?? $this->name,
            bodyHtml: nl2br(e($body)),
            url: route('conditions.show', $this->slug),
            date: $this->updated_at->format('Y-m-d'),
            siteName: config('scolta.site_name', 'Medical On The Moon'),
        );
    }
}
