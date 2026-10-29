<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatThread extends Model
{
    protected $fillable = ['first_user_id', 'second_user_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function includes(User $user): bool
    {
        return in_array($user->id, [$this->first_user_id, $this->second_user_id]);
    }
}
