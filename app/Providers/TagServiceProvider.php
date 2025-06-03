<?php

namespace App\Providers;

use App\Models\Bookmark;
use App\Observers\BookmarkTagObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class TagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Bookmark::observe(BookmarkTagObserver::class);

        // Listen for pivot table events
        Relation::morphMap([
            'bookmark' => Bookmark::class,
        ]);

        // Update tag usage count when a new tag is attached
        \DB::listen(function($query) {
            if (str_contains($query->sql, 'insert into `bookmark_tag`')) {
                $this->handleTagAttached($query->bindings);
            }
            if (str_contains($query->sql, 'delete from `bookmark_tag`')) {
                $this->handleTagDetached($query->bindings);
            }
        });
    }

    /**
     * Handle tag attached to bookmark
     */
    private function handleTagAttached(array $bindings): void
    {
        if (count($bindings) >= 2) {
            $tagId = $bindings[1] ?? null;
            if ($tagId) {
                $tag = \App\Models\Tag::find($tagId);
                if ($tag) {
                    $tag->incrementUsage();
                }
            }
        }
    }

    /**
     * Handle tag detached from bookmark
     */
    private function handleTagDetached(array $bindings): void
    {
        if (count($bindings) >= 2) {
            $tagId = $bindings[1] ?? null;
            if ($tagId) {
                $tag = \App\Models\Tag::find($tagId);
                if ($tag) {
                    $tag->decrementUsage();
                }
            }
        }
    }
}
