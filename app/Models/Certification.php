<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'issuer', 'issued_on', 'expires_on', 'credential_id', 'credential_url', 'is_published', 'sort_order'])]
class Certification extends Model
{
    protected function casts(): array
    {
        return ['issued_on' => 'date', 'expires_on' => 'date', 'is_published' => 'boolean'];
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderByDesc('issued_on');
    }
}
