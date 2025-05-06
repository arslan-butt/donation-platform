<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@acme.com')->first();
        $user = User::where('email', 'user@acme.com')->first();

        // Admin-created "mission" campaigns
        Campaign::factory()
            ->count(5)
            ->mission()
            ->active()
            ->create([
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

        // User-created employee campaigns
        Campaign::factory()
            ->count(5)
            ->employeeCampaign()
            ->active()
            ->create([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
    }
}
