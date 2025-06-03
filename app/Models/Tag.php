<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'usage_count'];

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class);
    }

    /**
     * Increment the usage count of this tag
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement the usage count of this tag
     */
    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }
}
