<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <-- import Str

class AdditionalRolesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الأدوار الجديدة
        $rolesToCreate = [
            [
                'name' => 'warehouse_employee',
                'display_name' => 'موظف مخزن',
                'description' => 'موظف مسؤول عن مهام المخزن'
            ],
            [
                'name' => 'warehouse_manager',
                'display_name' => 'موظف مسؤول مخزن',
                'description' => 'مسؤول عن إدارة المخزن'
            ],
            [
                'name' => 'admin_employee',
                'display_name' => 'موظف إداري',
                'description' => 'موظف مسؤول عن المهام الإدارية'
            ],
            [
                'name' => 'photographer',
                'display_name' => 'موظف تصوير',
                'description' => 'موظف مسؤول عن تصوير الأجهزة'
            ],
            [
                'name' => 'photo_manager',
                'display_name' => 'موظف مسؤول تصوير',
                'description' => 'مسؤول عن إدارة التصوير'
            ],
            // أضف أدوار أخرى حسب الحاجة
        ];

        foreach ($rolesToCreate as $roleData) {
            Role::firstOrCreate([
                'name' => $roleData['name'],
            ], [
                'id' => Str::uuid()->toString(),
                'display_name' => $roleData['display_name'],
                'description' => $roleData['description'],
            ]);
        }

        $this->command->info('Additional roles created successfully.');
    }
}
