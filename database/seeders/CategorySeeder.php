<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Classics',
            'Crime',
            'Fantasy',
            'General Fiction',
            'History',
            'Horror',
            'Literature',
            'Mystery',
            'Science',
            'Science Fiction',
        ];

        foreach ($categories as $category) {
            Category::factory()->create([
                'name' => $category,
            ]);
        }
    }
}
