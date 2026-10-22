<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'subject', 'message', 'ip_address', 'user_agent', 'emailed_at', 'read_at'])]
class ContactMessage extends Model
{
    protected function casts(): array
    {
        return ['emailed_at' => 'datetime', 'read_at' => 'datetime'];
    }
}
