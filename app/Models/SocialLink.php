<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['platform', 'label', 'url', 'icon', 'is_visible', 'sort_order'])]
class SocialLink extends Model
{
    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    /** Visible links that actually have a URL, so the site never renders an empty href. */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_visible', true)
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->orderBy('sort_order');
    }
}
