<?php

namespace App\Console\Commands;

use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportPocketBookmarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pocket-bookmarks {file} {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import bookmarks from a Pocket CSV export';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        $userId = $this->argument('user_id');

        // Ensure user exists
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        // Check if file exists
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info("Starting import from {$file} for user {$user->name}...");

        // Read CSV
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $count = 0;

        foreach ($records as $record) {
            // Convert Unix timestamp to datetime
            $timeAdded = isset($record['time_added'])
                ? Carbon::createFromTimestamp((int)$record['time_added'])
                : null;

            // Truncate title if it's too long (max 255 characters)
            $title = isset($record['title']) ? substr($record['title'], 0, 255) : null;

            // Create the bookmark
            $bookmark = Bookmark::create([
                'user_id' => $userId,
                'title' => $title,
                'url' => $record['url'] ?? null,
                'time_added' => $timeAdded,
                'status' => 'published',
                'notes' => ''
            ]);

            // Process tags - split the pipe-separated string into individual tags
            if (isset($record['tags']) && !empty($record['tags'])) {
                $tagNames = explode('|', $record['tags']);
                $tagIds = [];

                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate(['name' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                }

                // Use syncWithoutDetaching instead of attach to prevent duplicates
                if (!empty($tagIds)) {
                    $bookmark->tags()->syncWithoutDetaching($tagIds);

                    // Manually increment usage count for imported tags
                    foreach ($tagIds as $tagId) {
                        Tag::find($tagId)->incrementUsage();
                    }
                }
            }

            $count++;
        }

        $this->info("Successfully imported {$count} bookmarks.");
        return 0;
    }
}
