<?php

use App\Actions\Donation\CreateDonation;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use App\Notifications\DonationConfirmation;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionsSeeder::class);
});

it('sends donation confirmation email to the user', function () {
    // Fake notifications
    Notification::fake();

    // Create user and assign "user" role
    $user = User::factory()->create();
    $user->assignRole('user');
    $this->actingAs($user);

    // Create a category and campaign
    $category = \App\Models\Category::factory()->create([

    ]);
    $campaign = Campaign::factory()->create([
        'category_id' => $category->id,

    ]);

    // Prepare donation data
    $data = [
        'campaign_id' => $campaign->id,
        'amount' => 100.00,
        'payment_reference' => 'TEST123456',

    ];

    // Run the action
    $donation = app(CreateDonation::class)->execute($data, $user);

    // Assert notification was sent
    Notification::assertSentTo(
        $user,
        DonationConfirmation::class,
        function ($notification, $channels) use ($donation) {
            return $notification->getDonation()->is($donation)
                && in_array('mail', $channels);
        }
    );
});
