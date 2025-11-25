<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $query = Laptop::query();

        // --- تطبيق الفلاتر ---
        if ($request->filled('hide_expired')) {
            $query->where('quantity', '>', 0);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->filled('ram')) {
            $query->where('ram', $request->ram);
        }
        if ($request->filled('storage')) {
            $query->where('storage', $request->storage);
        }
        if ($request->filled('screen')) {
            $query->where('screen', $request->screen);
        }
        if ($request->filled('processor')) {
            $query->where('processor', $request->processor);
        }
        if ($request->filled('gpu')) {
            $query->where('gpu', $request->gpu);
        }

        // --- فلترة الشاشة اللمسية ---
        if ($request->filled('touch') && $request->touch == '1') {
            $query->where('is_touch', true);
        }

        // --- فلترة الجهاز القلاب ---
        if ($request->filled('convertible') && $request->convertible == '1') {
            $query->where('is_convertible', true);
        }

        // --- فلترة مخصص للألعاب ---
        if ($request->filled('gaming') && $request->gaming == '1') {
            $query->where(function ($q) {
                $q->where('gpu', 'like', '%NVIDIA%')
                    ->orWhere('gpu', 'like', '%AMD%')
                    ->orWhere('gpu', 'like', '%RADEON%')
                    ->orWhere('gpu', 'like', '%MX150%')
                    ->orWhere('gpu', 'like', '%MX130%')
                    ->orWhere('gpu', 'like', '%QUADRO%')
                    ->orWhere('gpu', 'like', '%GTX 1050%')
                    ->orWhere('processor', 'like', '%I7%')
                    ->orWhere('processor', 'like', '%RYZEN%');
            });
        }

        // --- فلترة مخصص للمونتاج ---
        if ($request->filled('editing') && $request->editing == '1') {
            $query->where(function ($q) {
                $q->where('gpu', 'like', '%QUADRO%')
                    ->orWhere('gpu', 'like', '%NVIDIA%')
                    ->orWhere('gpu', 'like', '%AMD%')
                    ->orWhere('gpu', 'like', '%RADEON%')
                    ->orWhereRaw('CAST(REPLACE(REPLACE(`ram`, " GB", ""), " GB", "") AS UNSIGNED) >= ?', [16]);
            });
        }

        // --- فلترة السعر ---
        if ($request->filled('min_price') && $request->min_price !== '') {
            $minPrice = (int) $request->min_price * 1000; // التحويل التلقائي
            $query->where('price_numeric', '>=', $minPrice);
        }
        if ($request->filled('max_price') && $request->max_price !== '') {
            $maxPrice = (int) $request->max_price * 1000; // التحويل التلقائي
            $query->where('price_numeric', '<=', $maxPrice);
        }

        // --- الفرز ---
        $sortField = $request->input('sort', 'name');
        $sortOrder = $request->input('order', 'asc');
        if (in_array($sortField, ['name', 'brand', 'model', 'processor', 'ram', 'storage', 'screen', 'gpu'])) {
            $query->orderBy($sortField, $sortOrder);
        } elseif ($sortField === 'price') {
            $query->orderBy('price_numeric', $sortOrder);
        } elseif ($sortField === 'quantity') {
            $query->orderBy('quantity', $sortOrder);
        } else {
            $query->orderBy('name', $sortOrder); // افتراضي
        }

        $laptops = $query->get();

        // --- استخراج القيم الفريدة للفرز ---
        $brands = Laptop::select('brand')->distinct()->pluck('brand');
        $rams = Laptop::select('ram')->distinct()->pluck('ram');
        $storages = Laptop::select('storage')->distinct()->pluck('storage');
        $screens = Laptop::select('screen')->whereNotNull('screen')->where('screen', '!=', '')->distinct()->pluck('screen');
        $gpus = Laptop::select('gpu')->whereNotNull('gpu')->where('gpu', '!=', '')->distinct()->pluck('gpu');
        $processors = Laptop::select('processor')->distinct()->pluck('processor');

        return view('customer.laptops.index', compact('laptops', 'brands', 'rams', 'storages', 'screens', 'gpus', 'processors'));
    }

    public function show($barcode)
    {
        $laptop = Laptop::where('barcode', $barcode)->firstOrFail();
        // حساب الأقساط كما في الكود الأصلي
        $monthlyPayment10 = $laptop->calculateMonthlyPayment(10);
        $monthlyPayment11 = $laptop->calculateMonthlyPayment(11);

        return view('customer.laptops.show', compact('laptop', 'monthlyPayment10', 'monthlyPayment11'));
    }
}
