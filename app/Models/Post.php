<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'code',
        'body',
        'image',
        'category_id',
        'user_id',
        'views',
        'likes',
        'link',
    ];

    protected $casts = [
        'views' => 'integer',
        'likes' => 'integer',
        'body' => 'string',
    ];

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->user_id)) {
                $post->user_id = auth()->id();
            }
        });
    }

    /**
     * Get the excerpt of the post.
     */
    public function getExcerptAttribute($length = 150)
    {
        // Strip HTML tags and get plain text excerpt
        $plainText = strip_tags($this->body);
        return Str::limit($plainText, $length);
    }

    /**
     * Get the reading time of the post.
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->body));
        $minutesToRead = round($wordCount / 200);
        return max(1, $minutesToRead);
    }
}
