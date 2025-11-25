@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>👤 عرض المستخدم</h1>
        <p class="subtitle">عرض تفاصيل المستخدم: {{ $user->name }}</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>الاسم:</strong> {{ $user->name }}</p>
                    <p><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
                    <p><strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>الدور:</strong>
                        @if ($user->role)
                            <span class="badge bg-primary">{{ $user->role->name }}</span>
                        @else
                            <span class="text-muted">غير معين</span>
                        @endif
                    </p>
                    <!-- يمكنك إضافة حقول أخرى هنا -->
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">العودة إلى القائمة</a>
        </div>
    </div>
@endsection
