<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function execute(User $user, array $data): User
    {
        // Store original email for comparison
        $originalEmail = $user->email;

        // Fill the user with validated data (except password and roles)
        $user->fill(collect($data)->except(['password', 'roles'])->toArray());

        // Handle password update if provided
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Check if email was changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            // Note: The controller will handle sending verification email
        }

        // Save only if there are changes
        if ($user->isDirty()) {
            $user->save();
        }

        // Handle role updates
        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->refresh();
    }
}
