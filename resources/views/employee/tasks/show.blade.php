@extends('layouts.employee-layout')


@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('employee.tasks.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-right"></i> الرجوع إلى مهامي
            </a>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $task->title }}</h4>
                            <small class="text-muted">رقم المهمة: {{ $task->id }}</small>
                        </div>
                        @if ($task->priority)
                            <span class="badge" style="background-color: {{ $task->priority->color_code }};">
                                {{ $task->priority->display_name }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">وصف المهمة</h6>
                        <p class="text-muted">
                            {{ $task->description ?: 'لا يوجد وصف للمهمة.' }}
                        </p>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>تاريخ البدء:</strong><br>
                                {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : 'غير محدد' }}
                            </div>
                            <div class="col-md-4">
                                <strong>تاريخ الاستحقاق:</strong><br>
                                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                            </div>
                            <div class="col-md-4">
                                <strong>الحالة:</strong><br>
                                {{ optional($task->status)->display_name ?? 'غير محددة' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>أنشئت بواسطة:</strong><br>
                                {{ optional($task->createdBy)->name ?? 'غير معروف' }}
                            </div>
                            <div class="col-md-4">
                                <strong>المسؤول المباشر:</strong><br>
                                {{ optional($task->supervisor)->name ?? 'غير محدد' }}
                            </div>
                            <div class="col-md-4">
                                <strong>المسندة إلى:</strong><br>
                                {{ optional($task->assignedTo)->name ?? 'غير محدد' }}
                            </div>
                        </div>

                        <hr>

                        {{-- نسبة الإنجاز --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>نسبة الإنجاز الحالية</strong>
                                <span>{{ $task->progress_percentage ?? 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $task->progress_percentage ?? 0 }}%;
                                               background-color: {{ optional($task->priority)->color_code ?? '#3498db' }};"
                                    aria-valuenow="{{ $task->progress_percentage ?? 0 }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        {{-- فورم تحديث نسبة الإنجاز --}}
                        <form method="POST" action="{{ route('employee.tasks.updateProgress', $task) }}"
                            class="row g-2 align-items-center mt-3">
                            @csrf
                            @method('PUT')

                            <div class="col-auto">
                                <label for="progress_percentage" class="col-form-label">تحديث النسبة:</label>
                            </div>
                            <div class="col-4 col-md-3">
                                <input type="number" name="progress_percentage" id="progress_percentage"
                                    class="form-control @error('progress_percentage') is-invalid @enderror" min="0"
                                    max="100" value="{{ old('progress_percentage', $task->progress_percentage ?? 0) }}">
                                @error('progress_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-auto">
                                <span class="fw-bold">%</span>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> حفظ التغيير
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- صندوق معلومات جانبي --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">معلومات سريعة</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="bi bi-flag"></i>
                                مستوى الأولوية:
                                <strong>{{ optional($task->priority)->display_name ?? 'غير محدد' }}</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-calendar-check"></i>
                                آخر تحديث:
                                <strong>{{ $task->updated_at?->format('Y-m-d H:i') }}</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-history"></i>
                                تاريخ الإنشاء:
                                <strong>{{ $task->created_at?->format('Y-m-d H:i') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- مساحة لتطوير لاحقًا (ملاحظات / ملفات مرفقة...) --}}
                <div class="card shadow-sm">
                    <div class="card-body text-muted small">
                        يمكن في المستقبل إضافة:
                        <ul class="mb-0">
                            <li>ملاحظات الموظف على المهمة</li>
                            <li>رفع ملفات مرفقة</li>
                            <li>سجل نشاط للمهمة</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
