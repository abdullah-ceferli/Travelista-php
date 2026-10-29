<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'blog_img', 'pub_date'];

    protected function casts(): array
    {
        return ['pub_date' => 'datetime', 'view_count' => 'integer'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->blog_img
            ? Storage::disk('public')->url($this->blog_img)
            : asset('images/blog-img/b1.jpg');
    }
}
