@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 mb-1">⏰ إعدادات أوقات العمل</h1>
                        <small class="text-muted">إدارة أوقات العمل الثابتة لكل الموظفين</small>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
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

                <!-- Settings Form -->
                <div class="card bg-dark border-light">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h6 class="mb-0">⚙️ إعدادات الشركة</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('work-schedule-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Check In Time -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label">🕐 وقت الدخول الرسمي</label>
                                    <input type="time" name="official_check_in" class="form-control"
                                        value="{{ $settings->official_check_in }}" required>
                                    @error('official_check_in')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Check Out Time -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label">🕓 وقت الخروج الرسمي</label>
                                    <input type="time" name="official_check_out" class="form-control"
                                        value="{{ $settings->official_check_out }}" required>
                                    @error('official_check_out')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Working Hours -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label">⏱️ ساعات العمل اليومية</label>
                                    <input type="number" name="working_hours" class="form-control" step="0.5"
                                        value="{{ $settings->working_hours }}" min="1" max="24" required>
                                    @error('working_hours')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Working Days Per Week -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label">📅 عدد أيام العمل في الأسبوع</label>
                                    <input type="number" name="working_days_per_week" class="form-control"
                                        value="{{ $settings->working_days_per_week }}" min="1" max="7"
                                        required>
                                    @error('working_days_per_week')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Default Day Off -->
                                <div class="col-12">
                                    <label class="form-label">🌙 اليوم الافتراضي للإجازة الأسبوعية</label>
                                    <select name="default_day_off" class="form-control bg-dark text-light border-secondary"
                                        required>
                                        @foreach ($days as $key => $label)
                                            <option value="{{ $key }}" @selected($key == $settings->default_day_off)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('default_day_off')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> حفظ الإعدادات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card bg-dark border-info mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-2">ℹ️ معلومات</h6>
                        <ul class="mb-0 small text-muted">
                            <li>هذه الإعدادات تنطبق على جميع الموظفين بشكل افتراضي</li>
                            <li>يمكن تغيير يوم الإجازة لكل موظف على حدة من صفحة الموظف</li>
                            <li>عدد أيام العمل يحسب الأيام التي لا تكون إجازة</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        select.form-control {
            background-color: #212529;
            color: #fff;
            border-color: #495057;
        }

        select.form-control:focus {
            background-color: #212529;
            color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        select.form-control option {
            background-color: #212529;
            color: #fff;
        }
    </style>
@endsection
