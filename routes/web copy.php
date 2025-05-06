<?php

// use App\Http\Controllers\CampaignController;
// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\UserController;
// use Illuminate\Support\Facades\Route;

// // Public Routes
// Route::get('/', function () {
//     return inertia('Welcome');
// })->name('home');

// // Authenticated User Routes
// Route::middleware(['auth', 'verified'])->group(function () {
//     // Dashboard
//     Route::get('/dashboard', function () {
//         return inertia('Dashboard');
//     })->name('dashboard');

//     // Categories (View Only)
//     // Route::get('/categories', [CategoryController::class, 'index'])
//     //     ->middleware('can:view categories')
//     //     ->name('categories.index');

//     // User Campaigns
//     Route::resource('campaigns', CampaignController::class)
//         ->middleware('can:manage own campaigns');
// });

// // Admin Routes
// Route::prefix('admin')
//     ->middleware(['auth', 'verified', 'role:admin'])
//     ->group(function () {
//         // Admin Dashboard
//         Route::get('/dashboard', function () {
//             return inertia('Dashboard');
//         })->name('admin.dashboard');

//         // User Management
//         Route::resource('users', UserController::class);

//         // Category Management
//         Route::resource('categories', CategoryController::class);
//     });

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
