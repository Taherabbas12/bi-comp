@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4>إضافة مهمة جديدة</h4>
                    </div>
                    <style>
                        select.form-control option,
                        select.form-select option {
                            color: #212529 !important;
                            /* لون نص داكن */
                            background-color: white !important;
                            /* لون خلفية أبيض */
                        }
                    </style>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.tasks.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">العنوان *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="priority_id" class="form-label">الأولوية *</label>
                                        <select class="form-control @error('priority_id') is-invalid @enderror"
                                            id="priority_id" name="priority_id" required>
                                            <option value="">اختر أولوية...</option>
                                            @foreach ($priorities as $priority)
                                                <option value="{{ $priority->id }}"
                                                    {{ old('priority_id') == $priority->id ? 'selected' : '' }}
                                                    style="color: {{ $priority->color_code }}">{{ $priority->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('priority_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="assigned_to_user_id" class="form-label">الموظف المعين *</label>
                                        <select class="form-control @error('assigned_to_user_id') is-invalid @enderror"
                                            id="assigned_to_user_id" name="assigned_to_user_id" required>
                                            <option value="">اختر موظفًا...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('assigned_to_user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to_user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_id" class="form-label">الحالة *</label>
                                        <select class="form-control @error('status_id') is-invalid @enderror" id="status_id"
                                            name="status_id" required>
                                            <option value="">اختر حالة...</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}"
                                                    {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->display_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">تاريخ البدء</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">تاريخ الانتهاء</label>
                                        <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                            id="due_date" name="due_date" value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="supervisor_user_id" class="form-label">المشرف</label>
                                        <select class="form-control @error('supervisor_user_id') is-invalid @enderror"
                                            id="supervisor_user_id" name="supervisor_user_id">
                                            <option value="">اختر مشرفًا (اختياري)...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('supervisor_user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('supervisor_user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="progress_percentage" class="form-label">نسبة الإنجاز (%)</label>
                                        <input type="number"
                                            class="form-control @error('progress_percentage') is-invalid @enderror"
                                            id="progress_percentage" name="progress_percentage"
                                            value="{{ old('progress_percentage', 0) }}" min="0" max="100">
                                        @error('progress_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">التفاصيل</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">إضافة المهمة</button>
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">إلغاء</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
