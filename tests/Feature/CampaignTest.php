<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles/permissions
        $this->artisan('db:seed', ['--class' => 'PermissionsSeeder']);
    }

    public function test_admin_can_view_campaign_index_page()
    {
        $admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.campaigns.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/campaigns/Index')
                ->has('campaigns')
            );
    }

    public function test_regular_user_cannot_access_admin_campaign_pages()
    {
        $user = User::factory()->create()->assignRole('user');

        $this->actingAs($user)
            ->get(route('admin.campaigns.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_campaign()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.campaigns.store'), [
                'title' => 'Test Campaign',
                'description' => 'Test campaign description',
                'initial_amount' => 0,
                'target_amount' => 1000,
                'category_id' => $category->id,
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
            ])
            ->assertRedirect(route('admin.campaigns.index'));

        $this->assertDatabaseHas('campaigns', [
            'title' => 'Test Campaign',
        ]);
    }

    public function test_user_with_permission_can_create_campaign()
    {
        $user = User::factory()->create()->assignRole('user');
        $user->givePermissionTo('create campaigns');
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->post(route('campaigns.store'), [
                'title' => 'User Campaign',
                'description' => 'A campaign by user',
                'initial_amount' => 0,
                'target_amount' => 1000,
                'category_id' => $category->id,
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
            ])
            ->assertRedirect(route('campaigns.index'));

        $this->assertDatabaseHas('campaigns', ['title' => 'User Campaign']);
    }

    public function test_user_cannot_delete_others_campaigns()
    {
        $user = User::factory()->create()->assignRole('user');
        $user->givePermissionTo('delete own campaigns');

        $anotherUser = User::factory()->create();
        $campaign = Campaign::factory()->for($anotherUser, 'author')->create();

        $this->actingAs($user)
            ->delete(route('campaigns.destroy', $campaign))
            ->assertForbidden();
    }

    public function test_user_can_delete_own_campaign()
    {
        $user = User::factory()->create()->assignRole('user');
        $user->givePermissionTo('delete own campaigns');

        $campaign = Campaign::factory()->for($user, 'author')->create();

        $this->actingAs($user)
            ->delete(route('campaigns.destroy', $campaign))
            ->assertRedirect();

        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
    }
}
