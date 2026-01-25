@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 mb-1">🌙 إجازة الموظف</h1>
                        <small class="text-muted">{{ $user->name }}</small>
                    </div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary">
                        ← رجوع
                    </a>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Default Settings Info -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading">📌 إعدادات الشركة</h6>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <small><strong>وقت الدخول:</strong> {{ $settings->official_check_in }}</small>
                        </div>
                        <div class="col-md-6">
                            <small><strong>وقت الخروج:</strong> {{ $settings->official_check_out }}</small>
                        </div>
                        <div class="col-md-6">
                            <small><strong>ساعات العمل:</strong> {{ $settings->working_hours }} ساعات</small>
                        </div>
                        <div class="col-md-6">
                            <small><strong>أيام العمل:</strong> {{ $settings->working_days_per_week }} أيام</small>
                        </div>
                        <div class="col-md-12">
                            <small><strong>الإجازة الافتراضية:</strong> {{ $settings->default_day_off_name }}</small>
                        </div>
                    </div>
                </div>

                <!-- Day Off Form -->
                <div class="card bg-dark border-light">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h6 class="mb-0">⚙️ اختر يوم الإجازة الأسبوعية</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user-day-offs.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Days Grid -->
                                <div class="col-12">
                                    <label class="form-label mb-3">📅 اختر يوم واحد للإجازة الأسبوعية</label>
                                    <div class="row g-2">
                                        @foreach ($days as $key => $label)
                                            <div class="col-6 col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="day_of_week"
                                                        id="day_{{ $key }}" value="{{ $key }}"
                                                        @checked($userDayOffs && in_array($key, $userDayOffs)) required>
                                                    <label class="form-check-label" for="day_{{ $key }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('day_of_week')
                                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> حفظ يوم الإجازة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card bg-dark border-warning mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-2">⚠️ ملاحظات مهمة</h6>
                        <ul class="mb-0 small text-muted">
                            <li>يمكن اختيار يوم واحد فقط للإجازة الأسبوعية</li>
                            <li>سيتم استخدام هذا اليوم لحساب الحضور والغياب</li>
                            <li>إذا لم تختر يوم، سيتم استخدام الإجازة الافتراضية للشركة</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
