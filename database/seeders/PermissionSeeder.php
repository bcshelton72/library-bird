<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $userPermissions = [
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
        foreach (array_merge($userPermissions, $librarianPermissions) as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        // Create user role & assign permissions
        $userRole = Role::create(['name' => 'User']);
        foreach ($userPermissions as $permission) {
            $userRole->givePermissionTo($permission);
        }

        // Create librarian role & assign permissions
        $librarianRole = Role::create(['name' => 'Librarian']);
        foreach ($librarianPermissions as $permission) {
            $librarianRole->givePermissionTo($permission);
        }
    }
}
