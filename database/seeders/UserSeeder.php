<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'ACME Admin',
            'email' => 'admin@acme.com',
        ]);
        $admin->assignRole('admin');

        // Create Regular User
        $user = User::factory()->create([
            'name' => 'ACME User',
            'email' => 'user@acme.com',
        ]);
        $user->assignRole('user');
    }
}
