@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">تفاصيل المهمة: {{ $task->title }}</h4>
                        <div>
                            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary btn-sm ms-1">العودة إلى القائمة</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-card-text"></i>
                                    <strong>العنوان:</strong> {{ $task->title }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-person"></i>
                                    <strong>الموظف المعين:</strong> {{ $task->assignedTo->name ?? 'غير محدد' }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-person-bounding-box"></i>
                                    <strong>المشرف:</strong> {{ $task->supervisor->name ?? 'غير محدد' }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-person-circle"></i>
                                    <strong>من أنشأ:</strong> {{ $task->createdBy->name }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text">
                                    <i class="bi bi-clipboard-check"></i>
                                    <strong>الحالة:</strong>
                                    <span class="badge"
                                        style="background-color: {{ $task->status->color_code }}; color: white;">
                                        {{ $task->status->display_name }}
                                    </span>
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-flag"></i>
                                    <strong>الأولوية:</strong>
                                    <span class="badge"
                                        style="background-color: {{ $task->priority->color_code }}; color: white;">
                                        {{ $task->priority->display_name }}
                                    </span>
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-calendar-event"></i>
                                    <strong>تاريخ البدء:</strong>
                                    {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : 'غير محدد' }}
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-calendar-x"></i>
                                    <strong>تاريخ الانتهاء:</strong>
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <p class="card-text">
                                    <i class="bi bi-bar-chart-fill"></i>
                                    <strong>نسبة الإنجاز:</strong>
                                </p>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->priority->color_code }};"
                                        aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $task->progress_percentage }}%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <p class="card-text"><strong>التفاصيل:</strong></p>
                                <div class="card bg-secondary p-3">
                                    <p>{!! nl2br(e($task->description)) !!}</p> <!-- nl2br لتحويل السطور الجديدة إلى <br> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection