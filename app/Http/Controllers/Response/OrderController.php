<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest; // نستخدم نفس Form Request من واجهة العميل
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User; // <-- نحتاجه لاختيار موظف التجهيز
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // جلب حالة pending بشكل آمن
        $pendingStatus = \App\Models\OrderStatus::where('name', 'pending')->first();
        $pendingStatusId = $pendingStatus ? $pendingStatus->id : null;

        // بناء الاستعلام الأساسي
        $ordersQuery = \App\Models\Order::with(['user', 'status', 'employee'])
            ->where('employee_id', $userId);

        // إذا كانت حالة pending موجودة، أضفها للنتائج
        if ($pendingStatusId) {
            $ordersQuery->orWhere('order_status_id', $pendingStatusId);
        }

        // جلب النتائج
        $orders = $ordersQuery->latest()->paginate(20);

        return view('response.orders.index', compact('orders'));
    }

    public function create()
    {
        // صلاحية: create_orders_for_customers_as_response
        // middleware: permission:create_orders_for_customers_as_response
        $laptops = Laptop::where('quantity', '>', 0)->get(); // فقط الأجهزة المتوفرة

        return view('response.orders.create', compact('laptops'));
    }

    public function store(StoreOrderRequest $request)
    {
        // صلاحية: create_orders_for_customers_as_response
        // middleware: permission:create_orders_for_customers_as_response

        $laptop = Laptop::where('barcode', $request->laptop_barcode)->firstOrFail();

        // حساب المبلغ الإجمالي
        $totalAmount = $laptop->price_numeric; // لطلب جهاز واحد

        // جلب حالة "قيد الانتظار"
        $pendingStatus = OrderStatus::where('name', 'pending')->firstOrFail();

        // إنشاء الطلب
        // ملاحظة: نستخدم auth()->id() كـ 'employee_id' لأن موظف الردود هو من قام بإنشاء الطلب.
        // 'user_id' في هذا السيناريو يُستخدم لتمثيل الزبون (ربما يكون NULL أو ID لزبون تم إنشاؤه مسبقًا).
        $order = Order::create([
            'user_id' => null, // أو ID لزبون تم إنشاؤه مسبقًا
            'order_status_id' => $pendingStatus->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'order_notes' => $request->order_notes,
            'notes' => $request->notes, // الحقل الجديد
            'source' => $request->source,
            'payment_type' => $request->payment_type,
            'installment_months' => $request->payment_type === 'installment' ? $request->installment_months : null,
            'total_amount' => $totalAmount,
            'employee_id' => auth()->id(), // <-- تعيين موظف الردود الحالي كموظف مسند إليه
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

        return redirect()->route('response.orders.success', $order->id)->with('success', 'تم إنشاء الطلب بنجاح!');
    }

    public function success(Order $order)
    {
        // تأكد أن الطلب يخص موظف الردود الحالي أو أن له حق الوصول
        if ($order->employee_id !== auth()->id()) {
            abort(403);
        }

        return view('response.orders.success', compact('order'));
    }

    public function show(Order $order)
    {
        // صلاحية: view_orders_for_response
        // middleware: permission:view_orders_for_response
        $order->load(['user', 'status', 'employee', 'items.laptop']);

        return view('response.orders.show', compact('order'));
    }

    public function confirmOrder(Request $request, Order $order)
    {
        // صلاحية: update_order_status_to_confirmed_by_response
        // middleware: permission:update_order_status_to_confirmed_by_response
        $confirmedStatus = OrderStatus::where('name', 'confirmed')->firstOrFail();
        $order->update([
            'order_status_id' => $confirmedStatus->id,
            // 'employee_id' => auth()->id() // <-- لا نحتاج لتعيينه مجددًا، لأنه مسند مسبقًا
        ]);

        // يمكنك هنا إرسال إشعار داخلي إلى موظف التجهيز الجديد
        // Notification::send($order->employee, new OrderAssignedNotification($order));

        return redirect()->back()->with('success', 'تم تأكيد الطلب.');
    }

    public function assignPreparation(Request $request, Order $order)
    {
        // صلاحية: assign_preparation_employee
        // middleware: permission:assign_preparation_employee
        $request->validate([
            'preparation_employee_id' => 'required|exists:users,id',
        ]);

        $preparationEmployee = User::find($request->preparation_employee_id);

        // تحقق من أن الموظف المُسند إليه هو من دور "preparation"
        if (! $preparationEmployee->role || $preparationEmployee->role->name !== 'preparation') {
            return back()->withErrors(['preparation_employee_id' => 'الموظف المُسند إليه يجب أن يكون من فئة "موظفي التجهيز".']);
        }

        $order->update([
            'employee_id' => $preparationEmployee->id, // <-- تعيين موظف التجهيز
            // يمكنك أيضًا تحديث الحالة إلى "قيد التجهيز" هنا إذا لزم الأمر
            // 'order_status_id' => OrderStatus::where('name', 'preparing')->first()->id
        ]);

        return redirect()->back()->with('success', 'تم تعيين موظف التجهيز للطلب #'.$order->id);
    }
}
