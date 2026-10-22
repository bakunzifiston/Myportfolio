<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'project_category_id', 'title', 'slug', 'short_description', 'overview', 'role', 'contribution',
    'technologies', 'key_features', 'challenges', 'solutions', 'thumbnail', 'thumbnail_alt', 'screenshots',
    'live_url', 'repository_url', 'is_featured', 'status', 'sort_order',
])]
class Project extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [self::STATUS_DRAFT => 'Draft', self::STATUS_PUBLISHED => 'Published'];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'key_features' => 'array',
            'screenshots' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('title');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail ? Storage::disk('public')->url($this->thumbnail) : null;
    }

    /** @return array<int, string> */
    public function screenshotUrls(): array
    {
        return collect($this->screenshots ?? [])
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->all();
    }

    /** Initials used on the generated cover when no thumbnail has been uploaded. */
    public function initials(): string
    {
        // Split on spaces, hyphens and camelCase boundaries, so "NileLingua" becomes "NL".
        $words = preg_split('/[\s\-]+|(?<=[a-z])(?=[A-Z])/', trim($this->title)) ?: [];
        if (count($words) === 1) {
            return Str::upper(Str::substr($words[0], 0, 2));
        }

        return Str::upper(collect($words)->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode(''));
    }

    /** A stable hue derived from the slug, so each generated cover is distinct but consistent. */
    public function coverHue(): int
    {
        return crc32($this->slug ?: $this->title) % 360;
    }

    /** True when only the title (and perhaps a short line) is known — used to show a "details pending" note. */
    public function hasCaseStudy(): bool
    {
        return filled($this->overview) || filled($this->contribution) || filled($this->key_features)
            || filled($this->challenges) || filled($this->solutions);
    }
}
