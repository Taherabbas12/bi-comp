@extends('layouts.employee-layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">📦 الطلبات</h1>
                   
                        <a href="{{ route('response.orders.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إنشاء طلب جديد
                        </a>
                
                 </div>
                <p class="text-muted mb-4">عرض الطلبات المُسندة إليك أو قيد الانتظار</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row g-3">
                    @forelse($orders as $order)
                        <div class="col-12 col-lg-6 col-xl-4">
                            <div class="card text-white bg-dark border-light h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">
                                            {{ $order->items->first()?->laptop?->name ?? 'جهاز غير محدد' }}
                                            <br>
                                            <small class="text-muted">{{ $order->items->first()?->laptop?->specs ?? 'لا توجد مواصفات' }}</small>
                                        </h5>
                                        <span class="badge bg-{{ $order->status->name === 'pending' ? 'warning text-dark' : ($order->status->name === 'delivered' ? 'success' : 'primary') }}">
                                            {{ $order->status->display_name ?? 'غير معروف' }}
                                        </span>
                                    </div>
                                    <p class="card-text">
                                        <i class="bi bi-person"></i>
                                        <strong>الزبون:</strong> {{ $order->customer_name }}
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-person-badge"></i>
                                        <strong>موظف التجهيز:</strong>
                                        @if ($order->employee)
                                            {{ $order->employee->name }}
                                        @else
                                            <span class="text-muted">غير معين</span>
                                        @endif
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-currency-dollar"></i>
                                        <strong>السعر الإجمالي:</strong> {{ number_format($order->total_amount) }} د.ع
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d h:i A') }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top-light">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('response.orders.show', $order) }}"
                                            class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> عرض التفاصيل
                                        </a>
                                        <!--@if ($order->status->name === 'pending')-->
                                        <!--    <form action="{{ route('response.orders.confirm', $order) }}" method="POST"-->
                                        <!--        class="d-inline">-->
                                        <!--        @csrf-->
                                        <!--        <button type="submit" class="btn btn-outline-success"-->
                                        <!--            onclick="return confirm('هل أنت متأكد من تأكيد هذا الطلب؟')">-->
                                        <!--            <i class="bi bi-check-circle"></i> تأكيد-->
                                        <!--        </button>-->
                                        <!--    </form>-->
                                        <!--@endif-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-cart-x" style="font-size: 3rem; color: #adb5bd;"></i>
                                <p class="mt-3">لا توجد طلبات.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection