@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>تفاصيل الحالة: {{ $taskStatus->display_name }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>الاسم:</strong> {{ $taskStatus->name }}</p>
                        <p><strong>الاسم المعروض:</strong> {{ $taskStatus->display_name }}</p>
                        <p><strong>اللون:</strong> <span class="badge"
                                style="background-color: {{ $taskStatus->color_code }}">{{ $taskStatus->color_code }}</span>
                        </p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $taskStatus->created_at->format('Y-m-d H:i:s') }}</p>
                        <p><strong>تاريخ التحديث:</strong> {{ $taskStatus->updated_at->format('Y-m-d H:i:s') }}</p>

                        <a href="{{ route('admin.task-statuses.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
                        <a href="{{ route('admin.task-statuses.edit', $taskStatus) }}" class="btn btn-warning">تعديل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
