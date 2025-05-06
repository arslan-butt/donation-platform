<?php

namespace App\Actions\Campaign;

use App\Models\Campaign;
use App\Models\User;

class UpdateCampaign
{
    public function execute(Campaign $campaign, array $data, User $user): Campaign
    {
        $campaign->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'initial_amount' => $data['initial_amount'],
            'target_amount' => $data['target_amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'category_id' => $data['category_id'],
        ]);

        return $campaign->fresh();
    }
}
