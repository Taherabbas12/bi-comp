@extends('layouts.employee-layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">📦 تفاصيل الطلب #{{ $order->id }}</h1>
                </div>
                <p class="text-muted mb-4">عرض معلومات الطلب وتحديث حالته</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h5 class="mb-0">معلومات الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-person"></i>
                                    <strong>اسم الزبون:</strong> {{ $order->customer_name }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-telephone"></i>
                                    <strong>رقم الهاتف:</strong> {{ $order->customer_phone }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-geo-alt"></i>
                                    <strong>العنوان:</strong> {{ $order->customer_address }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-sticky"></i>
                                    <strong>ملاحظات الطلب:</strong> {{ $order->order_notes ?? '-' }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-sticky"></i>
                                    <strong>ملاحظات إضافية:</strong> {{ $order->notes ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-laptop"></i>
                                    <strong>مصدر الطلب:</strong> {{ $order->source }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-credit-card"></i>
                                    <strong>طريقة الدفع:</strong> {{ $order->payment_type }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-calendar-month"></i>
                                    <strong>عدد شهور التقسيط:</strong> {{ $order->installment_months ?? 'نقدًا' }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-currency-dollar"></i>
                                    <strong>السعر الإجمالي:</strong> {{ number_format($order->total_amount) }} د.ع
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 💥 قسم عناصر الطلب (تم تعديله) -->
                <div class="card text-white bg-dark border-light mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">عناصر الطلب</h5>
                    </div>
                    <div class="card-body">
                        @if($order->items->count() > 0)
                            <div class="row g-3"> <!-- 💥 استخدام Grid Bootstrap -->
                                @foreach ($order->items as $item)
                                    <div class="col-12"> <!-- 💥 كل عنصر في عمود كامل (يمكن تغييره حسب الحاجة) -->
                                        <div class="card text-white bg-secondary border-light"> <!-- 💥 كرت لعنصر الطلب -->
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $item->laptop->name }}</h6>
                                                        <p class="card-text">
                                                            <i class="bi bi-tag"></i>
                                                            <strong>السعر عند الطلب:</strong> {{ number_format($item->price_at_order) }} د.ع
                                                        </p>
                                                    </div>
                                                    <span class="badge bg-info rounded-pill">
                                                        <i class="bi bi-cart-plus"></i> الكمية: {{ $item->quantity }}
                                                    </span>
                                                </div>
                                                <p class="card-text mt-2">
                                                    <i class="bi bi-currency-dollar"></i>
                                                    <strong>الإجمالي:</strong> {{ number_format($item->price_at_order * $item->quantity) }} د.ع
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted">لا توجد عناصر في هذا الطلب.</p>
                        @endif
                    </div>
                </div>
                <!-- 💥 النهاية -->
            </div>

            <div class="col-12 col-lg-4">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h5 class="mb-0">تعيين موظف التجهيز</h5>
                    </div>
                    <div class="card-body">
                        @if ($order->status->name !== 'delivered')
                            <form action="{{ route('response.orders.assignPreparation', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="preparation_employee_id" class="form-label">الموظف</label>
                                    <select name="preparation_employee_id" id="preparation_employee_id" class="form-select @error('preparation_employee_id') is-invalid @enderror">
                                        <option value="">اختر موظفًا...</option>
                                        @foreach (\App\Models\User::whereHas('role', function ($q) {
                                            $q->where('name', 'warehouse_employee');
                                        })->get() as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('preparation_employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">تعيين موظف</button>
                            </form>
                        @else
                            <p class="text-center text-muted">لا يمكن تعيين موظف لطلب تم تسليمه.</p>
                        @endif
                    </div>
                </div>

                <div class="card text-white bg-dark border-light mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">معلومات الحالة</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <i class="bi bi-clipboard-check"></i>
                            <strong>الحالة الحالية:</strong>
                            <span class="badge bg-{{ $order->status->name === 'pending' ? 'warning text-dark' : ($order->status->name === 'delivered' ? 'success' : 'primary') }}">{{ $order->status->display_name }}</span>
                        </p>
                        <p class="card-text">
                            <i class="bi bi-person-bounding-box"></i>
                            <strong>موظف التجهيز:</strong> {{ $order->employee ? $order->employee->name : 'غير معين' }}
                        </p>
                        <p class="card-text">
                            <i class="bi bi-calendar-check"></i>
                            <strong>تم إنشاء الطلب:</strong> {{ $order->created_at->format('Y-m-d h:i A') }}
                        </p>
                        <p class="card-text">
                            <i class="bi bi-calendar-x"></i>
                            <strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection