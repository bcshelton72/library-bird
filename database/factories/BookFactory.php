<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->unique()->words(fake()->numberBetween(1, 4), true)),
            'author_id' => fake()->numberBetween(1, Author::all()->count()),
            'publisher_id' => fake()->numberBetween(1, Publisher::all()->count()),
            'category_id' => fake()->numberBetween(1, Category::all()->count()),
            'description' => fake()->sentences(3, true),
        ];
    }
}
