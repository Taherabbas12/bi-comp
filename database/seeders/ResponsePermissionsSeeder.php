<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <-- import Str

class ResponsePermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $responsePermissions = [
            'view_response_dashboard',
            'view_orders_for_response',
            'update_order_status_to_confirmed_by_response',
            'assign_preparation_employee',
            'create_orders_for_customers_as_response',
        ];

        foreach ($responsePermissions as $permissionName) {
            // --- التعديل هنا ---
            // تحقق من وجود الصلاحية أولاً
            $existingPermission = Permission::where('name', $permissionName)->first();

            if (! $existingPermission) {
                // إذا لم تكن موجودة، قم بإنشائها
                Permission::create([
                    'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
                    'name' => $permissionName,
                ]);
                $this->command->info("Permission '$permissionName' created.");
            } else {
                // إذا كانت موجودة، قم بتسجيل رسالة
                $this->command->warn("Permission '$permissionName' already exists. Skipping.");
            }
            // --- التعديل هنا ---
        }
    }
}
