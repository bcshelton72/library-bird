<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // $book = $book->with('author')->get();
        // dd($book);
        return Inertia::render('Book/Show', [
            'book' => $book->load('author', 'category', 'publisher'),
        ]);
    }
}
