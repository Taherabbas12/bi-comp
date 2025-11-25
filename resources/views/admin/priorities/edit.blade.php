@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>تعديل الأولوية: {{ $priority->display_name }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.priorities.update', $priority) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم (مفتاح فريد)</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $priority->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="display_name" class="form-label">الاسم المعروض</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                    id="display_name" name="display_name"
                                    value="{{ old('display_name', $priority->display_name) }}" required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="color_code" class="form-label">لون العرض</label>
                                <input type="color"
                                    class="form-control form-control-color @error('color_code') is-invalid @enderror"
                                    id="color_code" name="color_code"
                                    value="{{ old('color_code', $priority->color_code) }}">
                                @error('color_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">تحديث</button>
                            <a href="{{ route('admin.priorities.index') }}" class="btn btn-secondary">إلغاء</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
