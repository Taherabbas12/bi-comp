<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskStatusSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $statusesToCreate = [
            [
                'name' => 'todo',
                'display_name' => 'مطلوب',
                'color_code' => '#007bff', // Blue
            ],
            [
                'name' => 'in_progress',
                'display_name' => 'قيد التنفيذ',
                'color_code' => '#ffc107', // Yellow
            ],
            [
                'name' => 'completed',
                'display_name' => 'منتهي',
                'color_code' => '#28a745', // Green
            ],
            [
                'name' => 'on_hold',
                'display_name' => 'معلق',
                'color_code' => '#6c757d', // Gray
            ],
            [
                'name' => 'cancelled',
                'display_name' => 'ملغي',
                'color_code' => '#dc3545', // Red
            ],
            // Add more statuses as needed
        ];

        foreach ($statusesToCreate as $statusData) {
            TaskStatus::firstOrCreate([
                'name' => $statusData['name'],
            ], [
                'id' => Str::uuid()->toString(),
                'display_name' => $statusData['display_name'],
                'color_code' => $statusData['color_code'],
            ]);
        }

        $this->command->info('Default task statuses created successfully.');
    }
}
