@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 mb-1">🕐 أوقات العمل الرسمية</h1>
                        <small class="text-muted">{{ $user->name }}</small>
                    </div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary">
                        ← رجوع
                    </a>
                </div>

                <!-- Info Card -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i>
                    حدد أوقات الدخول والخروج الرسمية لكل يوم. اختر "عطلة" للأيام التي لا يعمل فيها الموظف.
                </div>

                <!-- Schedules Form -->
                <form action="{{ route('work-schedules.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        @forelse($workSchedules as $schedule)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card bg-dark border-light h-100">
                                    <div class="card-body">
                                        <!-- Day Name -->
                                        <h6 class="card-title text-warning mb-3">
                                            📅 {{ $schedule->day_name }}
                                        </h6>

                                        <!-- Day Off Checkbox -->
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input day-off-checkbox"
                                                id="day_off_{{ $schedule->day_of_week }}"
                                                name="schedules[{{ $schedule->id }}][is_day_off]" value="1"
                                                {{ $schedule->is_day_off ? 'checked' : '' }}
                                                data-schedule-id="{{ $schedule->id }}">
                                            <label class="form-check-label" for="day_off_{{ $schedule->day_of_week }}">
                                                يوم عطلة
                                            </label>
                                        </div>

                                        <!-- Time Inputs (Hidden when day off) -->
                                        <div class="schedule-times" id="times_{{ $schedule->id }}"
                                            style="display: {{ $schedule->is_day_off ? 'none' : 'block' }}">

                                            <!-- Check In Time -->
                                            <div class="mb-3">
                                                <label class="form-label form-label-sm">⏰ وقت الدخول</label>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="schedules[{{ $schedule->id }}][official_check_in]"
                                                    value="{{ $schedule->official_check_in ?? '08:00' }}" required>
                                            </div>

                                            <!-- Check Out Time -->
                                            <div class="mb-3">
                                                <label class="form-label form-label-sm">⏰ وقت الخروج</label>
                                                <input type="time" class="form-control form-control-sm"
                                                    name="schedules[{{ $schedule->id }}][official_check_out]"
                                                    value="{{ $schedule->official_check_out ?? '17:00' }}" required>
                                            </div>

                                            <!-- Working Hours (Auto-calculated) -->
                                            <div class="mb-3">
                                                <label class="form-label form-label-sm">⏱️ ساعات العمل</label>
                                                <input type="number" class="form-control form-control-sm working-hours"
                                                    name="schedules[{{ $schedule->id }}][working_hours]" step="0.5"
                                                    value="{{ $schedule->working_hours ?? '8' }}" readonly disabled>
                                                <small class="text-muted">يتم الحساب تلقائياً</small>
                                            </div>
                                        </div>

                                        <!-- Day Off Message -->
                                        <div class="alert alert-warning alert-sm mb-0 py-2"
                                            id="dayoff_msg_{{ $schedule->id }}"
                                            style="display: {{ $schedule->is_day_off ? 'block' : 'none' }}">
                                            <i class="bi bi-x-circle"></i> يوم عطلة
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class="bi bi-calendar3 fs-1"></i>
                                <p class="mt-2">لا توجد جداول عمل محفوظة</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> حفظ الأوقات
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> إعادة تعيين
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        .form-label-sm {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .form-control-sm {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }

        .alert-sm {
            font-size: 0.85rem;
            margin: 0;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: #0dcaf0 !important;
            box-shadow: 0 0 10px rgba(13, 202, 240, 0.3);
        }
    </style>

    <script>
        document.querySelectorAll('.day-off-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const scheduleId = this.dataset.scheduleId;
                const timesDiv = document.getElementById('times_' + scheduleId);
                const msgDiv = document.getElementById('dayoff_msg_' + scheduleId);
                const inputs = timesDiv.querySelectorAll('input[type="time"]');

                if (this.checked) {
                    timesDiv.style.display = 'none';
                    msgDiv.style.display = 'block';
                    inputs.forEach(input => input.removeAttribute('required'));
                } else {
                    timesDiv.style.display = 'block';
                    msgDiv.style.display = 'none';
                    inputs.forEach(input => input.setAttribute('required', 'required'));
                }
            });

            // Calculate working hours
            const scheduleId = checkbox.dataset.scheduleId;
            const checkInInput = document.querySelector(
                `input[name="schedules[${scheduleId}][official_check_in]"]`
            );
            const checkOutInput = document.querySelector(
                `input[name="schedules[${scheduleId}][official_check_out]"]`
            );
            const workingHoursInput = document.querySelector(
                `input.working-hours[name="schedules[${scheduleId}][working_hours]"]`
            );

            if (checkInInput && checkOutInput) {
                const calculateHours = () => {
                    if (checkInInput.value && checkOutInput.value) {
                        const [inHour, inMin] = checkInInput.value.split(':').map(Number);
                        const [outHour, outMin] = checkOutInput.value.split(':').map(Number);

                        const inMinutes = inHour * 60 + inMin;
                        const outMinutes = outHour * 60 + outMin;
                        const diffMinutes = outMinutes - inMinutes;
                        const hours = (diffMinutes / 60).toFixed(2);

                        if (workingHoursInput) {
                            workingHoursInput.value = hours;
                        }
                    }
                };

                checkInInput.addEventListener('change', calculateHours);
                checkOutInput.addEventListener('change', calculateHours);
            }
        });
    </script>
@endsection
