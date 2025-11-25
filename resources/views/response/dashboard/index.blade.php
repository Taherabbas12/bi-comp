@extends('admin.layouts.app') <!-- نستخدم نفس layout الإدارة -->

@section('content')
    <div class="header">
        <h1>👨‍💼 لوحة تحكم موظف الردود</h1>
        <p class="subtitle">مرحبًا، {{ auth()->user()->name }}</p>
    </div>

    <div class="row g-4">
        <!-- عدد الطلبات المُسnde إليك -->
        <div class="col-md-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-cart-check" style="font-size: 3rem; color: var(--accent);"></i>
                    <h5 class="card-title mt-3">الطلبات المُسندة إلي</h5>
                    <p class="card-text display-6">{{ \App\Models\Order::where('employee_id', auth()->id())->count() }}</p>
                </div>
            </div>
        </div>
        <!-- عدد الطلبات المُ-confirm -->
        <div class="col-md-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: var(--success);"></i>
                    <h5 class="card-title mt-3">الطلبات المؤكدة</h5>
                    <p class="card-text display-6">
                        {{ \App\Models\Order::where('employee_id', auth()->id())->where('order_status_id', \App\Models\OrderStatus::where('name', 'confirmed')->first()->id)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <!-- ... واجهات أخرى ... -->
    </div>
@endsection
