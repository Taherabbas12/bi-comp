<?php

namespace Database\seeders;

use App\Models\Laptop;
use Illuminate\Database\Seeder;

class LaptopSeeder extends Seeder
{
    public function run()
    {
        // استيراد البيانات من الملف
        $dataFile = database_path('seeders/data.php');
        if (!file_exists($dataFile)) {
            $this->command->error("الملف data.php غير موجود في مسار database/seeders/");
            return;
        }

        $processedLaptops = include $dataFile;

        foreach ($processedLaptops as $laptopData) {
            // تحويل السعر من نص إلى رقم
            $priceNumeric = $this->priceToNumber($laptopData['price']);

            Laptop::create([
                'id' => \Illuminate\Support\Str::orderedUuid()->toString(),
                'quantity' => $laptopData['quantity'],
                'barcode' => $laptopData['barcode'],
                'name' => $laptopData['name'],
                'price_numeric' => $priceNumeric,
                'price_display' => $laptopData['price'],
                'brand' => $laptopData['brand'],
                'model' => $laptopData['model'],
                'processor' => $laptopData['processor'],
                'ram' => $laptopData['ram'],
                'storage' => $laptopData['storage'],
                'screen' => $laptopData['screen'],
                'is_touch' => $laptopData['is_touch'],
                'is_convertible' => $laptopData['is_convertible'],
                'gpu' => $laptopData['gpu'],
            ]);
        }

        $this->command->info(count($processedLaptops) . ' جهاز تم إنشاؤه بنجاح!');
    }

    private function priceToNumber($priceStr)
    {
        // إزالة "د.ع" و الفواصل و المسافات
        return (int) str_replace(['د.ع', ',', ' '], '', $priceStr);
    }
}
