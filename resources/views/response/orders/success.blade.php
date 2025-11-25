@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>✅ تم إنشاء الطلب بنجاح!</h1>
        <p class="subtitle">رقم الطلب: #{{ $order->id }}</p>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: var(--success);"></i>
            <h3 class="mt-3">تم إرسال طلبك بنجاح.</h3>
            <p>سيتم التواصل مع الزبون قريبًا.</p>
            <a href="{{ route('response.orders.index') }}" class="btn btn-primary">العودة إلى الطلبات</a>
        </div>
    </div>
@endsection
