<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Tag1\ScoltaLaravel\Searchable;
use Tag1\Scolta\Export\ContentItem;

class Procedure extends Model
{
    use Searchable;

    protected $fillable = [
        'name', 'slug', 'category', 'risk_level', 'description', 'indications',
        'contraindications', 'equipment_standard', 'equipment_lunar', 'steps',
        'steps_lunar', 'telemedicine_points', 'training_requirements', 'complications',
        'search_keywords', 'enriched',
    ];

    protected $casts = [
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

    public function scopeByRisk(Builder $query, string $risk): Builder
    {
        return $query->where('risk_level', $risk);
    }

    public function toSearchableContent(): ContentItem
    {
        $body = implode("\n\n", array_filter([
            $this->description,
            "Indications: {$this->indications}",
            "Equipment: {$this->equipment_standard}",
            $this->equipment_lunar ? "Lunar Equipment: {$this->equipment_lunar}" : null,
            "Steps: {$this->steps}",
            $this->steps_lunar ? "Lunar Technique: {$this->steps_lunar}" : null,
            $this->telemedicine_points ? "Telemedicine Guidance: {$this->telemedicine_points}" : null,
            $this->training_requirements ? "Training: {$this->training_requirements}" : null,
        ]));

        return new ContentItem(
            id: "procedure-{$this->id}",
            title: $this->name,
            bodyHtml: nl2br(e($body)),
            url: route('procedures.show', $this->slug),
            date: $this->updated_at->format('Y-m-d'),
            siteName: config('scolta.site_name', 'Medical On The Moon'),
        );
    }
}
