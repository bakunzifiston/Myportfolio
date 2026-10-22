<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['skill_category_id', 'name', 'is_visible', 'sort_order'])]
class Skill extends Model
{
    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'skill_category_id');
    }
}
