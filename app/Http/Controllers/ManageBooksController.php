<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;

class ManageBooksController extends Controller
{
    /**
     * Show the manage books page.
     */
    public function show(): Response
    {
        $books = Book::with('author')
            ->select('id', 'title', 'author_id', 'description', 'average_rating', 'availability_date', 'available')
            ->get();

        return Inertia::render('ManageBooks', [
            'books' => $books,
            'overdue_books' => $books->where('available', false)->values(),
        ]);
    }
}
