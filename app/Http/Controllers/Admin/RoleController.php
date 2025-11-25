<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // صلاحية: view_roles
        // middleware: permission:view_roles
        $roles = Role::with('permissions')->paginate(20); // أو get() إذا كنت لا ترغب في الترقيم

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // صلاحية: create_roles
        // middleware: permission:create_roles
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        // صلاحية: create_roles
        // middleware: permission:create_roles
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        if ($request->filled('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        // صلاحية: edit_roles
        // middleware: permission:edit_roles
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // صلاحية: edit_roles
        // middleware: permission:edit_roles
        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // صلاحية: delete_roles
        // middleware: permission:delete_roles
        $role->delete(); // soft delete إذا كنت تستخدمه

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
