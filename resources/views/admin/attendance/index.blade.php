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

        @media(max-width:768px) {
            .calendar-grid {
                grid-template-columns: repeat(2, 1fr);
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
                    ->orderBy('work_date')
                    ->get();

                $totalMinutes = $records->sum(fn($r) => $r->session_minutes);
                $user = $users->firstWhere('id', $userId);
            @endphp

            <div class="card bg-dark text-light border-light mb-3">
                <div class="card-header d-flex justify-content-between">
                    <strong>👤 {{ $user?->name }}</strong>
                    <span>⏱️ المجموع: {{ round($totalMinutes / 60, 2) }} ساعة</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الدخول</th>
                                <th>الخروج</th>
                                <th>المدة</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $r)
                                <tr>
                                    <td>{{ $r->work_date->format('Y-m-d') }}</td>
                                    <td>{{ optional($r->check_in_at)->format('H:i') ?? '—' }}</td>
                                    <td>{{ optional($r->check_out_at)->format('H:i') ?? '—' }}</td>
                                    <td>{{ $r->session_minutes }} دقيقة</td>
                                    <td><span class="badge bg-success">حاضر</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
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
@endsection
