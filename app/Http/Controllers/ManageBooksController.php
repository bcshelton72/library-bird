<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ManageBooksController extends Controller
{
    /**
     * Show the manage books page.
     */
    public function show(Request $request): Response
    {
        if ($request->user()->cannot('manage_books')) {
            abort(403);
        }

        $books = Book::with('author')
            ->select('id', 'cover_image', 'title', 'author_id', 'description', 'average_rating', 'availability_date', 'available')
            ->get();

        return Inertia::render('ManageBooks', [
            'books' => $books,
            'overdue_books' => $books->where('available', false)->values(),
        ]);
    }
}
