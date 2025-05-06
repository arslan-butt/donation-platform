<?php

namespace App\Actions\Donation;

use App\Models\Donation;
use App\Models\User;
use App\Notifications\DonationConfirmation;
use Illuminate\Support\Facades\DB;

class CreateDonation
{
    public function execute(array $data, User $user): Donation
    {
        $donation = DB::transaction(function () use ($data, $user) {
            $donation = Donation::create([
                'donor_id' => $user->id,
                'campaign_id' => $data['campaign_id'],
                'amount' => $data['amount'],
                'payment_reference' => $data['payment_reference'],
                'status' => 'completed',
                'donated_at' => now(),
            ]);

            $donation->payment()->create([
                'amount' => $data['amount'],
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return $donation;
        });

        $user->notify(new DonationConfirmation($donation));

        return $donation;
    }
}
