<?php

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionsSeeder::class);

    // Create users with roles
    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo('make donations');
    $this->admin->assignRole('admin');

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('make donations');
    $this->user->assignRole('user');
});

it('allows user to create a donation', function () {
    // Create a user and authenticate
    actingAs($this->user);

    // Create a campaign
    $campaign = Campaign::factory()->create();
    $donatedAt = now()->toDateTimeString();
    // Donation data
    $donationData = [
        'campaign_id' => $campaign->id,
        'amount' => 100,
        'payment_reference' => Str::random(10),
        'status' => 'completed',
        'donated_at' => $donatedAt,
    ];

    // Make a donation request using the HTTP helper provided by Pest
    $response = post(route('donations.store'), $donationData);

    // Verify the donation was created in the database
    $this->assertDatabaseHas('donations', [
        'donor_id' => $this->user->id,
        'campaign_id' => $campaign->id,
        'amount' => 100,
        'payment_reference' => $donationData['payment_reference'],
        'status' => 'completed',
        'donated_at' => $donatedAt,
    ]);

    // Assert the user is redirected back with a success message
    $response->assertRedirect();
    $response->assertSessionHas('success', 'Donation recorded successfully!');
});

it('prevents user without permission from making a donation', function () {
    // Create a user and campaign
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create();

    // Donation data
    $donationData = [
        'campaign_id' => $campaign->id,
        'amount' => 50,
        'payment_reference' => Str::random(10),
        'status' => 'completed',
        'donated_at' => now(),
    ];

    // Attempt to make a donation without the required permission
    $response = actingAs($user)->post(route('donations.store'), $donationData);

    $response->assertForbidden();
});

it('requires valid data to create a donation', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('make donations');

    actingAs($user);  // Authenticate the user

    // Attempt to create a donation with missing amount (which is required)
    $response = post(route('donations.store'), [
        'campaign_id' => 1,
        'payment_reference' => Str::random(10),
    ]);

    $response->assertSessionHasErrors('amount');
});
