@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>➕ إضافة مستخدم جديد</h1>
        <p class="subtitle">املأ البيانات التالية</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        required>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">الدور</label>
                    <select class="form-control" id="role_id" name="role_id">
                        <option value="">لا تعيين دور</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name ?? $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>

    <!-- CSS مخصص لحل مشكلة لون النص في القائمة المنسدلة -->
    <style>
        /* تحديد أكثر دقة لتجاوز أنماط Bootstrap */
        .card-body select.form-control option {
            color: #212529 !important;
            /* لون نص داكن */
            background-color: white !important;
            /* لون خلفية أبيض */
        }
    </style>
@endsection
