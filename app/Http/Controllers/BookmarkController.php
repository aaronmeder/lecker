<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display a listing of bookmarks.
     */
    public function index(Request $request)
    {
        $bookmarks = Bookmark::where('user_id', auth()->id())
            ->with('tags')
            ->latest('created_at')
            ->paginate(25);

        return view('bookmarks.index', [
            'bookmarks' => $bookmarks,
        ]);
    }
}
