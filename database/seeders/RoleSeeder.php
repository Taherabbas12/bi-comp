<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <-- import Str

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // --- الأدوار العامة ---
        $adminRole = Role::create([
            'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
            'name' => 'admin',
        ]);

        $preparationRole = Role::create([
            'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
            'name' => 'preparation',
        ]);

        // --- دور موظف الردود ---
        $responseRole = Role::create([
            'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
            'name' => 'response',
        ]);

        // --- ربط الصلاحيات ---
        // الصلاحيات العامة (admin فقط)
        $allPermissions = Permission::all();
        $adminRole->permissions()->attach($allPermissions->pluck('id'));

        // الصلاحيات المخصصة لموظف الردود
        $responsePermissionNames = [
            'view_response_dashboard',
            'view_orders_for_response',
            'update_order_status_to_confirmed_by_response',
            'assign_preparation_employee',
            'create_orders_for_customers_as_response',
        ];
        $responsePermissionIds = Permission::whereIn('name', $responsePermissionNames)->pluck('id');
        $responseRole->permissions()->attach($responsePermissionIds);

        // يمكنك ربط صلاحيات لـ preparation أيضًا إذا لزم الأمر
        // $preparationPermissionNames = [...];
        // $preparationPermissionIds = Permission::whereIn('name', $preparationPermissionNames)->pluck('id');
        // $preparationRole->permissions()->attach($preparationPermissionIds);
    }
}
