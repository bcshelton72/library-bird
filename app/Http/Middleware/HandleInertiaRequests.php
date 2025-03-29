<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'permissions' => [
                    'checkout_book' => optional($request->user())->can('checkout_book'),
                    'create_book' => optional($request->user())->can('create_book'),
                    'create_review' => optional($request->user())->can('create_review'),
                    'delete_book' => optional($request->user())->can('delete_book'),
                    'manage_books' => optional($request->user())->can('manage_books'),
                    'return_book' => optional($request->user())->can('return_book'),
                    'update_book' => optional($request->user())->can('update_book'),
                ],
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}
