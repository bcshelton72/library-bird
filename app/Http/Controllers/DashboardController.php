<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function show(): Response
    {
        return Inertia::render('Dashboard', [
            'books' => Book::with('author')
                ->select('id', 'title', 'author_id', 'description', 'average_rating', 'availability_date', 'available')
                ->get(),
        ]);
    }
}
