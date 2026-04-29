<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('user.view');
        $users = User::with('roles')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('user.create');
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $roles = $data['roles'];
        unset($data['roles']);

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $this->authorize('user.view');
        $user->load('roles.permissions');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('user.edit');
        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $roles = $data['roles'];
        unset($data['roles']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('user.delete');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggle(User $user): RedirectResponse
    {
        $this->authorize('user.toggle');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User account {$status}.");
    }
}
