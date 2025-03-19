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
                    'review_book' => optional($request->user())->can('review_book'),
                    'create_book' => optional($request->user())->can('create_book'),
                    'return_book' => optional($request->user())->can('return_book'),
                    'update_book' => optional($request->user())->can('update_book'),
                    'delete_book' => optional($request->user())->can('delete_book'),
                ],
                'roles' => [
                    'customer' => optional($request->user())->hasRole('Customer'),
                    'librarian' => optional($request->user())->hasRole('Librarian'),
                ],
            ],
        ];
    }
}
