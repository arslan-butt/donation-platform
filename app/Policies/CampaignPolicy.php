<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    // public function view(User $user, Campaign $campaign): Response
    // {
    //     return $campaign->created_by === $user->id
    //         ? Response::allow()
    //         : Response::denyAsNotFound();
    // }

    public function create(User $user): bool
    {
        // allow if admin
        if ($user->hasRole('admin')) {
            return true;
        }

        // allow if explicitly permitted
        return $user->can('create campaigns');
    }

    public function update(User $user, Campaign $campaign): Response
    {
        return $user->hasRole('admin') || $campaign->created_by === $user->id
            ? Response::allow()
            : Response::deny('You may only update your own campaigns.');
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->hasRole('admin') || $campaign->created_by === $user->id;
    }
}
