@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">

        <h3 class="mb-4">
            📅 تفاصيل الحضور ليوم
            <span class="text-info">{{ $day->format('Y-m-d') }}</span>
        </h3>

        @if ($records->count())
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>الدخول</th>
                            <th>الخروج</th>
                            <th>المدة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $r)
                            <tr>
                                <td>{{ $r->user->name }}</td>
                                <td>{{ optional($r->check_in_at)->format('H:i') ?? '—' }}</td>
                                <td>{{ optional($r->check_out_at)->format('H:i') ?? '—' }}</td>
                                <td>{{ $r->session_minutes }} دقيقة</td>
                                <td>
                                    <span class="badge bg-success">حاضر</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">لا توجد سجلات لهذا اليوم</p>
        @endif

    </div>
@endsection
