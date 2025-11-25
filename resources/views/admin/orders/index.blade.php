@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>📦 إدارة الطلبات</h1>
        <p class="subtitle">عرض وتحديث حالة الطلبات</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>قائمة الطلبات</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">زبون</th>
                            <th scope="col">حالة</th>
                            <th scope="col">موظف التجهيز</th>
                            <th scope="col">السعر الإجمالي</th>
                            <th scope="col">تاريخ الطلب</th>
                            <th scope="col">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $order->status->name === 'pending' ? 'warning' : ($order->status->name === 'delivered' ? 'success' : 'primary') }}">
                                        {{ $order->status->display_name ?? 'غير معروف' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($order->employee)
                                        {{ $order->employee->name }}
                                    @else
                                        <span class="text-muted">غير معين</span>
                                    @endif
                                </td>
                                <td>{{ number_format($order->total_amount) }} د.ع</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">لا توجد طلبات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orders->links() }}
        </div>
    </div>
@endsection
