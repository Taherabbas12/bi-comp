<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <-- import Str

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'view_response_dashboard',
            'view_preparation_dashboard',
            'view_laptops',
            'create_laptops',
            'edit_laptops',
            'delete_laptops',
            'view_orders',
            'update_order_status',
            'update_order_status_to_confirmed',
            'update_order_status_to_preparing',
            'update_order_status_to_ready',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',
        ];

        foreach ($permissions as $permissionName) {
            Permission::create([
                'id' => Str::uuid()->toString(), // <-- قم بتعيين 'id' يدويًا
                'name' => $permissionName,
            ]);
        }
    }
}
