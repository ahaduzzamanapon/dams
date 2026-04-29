<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $this->authorize('role.view');
        $roles = Role::withCount('permissions', 'users')->orderBy('name')->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('role.create');
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($p) => explode('.', $p->name)[0]);

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $this->authorize('role.view');
        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('role.edit');
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        // Protect built-in roles from renaming
        if (in_array($role->name, ['super-admin', 'admin', 'receptionist'])) {
            $role->syncPermissions($request->input('permissions', []));
        } else {
            $role->update(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permissions', []));
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('role.delete');

        if (in_array($role->name, ['super-admin', 'admin', 'receptionist'])) {
            return back()->with('error', 'Built-in roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }
}
