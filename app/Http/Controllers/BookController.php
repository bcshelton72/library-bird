<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Display the book's edit form.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(?Book $book)
    {
        return Inertia::render('Book/Edit', [
            'book' => optional($book)->load('author', 'category', 'publisher'),
        ]);
    }

    /**
     * Handle a book submission.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'cover_image' => 'sometimes|max:300',
            'title' => 'required|string|max:300',
            'first_name' => 'required|string|max:40',
            'last_name' => 'required|string|max:40',
            'description' => 'required|string|min:3|max:1000',
            'publisher' => 'required|string|max:60',
            'publication_date' => 'sometimes|date',
            'category' => 'required|string|max:20',
            'isbn' => 'sometimes|string|max:30',
            'page_count' => 'sometimes|int',
        ]);

        $author = Author::updateOrCreate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);
        $publisher = Publisher::updateOrCreate([
            'name' => $request->publisher,
        ]);
        $category = Category::updateOrCreate([
            'name' => $request->category,
        ]);

        $data = [
            'cover_image' => $request->cover_image,
            'title' => $request->title,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'category_id' => $category->id,
            'description' => $request->description,
            'publication_date' => $request->publication_date ?? null,
            'isbn' => $request->isbn ?? null,
            'page_count' => $request->page_count,
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = Storage::disk('public')->put('cover_images', $request->cover_image);
        }

        if (isset($request->id)) {
            $book = Book::find($request->id);
            $book->cover_image = $data['cover_image'] ?: $book->cover_image;
            $book->title = $data['title'];
            $book->author_id = $data['author_id'];
            $book->publisher_id = $data['publisher_id'];
            $book->category_id = $data['category_id'];
            $book->description = $data['description'];
            $book->publication_date = $data['publication_date'];
            $book->isbn = $data['isbn'];
            $book->page_count = $data['page_count'];
            $book->save();
        } else {
            Book::create($data);
        }

        return redirect()->route('manage-books')->with('success', 'Book saved successfully.');
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
