<?php

// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'status', 'employee', 'items.laptop'])->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'status', 'employee', 'items.laptop']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,id',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        $order->update([
            'order_status_id' => $request->status_id,
            'employee_id' => $request->employee_id,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب وموظف التجهيز بنجاح.');
    }
}
