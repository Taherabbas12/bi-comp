@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    
    <h1 class="mb-4 text-center">إدارة الحضور</h1>

    {{-- فلاتر البحث --}}
    <form method="GET" class="card p-3 mb-4">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label">الموظف</label>
                <select name="user_id" class="form-select">
                    <option value="">كل الموظفين</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-12 col-md-2">
                <button class="btn btn-primary w-100 mt-2 mt-md-0">
                    <i class="bi bi-search"></i> بحث
                </button>
            </div>
        </div>
    </form>

    {{-- عرض كروت الحضور --}}
    @if($records->count() > 0)
        <div class="row g-3">
            @foreach($records as $r)
                {{-- 💥 تم تعديل col-12 إلى col-lg-6 col-xl-4 لجعل العرض متجاوبًا --}}
                <div class="col-12 col-lg-6 col-xl-4">
                    <div class="card text-white bg-dark border-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">{{ $r->user->name }}</h5>
                                    <p class="card-text">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>التاريخ:</strong> {{ $r->work_date }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.attendance.user', $r->user->id) }}" class="btn btn-outline-light btn-sm">
                                    عرض التفاصيل
                                </a>
                            </div>

                            <div class="row mt-2">
                                <div class="col-6">
                                    <p class="card-text">
                                        <i class="bi bi-box-arrow-in-right text-success"></i>
                                        <strong>الدخول:</strong> {{ $r->check_in_at ? $r->check_in_at->format('h:i A') : '—' }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="card-text">
                                        <i class="bi bi-box-arrow-right text-danger"></i>
                                        <strong>الخروج:</strong> {{ $r->check_out_at ? $r->check_out_at->format('h:i A') : '—' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-2">
                                <p class="card-text">
                                    <i class="bi bi-clock-history text-info"></i>
                                    <strong>المدة:</strong>
                                    @if($r->check_in_at && $r->check_out_at)
                                        @php
                                            $interval = $r->check_in_at->diff($r->check_out_at);
                                            $totalMinutes = $r->check_in_at->diffInMinutes($r->check_out_at);
                                            $totalHours = $totalMinutes / 60;
                                        @endphp
                                        @if($totalMinutes < 60)
                                            {{ $interval->format('%i') }} دقيقة
                                        @elseif($totalMinutes < 3600) {{-- أقل من 60 ساعة --}}
                                            {{ $interval->format('%h ساعة و %i دقيقة') }}
                                        @else
                                            {{ number_format($totalHours, 2) }} ساعة
                                        @endif
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="صفحات السجلات">
                <ul class="pagination pagination-sm mb-0">
                    @if ($records->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link bg-dark text-light border-secondary">السابق</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link bg-dark text-light border-secondary" href="{{ $records->previousPageUrl() }}" rel="prev">السابق</a>
                        </li>
                    @endif

                    @php
                        $currentPage = $records->currentPage();
                        $lastPage = $records->lastPage();
                        $onEachSide = 1;
                    @endphp

                    @if ($currentPage > $onEachSide + 1)
                        <li class="page-item disabled"><span class="page-link bg-dark text-light border-secondary">...</span></li>
                    @endif

                    @for ($page = max(1, $currentPage - $onEachSide); $page <= min($lastPage, $currentPage + $onEachSide); $page++)
                        @if ($page == $currentPage)
                            <li class="page-item active" aria-current="page">
                                <span class="page-link bg-primary border-primary">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link bg-dark text-light border-secondary" href="{{ $records->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor

                    @if ($currentPage < $lastPage - $onEachSide)
                        <li class="page-item disabled"><span class="page-link bg-dark text-light border-secondary">...</span></li>
                    @endif

                    @if ($records->hasMorePages())
                        <li class="page-item">
                            <a class="page-link bg-dark text-light border-secondary" href="{{ $records->nextPageUrl() }}" rel="next">التالي</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link bg-dark text-light border-secondary">التالي</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #adb5bd;"></i>
            <p class="mt-3">لا توجد سجلات حضور مطابقة لبحثك.</p>
        </div>
    @endif

</div>

@endsection