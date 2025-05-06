<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Welcome Route
Route::get('/', function () {
    return inertia('Welcome');
})->name('home');

// Authenticated Users Only
Route::middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('dashboard', DashboardController::class)
            ->name('dashboard');

        Route::get('/campaigns/all', [CampaignController::class, 'all'])
            ->name('campaigns.all');

        Route::resource('campaigns', CampaignController::class);

        Route::get('/donations', [DonationController::class, 'index'])
            ->middleware('can:view donations')
            ->name('donations.index');

        Route::post('/donations', [DonationController::class, 'store'])
            ->middleware('can:make donations')
            ->name('donations.store');
    });

// Admin Routes
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // User Management
        Route::resource('users', UserController::class);

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Campaign Management
        Route::resource('campaigns', CampaignController::class);

    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
