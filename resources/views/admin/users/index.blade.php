@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">👥 إدارة المستخدمين</h1>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> إضافة مستخدم جديد
                    </a>
                </div>
                <p class="text-muted mb-4">عرض وتعديل المستخدمين</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row g-3"> <!-- 💥 استخدم Grid Bootstrap للكروت -->
                    @forelse($users as $user)
                        <div class="col-12 col-lg-6 col-xl-4"> <!-- 💥 كل كرت يأخذ عمودًا حسب حجم الشاشة -->
                            <div class="card text-white bg-dark border-light h-100"> <!-- 💥 استخدم Bootstrap Card -->
                                <div class="card-body">
                                    <h5 class="card-title">{{ $user->name }}</h5>
                                    <p class="card-text">
                                        <i class="bi bi-envelope"></i>
                                        <strong>البريد الإلكتروني:</strong> {{ $user->email }}
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-person-badge"></i>
                                        <strong>الدور:</strong>
                                        @if ($user->role)
                                            <span class="badge bg-primary">{{ $user->role->name }}</span>
                                        @else
                                            <span class="text-muted">غير معين</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top-light">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i> عرض
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
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
                                <i class="bi bi-people" style="font-size: 3rem; color: #adb5bd;"></i>
                                <p class="mt-3">لا توجد مستخدمين.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection