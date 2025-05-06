<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Gate::authorize('manage users');

        return Inertia::render('admin/users/Index', [
            'users' => User::query()
                ->with('roles')
                ->latest()
                ->filter(request()->only('search'))
                ->paginate(perPage: request('per_page', default: 2))
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                    'created_at' => $user->created_at->diffForHumans(),
                ]),

            'filters' => request()->only(['search', 'per_page']),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/users/Create', [
            'availableRoles' => Role::pluck('name'),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUser $createAction)
    {
        try {
            $user = $createAction->execute($request->validated());

            return to_route('admin.users.index');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    // Edit user form
    public function edit(User $user)
    {
        $user->load('roles');

        return Inertia::render('admin/users/Edit', [
            'user' => [
                ...$user->toArray(),
                'roles' => $user->roles->pluck('name')->toArray(),
            ],
            'availableRoles' => Role::pluck('name'),
        ]);
    }

    // Update user
    public function update(UpdateUserRequest $request, User $user, UpdateUser $updateUser): RedirectResponse
    {
        try {

            $updatedUser = $updateUser->execute($user, $request->validated());

            return to_route('admin.users.index');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back();
    }
}
