<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // dashboards
            'view_dashboard',
            'view_response_dashboard',
            'view_preparation_dashboard',
            'employee_dashboard',

            // laptops
            'view_laptops',
            'create_laptops',
            'edit_laptops',
            'delete_laptops',

            // orders (admin)
            'view_orders',
            'update_order_status',

            // response employee
            'view_orders_for_response',
            'create_orders_for_customers_as_response',
            'update_order_status_to_confirmed_by_response',
            'assign_preparation_employee',

            // preparation employee
            'update_order_status_to_preparing',
            'update_order_status_to_ready',

            // users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // roles & permissions
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',

            // tasks system
            'manage_tasks',
            'manage_priorities',
            'manage_task_statuses',

            // employee tasks
            'view_employee_tasks',
            'update_employee_task_progress',
        ];

        $permissionIdsByName = [];

        foreach ($permissions as $name) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['id' => (string) Str::uuid()]
            );
            $permissionIdsByName[$name] = $permission->id;
        }

        // أدوار
        $roles = [
            'admin' => [
                // كل شيء تقريبًا
                ...$permissions,
            ],
            'response' => [
                'view_response_dashboard',
                'view_orders_for_response',
                'create_orders_for_customers_as_response',
                'update_order_status_to_confirmed_by_response',
                'assign_preparation_employee',
            ],
            'preparation' => [
                'view_preparation_dashboard',
                'view_orders',
                'update_order_status_to_preparing',
                'update_order_status_to_ready',
            ],
            'employee' => [
                'employee_dashboard',
                'view_employee_tasks',
                'update_employee_task_progress',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['id' => (string) Str::uuid()]
            );

            $role->permissions()->sync(
                collect($rolePermissions)->map(
                    fn ($p) => $permissionIdsByName[$p]
                )->all()
            );
        }
    }
}
