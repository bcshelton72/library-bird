<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => fake()->numberBetween(1, Book::all()->count()),
            'review_text' => fake()->sentences(2, true),
            'rating' => fake()->randomElement([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5]),
        ];
    }
}
