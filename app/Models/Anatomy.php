<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tag1\ScoltaLaravel\Searchable;
use Tag1\Scolta\Export\ContentItem;

class Anatomy extends Model
{
    use Searchable;

    protected $fillable = [
        'name', 'slug', 'body_system', 'structure_type', 'normal_function', 'description',
        'lunar_adaptation_arrival', 'lunar_adaptation_6m', 'lunar_adaptation_2y',
        'common_conditions', 'cross_references', 'search_keywords', 'enriched',
    ];

    protected $casts = [
        'enriched' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeBySystem(Builder $query, string $system): Builder
    {
        return $query->where('body_system', $system);
    }

    public function toSearchableContent(): ContentItem
    {
        $body = implode("\n\n", array_filter([
            $this->description,
            "Normal Function: {$this->normal_function}",
            $this->lunar_adaptation_arrival ? "On Arrival (First Weeks): {$this->lunar_adaptation_arrival}" : null,
            $this->lunar_adaptation_6m ? "6-Month Resident: {$this->lunar_adaptation_6m}" : null,
            $this->lunar_adaptation_2y ? "Long-Term Resident (2+ Years): {$this->lunar_adaptation_2y}" : null,
        ]));

        return new ContentItem(
            id: "anatomy-{$this->id}",
            title: $this->name,
            bodyHtml: nl2br(e($body)),
            url: route('anatomy.show', $this->slug),
            date: $this->updated_at->format('Y-m-d'),
            siteName: config('scolta.site_name', 'Medical On The Moon'),
        );
    }
}
