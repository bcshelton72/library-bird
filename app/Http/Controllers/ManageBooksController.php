<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ManageBooksController extends Controller
{
    /**
     * Show the manage books page.
     */
    public function show(Request $request): Response
    {
        if (! $request->user()->hasRole('Librarian')) {
            abort(403);
        }

        $books = Book::with('author')
            ->select('id', 'title', 'author_id', 'description', 'average_rating', 'availability_date', 'available')
            ->get();

        return Inertia::render('ManageBooks', [
            'books' => $books,
            'overdue_books' => $books->where('available', false)->values(),
        ]);
    }

    /**
     * Mark the book as checked out
     */
    public function checkout(Request $request, Book $book)
    {
        if ($request->user()->cannot('checkout_book')) {
            abort(403);
        }

        $book->checkout();

        return redirect()->route('dashboard')->with('success', 'Your book was successfully checked out. Happy reading!');
    }

    /**
     * Mark the book as returned (no longer checked out)
     */
    public function return(Request $request, Book $book)
    {
        if ($request->user()->cannot('return_book')) {
            abort(403);
        }

        $book->return();

        return redirect()->route('manage-books')->with('success', 'The book was successfully returned.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Book $book)
    {
        if ($request->user()->cannot('delete_book')) {
            abort(403);
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('manage-books')->with('success', 'The book was successfully deleted.');
    }
}
