@extends('layouts.employee-layout')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">مهامي</h2>
                <p class="mb-0 text-light small">عرض وإدارة المهام المسندة إليك داخل النظام</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary me-1">إجمالي: {{ $totalTasks }}</span>
                <span class="badge bg-success me-1">مكتملة: {{ $completedTasks }}</span>
                <span class="badge bg-warning text-dark">قيد التنفيذ: {{ $inProgressTasks }}</span>
            </div>
        </div>

        {{-- فلاتر البحث --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('employee.tasks.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">الكل</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ request('status') == $status->id ? 'selected' : '' }}>
                                        {{ $status->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">الأولوية</label>
                            <select name="priority" class="form-select">
                                <option value="">الكل</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}"
                                        {{ request('priority') == $priority->id ? 'selected' : '' }}>
                                        {{ $priority->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">بحث</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="عنوان أو وصف"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-outline-light">
                                <i class="bi bi-funnel"></i> ترشيح
                            </button>
                            <a href="{{ route('employee.tasks.index') }}" class="btn btn-outline-secondary">
                                مسح الفلاتر
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- رسائل نجاح/أخطاء --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- قائمة المهام على شكل كروت --}}
        <div class="row g-3">
            @forelse ($tasks as $task)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm task-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $task->title }}</h5>
                                @if ($task->priority)
                                    <span class="badge"
                                        style="background-color: {{ $task->priority->color_code }};">
                                        {{ $task->priority->display_name }}
                                    </span>
                                @endif
                            </div>

                            @if ($task->status)
                                <p class="mb-2">
                                    <span class="badge bg-dark">
                                        الحالة: {{ $task->status->display_name }}
                                    </span>
                                </p>
                            @endif

                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($task->description, 120) }}
                            </p>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between small">
                                    <span>نسبة الإنجاز</span>
                                    <span>{{ $task->progress_percentage ?? 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        style="width: {{ $task->progress_percentage ?? 0 }}%;
                                               background-color: {{ optional($task->priority)->color_code ?? '#3498db' }};"
                                        aria-valuenow="{{ $task->progress_percentage ?? 0 }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 small text-muted">
                                <div>
                                    <i class="bi bi-calendar-event"></i>
                                    تاريخ الاستحقاق:
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                                </div>
                                @if ($task->supervisor)
                                    <div>
                                        <i class="bi bi-person-badge"></i>
                                        المشرف: {{ $task->supervisor->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <a href="{{ route('employee.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> تفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        لا توجد مهام مسندة إليك حاليًا.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .task-card {
            border-radius: 18px;
        }

        .task-card .card-title {
            font-weight: 700;
        }
    </style>
@endsection
