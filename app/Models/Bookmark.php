<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'time_added',
        'status',
        'notes'
    ];

    protected $casts = [
        'time_added' => 'datetime',
    ];

    /**
     * Bootstrap the model and its traits.
     */
    protected static function booted(): void
    {
        static::creating(function ($bookmark) {
            // Use time_added as the created_at timestamp if available
            if ($bookmark->time_added) {
                $bookmark->created_at = $bookmark->time_added;
                $bookmark->updated_at = $bookmark->time_added;
            }
        });
    }

    /**
     * Get the user that owns the bookmark
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tags for this bookmark
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
