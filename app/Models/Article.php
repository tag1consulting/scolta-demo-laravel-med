<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Tag1\ScoltaLaravel\Searchable;
use Tag1\Scolta\Export\ContentItem;

class Article extends Model
{
    use Searchable;

    protected $fillable = [
        'title', 'slug', 'research_type', 'author_name', 'author_affiliation',
        'abstract', 'content', 'journal_name', 'volume_issue', 'published_date',
        'keywords', 'references', 'body_system', 'search_keywords', 'featured', 'enriched',
    ];

    protected $casts = [
        'published_date' => 'date',
        'featured' => 'boolean',
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

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('research_type', $type);
    }

    public function toSearchableContent(): ContentItem
    {
        // Derive the base topic from the title prefix (everything before the first ':').
        // Articles in this dataset are structured as "Base Topic: Study Variant", so the
        // prefix is the canonical topic family. The pagefind filter enables client-side
        // deduplication so search results show at most one article per topic family.
        $baseTopic = trim(explode(':', $this->title, 2)[0]);
        $baseTopicFilter = sprintf(
            '<span data-pagefind-filter="base_topic:%s" hidden></span>',
            htmlspecialchars($baseTopic, ENT_QUOTES, 'UTF-8'),
        );

        return new ContentItem(
            id: "article-{$this->id}",
            title: $this->title,
            bodyHtml: nl2br(e($this->abstract."\n\n".$this->content)) . $baseTopicFilter,
            url: route('articles.show', $this->slug),
            date: ($this->published_date ?? $this->updated_at)->format('Y-m-d'),
            siteName: config('scolta.site_name', 'Medical On The Moon'),
        );
    }
}
