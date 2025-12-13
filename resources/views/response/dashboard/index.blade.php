{{-- @extends('response.layouts.app') --}}
@extends('layouts.employee-layout')

@section('title', 'لوحة التحكم')

@section('content')

    @php
        $userId = auth()->id();

        $total = \App\Models\Order::where('employee_id', $userId)->count();

        $confirmedStatus = \App\Models\OrderStatus::where('name', 'confirmed')->first();
        $pendingStatus = \App\Models\OrderStatus::where('name', 'pending')->first();
        $readyStatus = \App\Models\OrderStatus::where('name', 'ready')->first();

        $confirmed = $confirmedStatus
            ? \App\Models\Order::where('employee_id', $userId)->where('order_status_id', $confirmedStatus->id)->count()
            : 0;

        $pending = $pendingStatus
            ? \App\Models\Order::where('employee_id', $userId)->where('order_status_id', $pendingStatus->id)->count()
            : 0;

        $ready = $readyStatus
            ? \App\Models < Order::where('employee_id', $userId)->where('order_status_id', $readyStatus->id)->count()
            : 0;

        $lastUpdates = \App\Models\Order::where('employee_id', $userId)
            ->latest('updated_at')
            ->take(5)
            ->get();
    @endphp

    <h2 class="mb-4">مرحباً {{ auth()->user()->name }} 👋</h2>

    <div class="row g-3">

        {{-- إجمالي الطلبات --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>إجمالي الطلبات</h5>
                <h2 class="text-primary">{{ $total }}</h2>
            </div>
        </div>

        {{-- المؤكدة --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>الطلبات المؤكدة</h5>
                <h2 class="text-success">{{ $confirmed }}</h2>
            </div>
        </div>

        {{-- بانتظار التأكيد --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>بانتظار التأكيد</h5>
                <h2 class="text-warning">{{ $pending }}</h2>
            </div>
        </div>

        {{-- الجاهزة --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>الطلبات الجاهزة</h5>
                <h2 class="text-info">{{ $ready }}</h2>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4>آخر التحديثات</h4>

    <ul class="list-group">
        @forelse($lastUpdates as $order)
            <li class="list-group-item d-flex justify-content-between">
                <span>طلب رقم #{{ $order->id }}</span>
                <small>{{ $order->updated_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="list-group-item">لا توجد تحديثات.</li>
        @endforelse
    </ul>

@endsection
