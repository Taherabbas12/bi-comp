@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>📦 تفاصيل الطلب #{{ $order->id }}</h1>
        <p class="subtitle">عرض معلومات الطلب وتحديث حالته</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>اسم الزبون:</strong> {{ $order->customer_name }}</p>
                            <p><strong>رقم الهاتف:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>العنوان:</strong> {{ $order->customer_address }}</p>
                            <p><strong>ملاحظات الطلب:</strong> {{ $order->order_notes ?? '-' }}</p>
                            <p><strong>ملاحظات إضافية:</strong> {{ $order->notes ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>مصدر الطلب:</strong> {{ $order->source }}</p>
                            <p><strong>طريقة الدفع:</strong> {{ $order->payment_type }}</p>
                            <p><strong>عدد شهور التقسيط:</strong> {{ $order->installment_months ?? 'نقدًا' }}</p>
                            <p><strong>السعر الإجمالي:</strong> {{ number_format($order->total_amount) }} د.ع</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>عناصر الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
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

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>تحديث الحالة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status_id" class="form-label">الحالة الجديدة</label>
                            <select name="status_id" id="status_id" class="form-select" required>
                                @foreach (\App\Models\OrderStatus::all() as $status)
                                    <option value="{{ $status->id }}"
                                        {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">تعيين موظف التجهيز</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">لا تعيين</option>
                                @foreach (\App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'preparation');
        })->get() as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">تحديث الحالة</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>معلومات الحالة</h5>
                </div>
                <div class="card-body">
                    <p><strong>الحالة الحالية:</strong> <span
                            class="badge bg-{{ $order->status->name === 'pending' ? 'warning' : ($order->status->name === 'delivered' ? 'success' : 'primary') }}">{{ $order->status->display_name }}</span>
                    </p>
                    <p><strong>موظف التجهيز:</strong> {{ $order->employee ? $order->employee->name : 'غير معين' }}</p>
                    <p><strong>تم إنشاء الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
