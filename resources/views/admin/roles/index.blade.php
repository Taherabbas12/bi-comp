@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">👤 إدارة الأدوار</h1>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> إضافة دور جديد
                    </a>
                </div>
                <p class="text-muted mb-4">عرض وتعديل الأدوار وصلاحياتها</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row g-3"> <!-- 💥 استخدم Grid Bootstrap للكروت -->
                    @forelse($roles as $role)
                        <div class="col-12 col-lg-6 col-xl-4"> <!-- 💥 كل كرت يأخذ عمودًا حسب حجم الشاشة -->
                            <div class="card text-white bg-dark border-light h-100"> <!-- 💥 استخدم Bootstrap Card -->
                                <div class="card-body">
                                    <h5 class="card-title">{{ $role->name }}</h5>
                                    <p class="card-text">
                                        <i class="bi bi-shield-lock"></i>
                                        <strong>الصلاحيات:</strong>
                                    </p>
                                    <div class="mb-2">
                                        @forelse($role->permissions as $permission)
                                            <span class="badge bg-secondary mb-1">{{ $permission->name }}</span>
                                        @empty
                                            <span class="text-muted">لا توجد صلاحيات</span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-light">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                <i class="bi bi-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-person-bounding-box" style="font-size: 3rem; color: #adb5bd;"></i>
                                <p class="mt-3">لا توجد أدوار.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection