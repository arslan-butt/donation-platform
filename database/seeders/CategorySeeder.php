<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@acme.com')->first();

        Category::factory()->count(10)->create([
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }
}
