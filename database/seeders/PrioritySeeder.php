<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrioritySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $prioritiesToCreate = [
            // الأولويات الأساسية (Primary)
            [
                'name' => 'critical',
                'display_name' => 'حرج',
                'color_code' => '#e74c3c', // أحمر داكن
                'is_primary' => true,
            ],
            [
                'name' => 'urgent',
                'display_name' => 'عاجل',
                'color_code' => '#dc3545', // أحمر
                'is_primary' => true,
            ],
            [
                'name' => 'high',
                'display_name' => 'مرتفع',
                'color_code' => '#f39c12', // برتقالي
                'is_primary' => true,
            ],
            [
                'name' => 'medium',
                'display_name' => 'متوسط',
                'color_code' => '#ffc107', // أصفر
                'is_primary' => true,
            ],
            [
                'name' => 'low',
                'display_name' => 'منخفض',
                'color_code' => '#17a2b8', // أزرق فاتح
                'is_primary' => true,
            ],
            [
                'name' => 'normal',
                'display_name' => 'عادي',
                'color_code' => '#6c757d', // رمادي
                'is_primary' => true,
            ],
            // الأولويات الفرعية (Secondary)
            [
                'name' => 'planning',
                'display_name' => 'مخطط',
                'color_code' => '#6f42c1', // بنفسجي
                'is_primary' => false,
            ],
            [
                'name' => 'review',
                'display_name' => 'تحت المراجعة',
                'color_code' => '#fd7e14', // برتقالي داكن
                'is_primary' => false,
            ],
            [
                'name' => 'pending',
                'display_name' => 'في الانتظار',
                'color_code' => '#adb5bd', // رمادي فاتح
                'is_primary' => false,
            ],
            [
                'name' => 'on_hold',
                'display_name' => 'معلق',
                'color_code' => '#6c757d', // رمادي
                'is_primary' => false,
            ],
            [
                'name' => 'deferred',
                'display_name' => 'مؤجل',
                'color_code' => '#20c997', // أخضر مائي
                'is_primary' => false,
            ],
            // أضف أولويات فرعية أخرى حسب الحاجة
        ];

        foreach ($prioritiesToCreate as $priorityData) {
            Priority::firstOrCreate([
                'name' => $priorityData['name'],
            ], [
                'id' => Str::uuid()->toString(),
                'display_name' => $priorityData['display_name'],
                'color_code' => $priorityData['color_code'],
                'is_primary' => $priorityData['is_primary'], // <-- افترض وجود هذا الحقل في الجدول
            ]);
        }

        $this->command->info('Default priorities created successfully.');
    }
}
