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
            ray("Processing record: ", $record)->die();
            // Convert Unix timestamp to datetime
            $timeAdded = isset($record['time_added'])
                ? Carbon::createFromTimestamp((int)$record['time_added'])
                : null;

            $timeUpdated = isset($record['time_updated']) && !empty($record['time_updated'])
                ? Carbon::createFromTimestamp((int)$record['time_updated'])
                : null;

            $timeRead = isset($record['time_read']) && !empty($record['time_read'])
                ? Carbon::createFromTimestamp((int)$record['time_read'])
                : null;

            $timeFavorited = isset($record['time_favorited']) && !empty($record['time_favorited'])
                ? Carbon::createFromTimestamp((int)$record['time_favorited'])
                : null;

            // Create the bookmark
            $bookmark = Bookmark::create([
                'user_id' => $userId,
                'item_id' => $record['item_id'] ?? null,
                'resolved_id' => $record['resolved_id'] ?? null,
                'given_url' => $record['given_url'] ?? null,
                'given_title' => $record['given_title'] ?? null,
                'resolved_url' => $record['resolved_url'] ?? null,
                'resolved_title' => $record['resolved_title'] ?? null,
                'favorite' => $record['favorite'] ?? null,
                'excerpt' => $record['excerpt'] ?? null,
                'is_article' => isset($record['is_article']) ? (bool)$record['is_article'] : null,
                'has_video' => isset($record['has_video']) ? (bool)$record['has_video'] : null,
                'has_image' => isset($record['has_image']) ? (bool)$record['has_image'] : null,
                'word_count' => isset($record['word_count']) ? (int)$record['word_count'] : null,
                'lang' => $record['lang'] ?? null,
                'time_added' => $timeAdded,
                'time_updated' => $timeUpdated,
                'time_read' => $timeRead,
                'time_favorited' => $timeFavorited,
                'status' => 'published',
                'notes' => '',
                'created_at' => $timeAdded,
            ]);

            // Process tags
            if (isset($record['tags']) && !empty($record['tags'])) {
                $tagNames = explode(',', $record['tags']);
                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate(['name' => $tagName]);
                        $bookmark->tags()->attach($tag->id);
                    }
                }
            }

            $count++;
        }

        $this->info("Successfully imported {$count} bookmarks.");
        return 0;
    }
}
