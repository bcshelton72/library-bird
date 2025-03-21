<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Book $book)
    {
        return Inertia::render('Book/Show', [
            'book' => $book->load('author', 'category', 'publisher'),
            'review' => $book->reviews()->where('user_id', $request->user()->id)->first(),
        ]);
    }
}
