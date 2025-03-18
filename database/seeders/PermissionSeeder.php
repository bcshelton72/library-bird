<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerPermissions = [
            'checkout_book',
            'create_review',
        ];

        $librarianPermissions = [
            'create_book',
            'return_book',
            'update_book',
            'delete_book',
        ];

        // Create permissions
        foreach (array_merge($customerPermissions, $librarianPermissions) as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }

        // Create customer role & assign permissions
        $customerRole = Role::create(['name' => 'Customer']);
        foreach ($customerPermissions as $permission) {
            $customerRole->givePermissionTo($permission);
        }

        // Create librarian role & assign permissions
        $librarianRole = Role::create(['name' => 'Librarian']);
        foreach ($librarianPermissions as $permission) {
            $librarianRole->givePermissionTo($permission);
        }
    }
}
