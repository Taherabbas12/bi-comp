@extends('admin.layouts.app')

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

                <div class="card text-white bg-dark border-light mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">عناصر الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">الجهاز</th>
                                        <th scope="col">الكمية</th>
                                        <th scope="col">السعر عند الطلب</th>
                                        <th scope="col">الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->laptop->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price_at_order) }} د.ع</td>
                                            <td>{{ number_format($item->price_at_order * $item->quantity) }} د.ع</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h5 class="mb-0">تحديث الحالة</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status_id" class="form-label">الحالة الجديدة</label>
                                <select name="status_id" id="status_id" class="form-select @error('status_id') is-invalid @enderror" required>
                                    @foreach (\App\Models\OrderStatus::all() as $status)
                                        <option value="{{ $status->id }}"
                                            {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">تعيين موظف التجهيز</label>
                                <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                    <option value="">لا تعيين</option>
                                    @foreach (\App\Models\User::whereHas('role', function ($q) {
                                        $q->where('name', 'warehouse_employee');
                                    })->get() as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">تحديث الحالة</button>
                        </form>
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
                            <strong>تم إنشاء الطلب:</strong> {{ $order->created_at->format('Y-m-d h:i A') }} <!-- 💥 تم تعديل التنسيق هنا -->
                        </p>
                        <p class="card-text">
                            <i class="bi bi-calendar-x"></i>
                            <strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y-m-d h:i A') }} <!-- 💥 تم تعديل التنسيق هنا -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection