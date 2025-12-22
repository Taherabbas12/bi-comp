@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card text-white bg-dark border-light shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="h5 mb-0">👤 تفاصيل المستخدم</h1>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            {{-- العمود الأول --}}
                            <div class="col-12 col-md-6">
                                <p><strong>👤 الاسم:</strong><br>{{ $user->name }}</p>
                                <p><strong>📧 البريد:</strong><br>{{ $user->email }}</p>
                                <p><strong>📞 الهاتف:</strong><br>{{ $user->phone ?? '—' }}</p>
                                <p><strong>✈️ التلكرام:</strong><br>{{ $user->telegram_id ?? '—' }}</p>
                            </div>

                            {{-- العمود الثاني --}}
                            <div class="col-12 col-md-6">
                                <p><strong>🎂 تاريخ الميلاد:</strong><br>{{ $user->birth_date ?? '—' }}</p>
                                <p><strong>🚻 الجنس:</strong><br>
                                    {{ $user->gender == 'male' ? 'ذكر' : ($user->gender == 'female' ? 'أنثى' : '—') }}
                                </p>
                                <p><strong>🆔 الرقم الوطني:</strong><br>{{ $user->national_id ?? '—' }}</p>
                                <p><strong>📍 العنوان:</strong><br>{{ $user->address ?? '—' }}</p>
                            </div>

                            <div class="col-12">
                                <hr class="border-secondary">
                                <p><strong>📝 ملاحظات:</strong><br>{{ $user->notes ?? '—' }}</p>
                            </div>

                            <div class="col-12">
                                <p>
                                    <strong>🎭 الدور:</strong>
                                    @if ($user->role)
                                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">غير معين</span>
                                    @endif
                                </p>
                            </div>

                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                ← رجوع
                            </a>
                            <small class="text-muted">
                                أنشئ في {{ $user->created_at->format('Y-m-d') }}
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
