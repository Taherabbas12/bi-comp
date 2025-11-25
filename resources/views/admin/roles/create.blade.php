@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>➕ إضافة دور جديد</h1>
        <p class="subtitle">املأ البيانات التالية</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الدور</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="permissions" class="form-label">الصلاحيات</label>
                    <select multiple class="form-control" id="permissions" name="permissions[]">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">إضافة الدور</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
