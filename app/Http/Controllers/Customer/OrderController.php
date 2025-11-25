<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest; // ننشئها لاحقًا
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;

class OrderController extends Controller
{
    public function create()
    {
        // عادةً ما يتم توجيه المستخدم من صفحة المنتج مباشرة
        abort(404); // أو إعادة التوجيه إلى الصفحة الرئيسية
    }

    public function store(StoreOrderRequest $request)
    {
        $laptop = Laptop::where('barcode', $request->laptop_barcode)->firstOrFail();

        // حساب المبلغ الإجمالي
        $totalAmount = $laptop->price_numeric; // لطلب جهاز واحد

        // جلب حالة "قيد الانتظار"
        $pendingStatus = OrderStatus::where('name', 'pending')->firstOrFail();

        // إنشاء الطلب
        $order = Order::create([
            'user_id' => auth()->id(), // المستخدم الحالي
            'order_status_id' => $pendingStatus->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'order_notes' => $request->order_notes,
            'notes' => $request->notes, // <-- الحقل الجديد
            'source' => $request->source,
            'payment_type' => $request->payment_type,
            'installment_months' => $request->payment_type === 'installment' ? $request->installment_months : null,
            'total_amount' => $totalAmount,
            // 'employee_id' => null, // <-- يُترك فارغًا في البداية
        ]);

        // إنشاء عنصر الطلب
        OrderItem::create([
            'order_id' => $order->id,
            'laptop_id' => $laptop->id,
            'quantity' => 1, // دائمًا 1 في هذا السيناريو
            'price_at_order' => $laptop->price_numeric,
        ]);

        // خصم الكمية من المخزون (اختياري، حسب سياسة العمل)
        // $laptop->decrement('quantity');

        return redirect()->route('orders.success', $order->id)->with('success', 'تم إرسال طلبك بنجاح!');
    }

    public function success(Order $order)
    {
        // تأكد أن الطلب يخص المستخدم الحالي
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.orders.success', compact('order'));
    }
}
