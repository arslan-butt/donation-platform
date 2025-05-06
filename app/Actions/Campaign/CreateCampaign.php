<?php

namespace App\Actions\Campaign;

use App\Models\Campaign;
use App\Models\User;

class CreateCampaign
{
    public function execute(array $data, User $user): Campaign
    {
        $data['type'] = $user->hasRole('admin') ? 'admin' : 'employee';

        return Campaign::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'initial_amount' => $data['initial_amount'],
            'target_amount' => $data['target_amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'],
            'type' => $data['type'],
            'featured_image' => $data['featured_image'] ?? null,
            'category_id' => $data['category_id'],
        ]);
    }
}
