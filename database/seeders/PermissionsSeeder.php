<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Define permissions
        $permissions = [
            // Campaign permissions
            'create campaigns',
            'edit own campaigns',
            'delete own campaigns',
            'view campaigns',

            // Donation permissions
            'make donations',
            'view donations',

            // Admin permissions
            'manage campaigns',
            'manage categories',
            'manage users',
            'access admin panel',
            'view dashboard',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());

        $user->givePermissionTo([
            'create campaigns',
            'edit own campaigns',
            'delete own campaigns',
            'view campaigns',
            'make donations',
            'view donations',
        ]);

    }
}
