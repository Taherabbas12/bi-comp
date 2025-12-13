<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'view_dashboard',

            // Laptops
            'view_laptops',
            'create_laptops',
            'edit_laptops',
            'delete_laptops',

            // Orders - Admin
            'view_orders',
            'update_order_status',

            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Roles & Permissions
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',

            // Tasks (Admin)
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'delete_tasks',

            // Response Employee
            'view_response_dashboard',
            'view_orders_for_response',
            'create_orders_for_customers_as_response',
            'update_order_status_to_confirmed_by_response',
            'assign_preparation_employee',

            // Preparation Employee
            'view_preparation_dashboard',
            'view_orders',
            'update_order_status_to_preparing',
            'update_order_status_to_ready',

            // Normal Employee (Tasks)
            'employee_dashboard',
            'employee_tasks',
            'employee_update_task_progress',
        ];

        foreach ($permissions as $perm) {

            Permission::firstOrCreate(
                ['name' => $perm],
                [
                    'id' => (string) Str::uuid(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
