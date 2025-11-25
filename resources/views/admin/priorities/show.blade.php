@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>تفاصيل الأولوية: {{ $priority->display_name }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>الاسم:</strong> {{ $priority->name }}</p>
                        <p><strong>الاسم المعروض:</strong> {{ $priority->display_name }}</p>
                        <p><strong>اللون:</strong> <span class="badge"
                                style="background-color: {{ $priority->color_code }}">{{ $priority->color_code }}</span></p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $priority->created_at->format('Y-m-d H:i:s') }}</p>
                        <p><strong>تاريخ التحديث:</strong> {{ $priority->updated_at->format('Y-m-d H:i:s') }}</p>

                        <a href="{{ route('admin.priorities.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
                        <a href="{{ route('admin.priorities.edit', $priority) }}" class="btn btn-warning">تعديل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
