## Welcome!

Library Bird is a proof of concept for a library management system, usable by both customers to check out & review books, and librarians to manage books. The stack is Laravel 11, Vue3, and Inertia.

## Build steps

- Clone the repo
- `composer install`
- `npm install`
- `cp .env.example .env`
- `php artisan key:generate`
- `touch database/database.sqlite`
- `php artisan migrate:fresh --seed`
- `php artisan storage:link`
- `npm run dev`
- `php artisan serve`
- Visit http://localhost:8000/

Please disregard the standard Laravel demo home page! Once you create a user and log in the site is styled.
