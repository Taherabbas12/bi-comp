<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            [
                'name' => 'pending',
                'display_name' => 'انتظار',
            ],
            [
                'name' => 'received_by_store_employee',
                'display_name' => 'تم الاستلام من قبل موظف المخزن',
            ],
            [
                'name' => 'prepared_by_store_employee',
                'display_name' => 'تم اكمال التجهيز من قبل موظف المخزن',
            ],
            [
                'name' => 'order_has_problem',
                'display_name' => 'هناك مشكلة في الطلب',
            ],
            [
                'name' => 'delayed_to_tomorrow',
                'display_name' => 'يتأخر الطلب الى غداً',
            ],
            [
                'name' => 'sent_for_delivery',
                'display_name' => 'تم تسليم اللابتوب للتوصيل',
            ],
            [
                'name' => 'returned',
                'display_name' => 'راجع',
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('order_statuses')->insert([
                'id'           => Str::uuid(),
                'name'         => $status['name'],
                'display_name' => $status['display_name'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}