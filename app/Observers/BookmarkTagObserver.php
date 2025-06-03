<?php

namespace App\Observers;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class BookmarkTagObserver
{
    /**
     * Handle the Bookmark "created" event.
     */
    public function created(Bookmark $bookmark): void
    {
        // We'll handle tag associations separately
    }

    /**
     * Handle the Bookmark "updated" event.
     */
    public function updated(Bookmark $bookmark): void
    {
        // We'll handle tag associations separately
    }

    /**
     * Handle the Bookmark "deleted" event.
     */
    public function deleted(Bookmark $bookmark): void
    {
        // Decrement usage count for all associated tags
        $bookmark->tags->each(function ($tag) {
            $tag->decrementUsage();
        });
    }
}
