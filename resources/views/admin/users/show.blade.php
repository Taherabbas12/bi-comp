@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="card text-white bg-dark border-light shadow-sm">

                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0">👤 تفاصيل المستخدم</h6>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <div class="row g-3">

                            <!-- Personal Info Section -->
                            <div class="col-12">
                                <h6 class="text-warning border-bottom border-secondary pb-2">👤 البيانات الشخصية</h6>
                            </div>

                            <div class="col-12 col-md-6">
                                <p><strong>👤 الاسم:</strong><br>{{ $user->name }}</p>
                                <p><strong>📧 البريد:</strong><br>{{ $user->email }}</p>
                                <p><strong>📞 الهاتف:</strong><br>{{ $user->phone ?? '—' }}</p>
                                <p><strong>✈️ التلكرام:</strong><br>{{ $user->telegram_id ?? '—' }}</p>
                            </div>

                            <div class="col-12 col-md-6">
                                <p><strong>🎂 تاريخ الميلاد:</strong><br>{{ $user->birth_date ?? '—' }}</p>
                                <p><strong>🚻 الجنس:</strong><br>
                                    {{ $user->gender == 'male' ? 'ذكر' : ($user->gender == 'female' ? 'أنثى' : '—') }}
                                </p>
                                <p><strong>🆔 الرقم الوطني:</strong><br>{{ $user->national_id ?? '—' }}</p>
                                <p><strong>📍 العنوان:</strong><br>{{ $user->address ?? '—' }}</p>
                            </div>

                            <!-- Employment Info Section -->
                            <div class="col-12 mt-3">
                                <h6 class="text-info border-bottom border-secondary pb-2">💼 بيانات التوظيف والراتب</h6>
                            </div>

                            <div class="col-12 col-md-6">
                                <p><strong>💰 الراتب:</strong><br>
                                    @if ($user->salary)
                                        <span class="text-success fw-bold">
                                            {{ number_format($user->salary, 2) }} {{ $user->salary_currency ?? 'IQD' }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </p>
                                <p><strong>📊 القسم:</strong><br>{{ $user->department ?? '—' }}</p>
                            </div>

                            <div class="col-12 col-md-6">
                                <p><strong>🎯 المسمى الوظيفي:</strong><br>{{ $user->position ?? '—' }}</p>
                                <p><strong>📋 نوع التوظيف:</strong><br>
                                    @if ($user->employment_type)
                                        <span class="badge bg-info">{{ $user->employment_type }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </p>
                                <p><strong>📅 تاريخ التعيين:</strong><br>
                                    {{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('Y-m-d') : '—' }}
                                </p>
                            </div>

                            <!-- Notes Section -->
                            <div class="col-12">
                                <hr class="border-secondary">
                                <p><strong>📝 ملاحظات:</strong><br>{{ $user->notes ?? '—' }}</p>
                            </div>

                            <!-- Role Section -->
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

                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center mt-4 gap-2">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                                    ← رجوع
                                </a>
                                <a href="{{ route('work-schedules.edit', $user) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-clock"></i> أوقات العمل
                                </a>
                                <a href="{{ route('user-day-offs.edit', $user) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-moon"></i> يوم الإجازة
                                </a>
                                <a href="{{ route('admin.attachments.index', $user) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-paperclip"></i> المرفقات
                                </a>
                            </div>
                            <small class="text-muted">
                                {{ $user->created_at->format('Y-m-d') }}
                            </small>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
