<?php

namespace App\Http\Controllers\Preparation;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;

class OrderController extends Controller
{
    public function index()
    {
        // صلاحية: view_orders
        // middleware: permission:view_orders
        // فقط الطلبات المؤكدة أو قيد التجهيز
        $preparingStatus = OrderStatus::where('name', 'preparing')->firstOrFail();
        $readyStatus = OrderStatus::where('name', 'ready')->firstOrFail();
        $orders = Order::whereIn('order_status_id', [$preparingStatus->id, $readyStatus->id])
            ->with(['user', 'status', 'employee'])
            ->paginate(20);

        return view('preparation.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // صلاحية: view_orders
        // middleware: permission:view_orders
        $order->load(['user', 'status', 'items.laptop']);

        return view('preparation.orders.show', compact('order'));
    }

    public function markAsPreparing(Order $order)
    {
        // صلاحية: update_order_status_to_preparing
        // middleware: permission:update_order_status_to_preparing
        $preparingStatus = OrderStatus::where('name', 'preparing')->firstOrFail();
        $order->update([
            'order_status_id' => $preparingStatus->id,
            'employee_id' => auth()->id(), // تعيين موظف التجهيز الحالي
        ]);

        return redirect()->back()->with('success', 'Order marked as preparing.');
    }

    public function markAsReady(Order $order)
    {
        // صلاحية: update_order_status_to_ready
        // middleware: permission:update_order_status_to_ready
        $readyStatus = OrderStatus::where('name', 'ready')->firstOrFail();
        $order->update([
            'order_status_id' => $readyStatus->id,
            'employee_id' => auth()->id(), // تعيين موظف التجهيز الحالي
        ]);

        return redirect()->back()->with('success', 'Order marked as ready for delivery.');
    }
}
