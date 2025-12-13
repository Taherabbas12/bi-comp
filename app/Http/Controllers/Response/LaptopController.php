<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaptopController extends Controller
{
    // -------------------------------
    // عرض الصفحة الأساسية
    // -------------------------------
    public function index(Request $request)
    {
        // جلب القيم الديناميكية للفلاتر
        $brands = Laptop::pluck('brand')->unique()->filter()->values();
        $rams = Laptop::pluck('ram')->unique()->filter()->values();
        $storages = Laptop::pluck('storage')->unique()->filter()->values();
        $screens = Laptop::pluck('screen')->unique()->filter()->values();
        $processors = Laptop::pluck('processor')->unique()->filter()->values();
        $gpus = Laptop::pluck('gpu')->unique()->filter()->values();

        $query = Laptop::query();
        $this->applyFilters($query, $request);

        $laptops = $query->paginate(20);

        return view('response.laptops.index', compact(
            'laptops',
            'brands',
            'rams',
            'storages',
            'screens',
            'processors',
            'gpus'
        ));
    }

    // -------------------------------
    // AJAX فلترة المنتجات
    // -------------------------------
    public function filter(Request $request)
    {
        $query = Laptop::query();
        $this->applyFilters($query, $request);

        $laptops = $query->paginate(20);

        if ($request->ajax()) {
            return view('response.laptops.cards', compact('laptops'))->render();
        }

        return redirect()->route('response.laptops.index');
    }

    // -------------------------------
    // دالة تطبيق الفلاتر
    // -------------------------------
    private function applyFilters($query, Request $request)
    {
        // بحث عام
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('brand', 'like', "%{$s}%")
                    ->orWhere('barcode', 'like', "%{$s}%");
            });
        }

        // فلاتر مباشرة
        foreach (['brand', 'ram', 'storage', 'screen', 'processor', 'gpu'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        // السعر
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Touch
        if ($request->boolean('touch')) {
            $query->where('is_touch', 1);
        }

        // Convertible
        if ($request->boolean('convertible')) {
            $query->where('is_convertible', 1);
        }

        // Gaming
        if ($request->boolean('gaming')) {
            $query->where(function ($q) {
                $q->where('gpu', 'like', '%NVIDIA%')
                    ->orWhere('gpu', 'like', '%RADEON%')
                    ->orWhere('processor', 'like', '%i7%')
                    ->orWhere('processor', 'like', '%Ryzen%');
            });
        }

        // Editing
        if ($request->boolean('editing')) {
            $query->where(function ($q) {
                $q->where('gpu', 'like', '%QUADRO%')
                    ->orWhere('gpu', 'like', '%NVIDIA%')
                    ->orWhere('ram', '>=', 16);
            });
        }

        // إخفاء المنتهية
        if ($request->boolean('hide_expired')) {
            $query->where('quantity', '>', 0);
        }

        // الفرز
        if ($request->filled('sort')) {
            $query->orderBy(
                $request->sort,
                $request->get('order', 'asc')
            );
        } else {
            $query->latest();
        }
    }

    // -------------------------------
    // صفحة إنشاء الطلب
    // -------------------------------
    public function createOrder(Laptop $laptop)
    {
        return view('response.laptops.create_order', compact('laptop'));
    }

    // -------------------------------
    // حفظ الطلب
    // -------------------------------
public function storeOrder(Request $request, Laptop $laptop)
{
    try {
        $request->validate([
            'client_name' => 'required|max:255',
            'client_phone' => 'required|numeric|digits_between:10,15',
            'client_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'payment_type' => 'required|in:cash,installment,credit',
            'installment_months' => 'required_if:payment_type,installment|nullable|integer|min:1',
            'order_notes' => 'required|string|max:1000',
            'notes' => 'required|string|max:1000',
        ]);

        // تأكد من أن السعر موجود
        if ($laptop->price_numeric === null || !is_numeric($laptop->price_numeric)) {
            return back()->withErrors(['laptop_price' => 'سعر اللابتوب غير متوفر']);
        }

        // تأكد من أن الكمية متوفرة
        if ($laptop->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'الكمية المطلوبة غير متوفرة']);
        }

        $pending = OrderStatus::where('name', 'pending')->firstOrFail();

        // Debug: قبل إنشاء الطلب
        \Log::info('Before Order Creation', [
            'user_id' => auth()->id(),
            'laptop_price' => $laptop->price_numeric,
            'quantity' => $request->quantity
        ]);

        // إنشاء الطلب
        $order = Order::create([
            'id' => (string) Str::uuid(),
            'employee_id' => auth()->id(),
            'order_status_id' => $pending->id,
            'customer_name' => $request->client_name,
            'customer_phone' => $request->client_phone,
            'customer_address' => $request->client_address,
            'source' => 'response',
            'payment_type' => $request->payment_type,
            'total_amount' => $laptop->price_numeric * $request->quantity,
            'installment_months' => $request->payment_type === 'installment' ? $request->installment_months : null,
            'order_notes' => $request->order_notes,
            'notes' => $request->notes,
            'user_id' => null,
        ]);

        \Log::info('Order Created', ['order_id' => $order->id]);

        // إنشاء عنصر الطلب
        $orderItem = OrderItem::create([
            'id' => (string) Str::uuid(),
            'order_id' => $order->id,
            'laptop_id' => $laptop->id,
            'quantity' => $request->quantity,
            'price_at_order' => $laptop->price_numeric,
        ]);

        \Log::info('Order Item Created', ['order_item_id' => $orderItem->id]);

        // تحديث كمية اللابتوب
        $laptop->decrement('quantity', $request->quantity);

        \Log::info('Laptop quantity updated', ['new_quantity' => $laptop->quantity]);

        // التحقق من وجود الطلب
        $storedOrder = \App\Models\Order::find($order->id);
        if (!$storedOrder) {
            \Log::error('Order not found after creation', ['order_id' => $order->id]);
            throw new \Exception('Order not found after creation');
        }

        \Log::info('Order exists in database', ['order_id' => $order->id]);

        return redirect()->route('response.orders.show', $order->id)
            ->with('success', 'تم إنشاء الطلب بنجاح');

    } catch (\Exception $e) {
        \Log::error('Store Order Error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // Debug: عرض الخطأ مباشرة
        echo "<div style='position:fixed;top:0;left:0;background:red;color:white;padding:20px;z-index:9999;'>";
        echo "<h3>Error creating order:</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<p>File: " . $e->getFile() . " (Line: " . $e->getLine() . ")</p>";
        echo "</div>";

        sleep(5);
        return back()->withErrors(['general' => $e->getMessage()]);
    }
}
}
