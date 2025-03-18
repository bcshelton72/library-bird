<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::factory()->count(10)->create();

        // Calculate average review scores
        Book::all()->each(function ($book) {
            $book->calculateAverageRating();
        });
    }
}
