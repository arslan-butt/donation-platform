<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $component = $isAdmin ? 'dashboard/AdminDashboard' : 'dashboard/UserDashboard';

        return Inertia::render($component, [
            // Show user count only for admins
            'user_count' => $isAdmin ? User::count() : null,

            // Campaign stats filtered by user when not admin
            'active_campaigns' => Inertia::defer(fn () => Campaign::forUser($user)
                ->whereStatus(CampaignStatus::Active)
                ->where('end_date', '>', now())
                ->count()),

            'completed_campaigns' => Inertia::defer(fn () => Campaign::forUser($user)
                ->whereStatus(CampaignStatus::Completed)
                ->count()),

            // Total campaigns filtered by user
            'total_campaigns' => Inertia::defer(fn () => Campaign::forUser($user)
                ->count()),

            // Donation stats filtered through campaign relationship
            'total_raised' => Inertia::defer(fn () => Donation::whereHas('campaign', fn ($q) => $q->forUser($user))
                ->where('status', 'completed')
                ->sum('amount')),

            'total_donations' => Inertia::defer(fn () => Donation::whereHas('campaign', fn ($q) => $q->forUser($user))
                ->where('status', 'completed')
                ->count()),

            // Average donation calculation
            'avg_donation' => Inertia::defer(fn () => Donation::whereHas('campaign', fn ($q) => $q->forUser($user))
                ->where('status', 'completed')
                ->average('amount')),

        ]);
    }
}
