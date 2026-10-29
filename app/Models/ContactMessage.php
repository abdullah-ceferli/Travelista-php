<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = ['user_id', 'name', 'surname', 'email', 'subject', 'stars', 'message'];

    protected $hidden = ['email'];

    protected function casts(): array
    {
        return ['stars' => 'integer', 'is_published' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
