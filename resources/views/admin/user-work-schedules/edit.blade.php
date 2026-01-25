@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 mb-1">⏰ جدول العمل الأسبوعي</h1>
                        <small class="text-muted">{{ $user->name }}</small>
                    </div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary">
                        ← رجوع
                    </a>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>حدثت أخطاء في التحقق من البيانات:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Work Schedule Form -->
                <div class="card bg-dark border-light">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h6 class="mb-0">📅 أوقات العمل لكل يوم</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user-work-schedules.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                @foreach ($schedules as $index => $schedule)
                                    <div class="col-12 col-lg-6 border-bottom border-secondary pb-4">
                                        <div class="card bg-dark border-secondary">
                                            <div class="card-header bg-dark">
                                                <div class="form-check">
                                                    <input class="form-check-input day-off-toggle" type="checkbox"
                                                        name="schedules[{{ $index }}][is_day_off]" value="1"
                                                        id="day_off_{{ $schedule->day_of_week }}"
                                                        @checked($schedule->is_day_off)
                                                        data-day="{{ $schedule->day_of_week }}">
                                                    <label class="form-check-label"
                                                        for="day_off_{{ $schedule->day_of_week }}">
                                                        <strong>{{ $schedule->day_name }}</strong>
                                                        <span class="badge bg-danger ms-2"
                                                            style="display: {{ $schedule->is_day_off ? 'inline-block' : 'none' }}"
                                                            id="badge_{{ $schedule->day_of_week }}">عطلة</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body work-schedule-inputs"
                                                id="schedule_{{ $schedule->day_of_week }}"
                                                style="display: {{ $schedule->is_day_off ? 'none' : 'block' }}">
                                                <input type="hidden" name="schedules[{{ $index }}][day_of_week]"
                                                    value="{{ $schedule->day_of_week }}">

                                                <div class="row g-2">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">🕐 وقت الدخول</label>
                                                        <input type="time"
                                                            name="schedules[{{ $index }}][check_in]"
                                                            class="form-control"
                                                            value="{{ $schedule->check_in ? $schedule->check_in : '09:00' }}">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">🕓 وقت الخروج</label>
                                                        <input type="time"
                                                            name="schedules[{{ $index }}][check_out]"
                                                            class="form-control"
                                                            value="{{ $schedule->check_out ? $schedule->check_out : '17:00' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Submit -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> حفظ جدول العمل
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
                            <li>حدد الأيام التي يعمل فيها الموظف وأوقات العمل</li>
                            <li>علّم خانة "عطلة" للأيام التي لا يعمل فيها</li>
                            <li>يتم حفظ البيانات تلقائياً عند الضغط على "حفظ"</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.day-off-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const day = this.dataset.day;
                const scheduleInputs = document.getElementById('schedule_' + day);
                const badge = document.getElementById('badge_' + day);

                if (this.checked) {
                    scheduleInputs.style.display = 'none';
                    badge.style.display = 'inline-block';
                } else {
                    scheduleInputs.style.display = 'block';
                    badge.style.display = 'none';
                }
            });
        });
    </script>
@endsection
