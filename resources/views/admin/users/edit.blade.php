@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h1 class="h4 mb-0">✏️ تعديل المستخدم</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>الاسم</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label>البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label>كلمة المرور (اختياري)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>تأكيد كلمة المرور</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <hr class="border-secondary my-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>📞 الهاتف</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label>✈️ التلكرام</label>
                                    <input type="text" name="telegram_id" class="form-control"
                                        value="{{ $user->telegram_id }}">
                                </div>
                                <div class="col-md-6">
                                    <label>🎂 تاريخ الميلاد</label>
                                    <input type="date" name="birth_date" class="form-control"
                                        value="{{ optional($user->birth_date)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label>🚻 الجنس</label>
                                    <select name="gender" class="form-control">
                                        <option value="">—</option>
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>🆔 الرقم الوطني</label>
                                    <input type="text" name="national_id" class="form-control"
                                        value="{{ $user->national_id }}">
                                </div>
                                <div class="col-md-6">
                                    <label>📍 العنوان</label>
                                    <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                                </div>
                                <div class="col-12">
                                    <label>📝 ملاحظات</label>
                                    <textarea name="notes" class="form-control">{{ $user->notes }}</textarea>
                                </div>
                            </div>

                            <hr class="border-secondary my-4">

                            <div class="mb-3">
                                <label>الدور</label>
                                <select name="role_id" class="form-control">
                                    <option value="">لا تعيين</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary">تحديث</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
