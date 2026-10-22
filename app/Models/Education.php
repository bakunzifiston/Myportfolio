<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'level', 'field_of_study', 'qualification_title', 'institution', 'city', 'country', 'start_year',
    'end_year', 'status', 'description', 'is_published', 'sort_order',
])]
class Education extends Model
{
    protected $table = 'education';

    public const LEVELS = [
        'bachelor' => "Bachelor's",
        'master' => "Master's",
        'doctorate' => 'Doctoral',
        'diploma' => 'Diploma',
        'certificate' => 'Certificate',
        'other' => 'Other',
    ];

    /**
     * Only "awarded" presents the entry as a held qualification. Set it once completion is confirmed.
     */
    public const STATUSES = [
        'pursued' => 'Studies pursued',
        'in_progress' => 'In progress',
        'awarded' => 'Qualification awarded',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderByDesc('start_year');
    }

    public function isAwarded(): bool
    {
        return $this->status === 'awarded';
    }

    /**
     * The public heading. A formal qualification title is used only for awarded entries;
     * everything else is phrased as studies so that no unconfirmed degree is claimed.
     */
    public function displayTitle(): string
    {
        if ($this->isAwarded() && filled($this->qualification_title)) {
            return $this->qualification_title;
        }

        $level = self::LEVELS[$this->level] ?? null;
        $prefix = in_array($this->level, ['bachelor', 'master', 'doctorate'], true)
            ? $level.'-level studies'
            : ($level ? $level.' studies' : 'Studies');

        return $this->field_of_study ? $prefix.' in '.$this->field_of_study : $prefix;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['pursued'];
    }

    public function locationLabel(): ?string
    {
        return collect([$this->city, $this->country])->filter()->implode(', ') ?: null;
    }

    public function period(): ?string
    {
        if (! $this->start_year && ! $this->end_year) {
            return null;
        }
        if ($this->start_year && ! $this->end_year) {
            return $this->status === 'in_progress' ? $this->start_year.' – Present' : (string) $this->start_year;
        }

        return collect([$this->start_year, $this->end_year])->filter()->unique()->implode(' – ');
    }
}
