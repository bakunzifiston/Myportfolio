<?php

namespace App\Models;

use App\Support\Settings;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => Settings::flush());
        static::deleted(fn () => Settings::flush());
    }
}
