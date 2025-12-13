@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h1 class="h4 mb-0">👤 عرض المستخدم</h1>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted mb-4">عرض تفاصيل المستخدم: {{ $user->name }}</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-person"></i>
                                    <strong>الاسم:</strong> {{ $user->name }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-envelope"></i>
                                    <strong>البريد الإلكتروني:</strong> {{ $user->email }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-calendar-check"></i>
                                    <strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-calendar-x"></i>
                                    <strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <p class="card-text">
                                    <i class="bi bi-person-badge"></i>
                                    <strong>الدور:</strong>
                                    @if ($user->role)
                                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                                    @else
                                        <span class="text-muted">غير معين</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">العودة إلى القائمة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection