@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>تفاصيل المهمة: {{ $task->title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>العنوان:</strong> {{ $task->title }}</p>
                                <p><strong>الموظف المعين:</strong> {{ $task->assignedTo->name ?? 'غير محدد' }}</p>
                                <p><strong>المشرف:</strong> {{ $task->supervisor->name ?? 'غير محدد' }}</p>
                                <p><strong>من أنشأ:</strong> {{ $task->createdBy->name }}</p>
                                <p><strong>نسبة الإنجاز:</strong>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->priority->color_code }};"
                                        aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0"
                                        aria-valuemax="100">{{ $task->progress_percentage }}%</div>
                                </div>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>الحالة:</strong> <span class="badge"
                                        style="background-color: {{ $task->status->color_code }}">{{ $task->status->display_name }}</span>
                                </p>
                                <p><strong>الأولوية:</strong> <span class="badge"
                                        style="background-color: {{ $task->priority->color_code }}">{{ $task->priority->display_name }}</span>
                                </p>
                                <p><strong>تاريخ البدء:</strong>
                                    {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : 'غير محدد' }}
                                </p>
                                <p><strong>تاريخ الانتهاء:</strong>
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>التفاصيل:</strong></p>
                                <p>{!! nl2br(e($task->description)) !!}</p> <!-- nl2br لتحويل السطور الجديدة إلى <br> -->
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
                            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-warning">تعديل</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
