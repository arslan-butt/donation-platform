<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->user = User::factory()->create();
    $this->user->assignRole('user');
});

test('admin can view category index with pagination', function () {
    Category::factory()->count(7)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/categories/Index')
            ->has('categories.data', 5) // paginated to 5 per page
            ->has('filters')
        );
});

test('regular user cannot access admin category index', function () {
    $this->actingAs($this->user)
        ->get(route('admin.categories.index'))
        ->assertForbidden();
});

test('admin can access category create and edit pages', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.categories.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('admin/categories/Create'));

    $this->actingAs($this->admin)
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/categories/Edit')
            ->has('category', fn ($cat) => $cat
                ->where('id', $category->id)
                ->where('name', $category->name)
                ->where('description', $category->description)
            )
        );
});

test('admin can create, update, and soft delete categories', function () {
    // Create
    $this->actingAs($this->admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Books',
            'description' => 'Reading materials',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', ['name' => 'Books']);

    // Update
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.categories.update', $category), [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ])
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Updated Name',
    ]);

    // Delete
    $this->actingAs($this->admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('regular users are forbidden from managing categories', function () {
    $category = Category::factory()->create();

    $routes = [
        ['get', route('admin.categories.create')],
        ['post', route('admin.categories.store'), ['name' => 'X']],
        ['get', route('admin.categories.edit', $category)],
        ['put', route('admin.categories.update', $category), ['name' => 'Y']],
        ['delete', route('admin.categories.destroy', $category)],
    ];

    foreach ($routes as $route) {
        [$method, $url, $data] = array_pad($route, 3, []);

        $this->actingAs($this->user)
            ->{$method}($url, $data)
            ->assertForbidden();
    }
});
