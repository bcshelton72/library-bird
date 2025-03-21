<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Handle a review submission.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'review_text' => 'required|string|min:3|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = Review::updateOrCreate([
            'book_id' => $request->book_id,
            'user_id' => $request->user()->id,
        ], [
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        $review->book->calculateAverageRating();

        return redirect()->route('dashboard')->with('success', 'Review saved successfully!');
    }
}
