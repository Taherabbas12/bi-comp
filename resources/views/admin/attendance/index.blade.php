@extends('admin.layouts.app')
@section('content')
    <style>
        :root {
            --bg: #020617;
            --card: #0f172a;
            --border: #334155;
            --green: #22c55e;
            --red: #ef4444;
            --text: #e5e7eb;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
        }

        .day-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            min-height: 120px;
            transition: .25s;
        }

        .day-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .4);
        }

        .day-number {
            font-size: 1.4rem;
            font-weight: 900;
        }

        .badge-box {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .badge {
            font-size: .8rem;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .present {
            background: rgba(34, 197, 94, .15);
            color: var(--green);
        }

        .absent {
            background: rgba(239, 68, 68, .15);
            color: var(--red);
        }

        .attendance-table {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .attendance-table thead {
            background: rgba(51, 65, 85, 0.3);
            font-weight: 600;
            text-align: right;
        }

        .attendance-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: 0.2s;
        }

        .attendance-table tbody tr:hover {
            background: rgba(51, 65, 85, 0.2);
        }

        .attendance-table td {
            padding: 1rem;
            color: var(--text);
        }

        .btn-edit {
            padding: 4px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        .report-card {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .report-stat {
            font-size: 2rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: 0.5rem;
        }

        .report-label {
            font-size: 0.95rem;
            color: rgba(229, 231, 235, 0.7);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .weekly-card {
            transition: all 0.3s ease;
        }

        .weekly-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(34, 197, 94, 0.2);
        }

        @media(max-width:768px) {
            .calendar-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div class="container-fluid">

        <h2 class="mb-3">📅 إدارة الحضور</h2>

        {{-- Filters --}}
        <form method="GET" class="card p-3 mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">الموظف</label>
                    <select name="user_id" class="form-select" onchange="this.form.submit()">
                        <option value="">كل الموظفين</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label">الشهر</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control"
                        onchange="this.form.submit()">
                </div>
            </div>
        </form>

        {{-- ===================== --}}
        {{-- CASE 1: All employees --}}
        {{-- ===================== --}}
        @if (!$userId)
            <div class="calendar-grid mb-2 fw-bold text-center">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>

            <div class="calendar-grid">
                @for ($i = 0; $i < $start->dayOfWeek; $i++)
                    <div></div>
                @endfor

                @for ($date = $start->copy(); $date <= $end; $date->addDay())
                    @php
                        $present = $attendanceByDay[$date->toDateString()] ?? 0;
                        $absent = $usersCount - $present;
                    @endphp

                    <div class="day-card">
                        <div class="day-number">{{ $date->day }}</div>
                        <div class="badge-box">
                            <span class="badge present">✔ {{ $present }} حضور</span>
                            <span class="badge absent">❌ {{ $absent }} غياب</span>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- ===================== --}}
            {{-- CASE 2: Single employee --}}
            {{-- ===================== --}}
        @else
            @php
                $records = \App\Models\Attendance::where('user_id', $userId)
                    ->whereBetween('work_date', [$start, $end])
                    ->orderBy('work_date', 'desc')
                    ->get();

                $totalMinutes = $records->sum(fn($r) => $r->session_minutes);
                $totalHours = intdiv($totalMinutes, 60);
                $remainingMinutes = $totalMinutes % 60;

                $daysPresent = $records->count();
                $user = $users->firstWhere('id', $userId);
            @endphp

            <!-- Report Card -->
            <div class="stats-grid">
                <div class="report-card">
                    <div class="report-stat">{{ $daysPresent }}</div>
                    <div class="report-label">أيام الحضور</div>
                </div>
                <div class="report-card">
                    <div class="report-stat">
                        @if ($totalHours > 0 && $remainingMinutes > 0)
                            {{ $totalHours }}h {{ $remainingMinutes }}m
                        @elseif ($totalHours > 0)
                            {{ $totalHours }}h
                        @else
                            {{ $remainingMinutes }}m
                        @endif
                    </div>
                    <div class="report-label">إجمالي ساعات الحضور</div>
                </div>
            </div>

            <!-- Weekly Statistics -->
            @if (!empty($weeklyStats))
                <div class="mb-4">
                    <h5 class="mb-3">📊 إحصائيات الحضور الأسبوعية</h5>
                    <div class="row">
                        @foreach ($weeklyStats as $week)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-dark text-light border-light h-100 weekly-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title mb-0">الأسبوع {{ $week['week_number'] }}</h6>
                                            @if ($week['days'] > 0)
                                                <span class="badge bg-success">✔ حاضر</span>
                                            @else
                                                <span class="badge bg-danger">❌ غائب</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted mb-3">
                                            {{ $week['start'] }} إلى {{ $week['end'] }}
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h5 text-success mb-1">{{ $week['days'] }}</div>
                                                    <div class="small">أيام الحضور</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h5 text-info mb-1">
                                                        @if ($week['hours'] > 0 && $week['remaining_minutes'] > 0)
                                                            {{ $week['hours'] }}h<br><small>{{ $week['remaining_minutes'] }}m</small>
                                                        @elseif ($week['hours'] > 0)
                                                            {{ $week['hours'] }}h
                                                        @else
                                                            {{ $week['remaining_minutes'] }}m
                                                        @endif
                                                    </div>
                                                    <div class="small">ساعات الحضور</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card bg-dark text-light border-light mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>👤 {{ $user?->name }}</strong>
                    <span>
                        @if ($totalHours > 0 && $remainingMinutes > 0)
                            ⏱️ المجموع: {{ $totalHours }}h {{ $remainingMinutes }}m
                        @elseif ($totalHours > 0)
                            ⏱️ المجموع: {{ $totalHours }}h
                        @else
                            ⏱️ المجموع: {{ $remainingMinutes }}m
                        @endif
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 attendance-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الدخول</th>
                                <th>الخروج</th>
                                <th>المدة</th>
                                <th>الحالة</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $r)
                                <tr>
                                    <td>{{ $r->work_date->format('Y-m-d') }}</td>
                                    <td>{{ optional($r->check_in_at)->format('H:i') ?? '—' }}</td>
                                    <td>{{ optional($r->check_out_at)->format('H:i') ?? '—' }}</td>
                                    <td>{{ $r->formatted_duration }}</td>
                                    <td><span class="badge bg-success">حاضر</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-edit"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            onclick="prepareEdit('{{ $r->id }}', '{{ $r->work_date->format('Y-m-d') }}', '{{ optional($r->check_in_at)->format('Y-m-d H:i') }}', '{{ optional($r->check_out_at)->format('Y-m-d H:i') }}')">
                                            ✏️ تعديل
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        لا توجد سجلات حضور لهذا الموظف
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light border-light">
                <div class="modal-header border-light">
                    <h5 class="modal-title" id="editModalLabel">تعديل سجل الحضور</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="work_date" class="form-label">التاريخ</label>
                            <input type="date" class="form-control bg-dark text-light border-light" id="work_date"
                                name="work_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="check_in_at" class="form-label">وقت الدخول</label>
                            <input type="datetime-local" class="form-control bg-dark text-light border-light"
                                id="check_in_at" name="check_in_at">
                        </div>
                        <div class="mb-3">
                            <label for="check_out_at" class="form-label">وقت الخروج</label>
                            <input type="datetime-local" class="form-control bg-dark text-light border-light"
                                id="check_out_at" name="check_out_at">
                        </div>
                    </div>
                    <div class="modal-footer border-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function prepareEdit(id, workDate, checkInAt, checkOutAt) {
            const form = document.getElementById('editForm');
            form.action = `/admin/attendance/${id}`;

            document.getElementById('work_date').value = workDate;

            // Convert to datetime-local format
            if (checkInAt) {
                const inDate = new Date(checkInAt);
                document.getElementById('check_in_at').value = checkInAt.replace(' ', 'T');
            }

            if (checkOutAt) {
                const outDate = new Date(checkOutAt);
                document.getElementById('check_out_at').value = checkOutAt.replace(' ', 'T');
            }
        }
    </script>
@endsection
