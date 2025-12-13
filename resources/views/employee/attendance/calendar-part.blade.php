@php
    use Carbon\Carbon;

    $start = $currentMonth->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
    $end   = $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
    $day   = $start->copy();
@endphp

<div class="calendar-grid">

    {{-- رؤوس الأيام --}}
    <div class="calendar-header">الأحد</div>
    <div class="calendar-header">الاثنين</div>
    <div class="calendar-header">الثلاثاء</div>
    <div class="calendar-header">الأربعاء</div>
    <div class="calendar-header">الخميس</div>
    <div class="calendar-header">الجمعة</div>
    <div class="calendar-header">السبت</div>

    {{-- الأيام --}}
    @while($day <= $end)

        @php
            $key = $day->toDateString();
            $info = $dailyHours[$key] ?? [
                'total' => 0,
                'isCurrentMonth' => false,
                'hasAttendance' => false,
            ];
        @endphp

        <div class="calendar-day
            {{ !$info['isCurrentMonth'] ? 'outside' : '' }}
            {{ $info['hasAttendance'] ? 'has' : '' }}
        ">

            <div class="day-number">
                {{ $day->day }}
            </div>

            <div class="day-hours">
                {{ $info['hasAttendance'] ? number_format($info['total'], 1).' س' : '—' }}
            </div>

        </div>

        @php $day->addDay(); @endphp
    @endwhile

</div>