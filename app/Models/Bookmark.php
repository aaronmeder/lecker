<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'item_id', 'resolved_id', 'given_url', 'given_title',
        'resolved_url', 'resolved_title', 'favorite', 'excerpt',
        'is_article', 'has_video', 'has_image', 'word_count', 'lang',
        'time_added', 'time_updated', 'time_read', 'time_favorited',
        'status', 'notes'
    ];

    protected $casts = [
        'is_article' => 'boolean',
        'has_video' => 'boolean',
        'has_image' => 'boolean',
        'word_count' => 'integer',
        'time_added' => 'datetime',
        'time_updated' => 'datetime',
        'time_read' => 'datetime',
        'time_favorited' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
