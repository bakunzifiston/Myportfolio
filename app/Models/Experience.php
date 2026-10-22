<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'type', 'project_id', 'organization', 'position', 'employment_type', 'location', 'start_date', 'end_date',
    'is_current', 'summary', 'responsibilities', 'contributions', 'technologies', 'is_published', 'sort_order',
])]
class Experience extends Model
{
    public const TYPES = ['employment' => 'Work experience', 'project' => 'Project-based contribution'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'is_published' => 'boolean',
            'responsibilities' => 'array',
            'contributions' => 'array',
            'technologies' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Published entries only. An entry also needs a heading (position or organisation) so that
     * incomplete records never surface publicly even if someone ticks "published" too early.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn (Builder $q) => $q->whereNotNull('position')->orWhereNotNull('organization'))
            ->orderBy('sort_order')
            ->orderByDesc('start_date');
    }

    public function heading(): string
    {
        return $this->position ?: ($this->organization ?: $this->project?->title ?: 'Contribution');
    }

    public function period(): ?string
    {
        if (! $this->start_date) {
            return null;
        }

        $end = $this->is_current ? 'Present' : $this->end_date?->format('M Y');

        return $this->start_date->format('M Y').($end ? ' – '.$end : '');
    }
}
