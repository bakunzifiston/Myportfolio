<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'icon', 'description', 'sort_order'])]
class SkillCategory extends Model
{
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('sort_order')->orderBy('name');
    }

    public function visibleSkills(): HasMany
    {
        return $this->skills()->where('is_visible', true);
    }
}
