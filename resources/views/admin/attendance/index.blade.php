@extends('admin.layouts.app')

@section('content')
    <style>
        /* ====== THEME ====== */
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
            position: relative;
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

        /* Mobile */
        @media(max-width:768px) {
            .calendar-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📅 رزنامة الحضور الشهرية</h2>

            <form method="GET">
                <input type="month" name="month" value="{{ $month }}" class="form-control">
            </form>
        </div>

        {{-- أسماء الأيام --}}
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

                    <a href="{{ route('admin.attendance.day', $date->toDateString()) }}"
                        class="btn btn-sm btn-outline-light w-100 mt-3">
                        عرض التفاصيل
                    </a>
                </div>
            @endfor

        </div>
    </div>
@endsection
