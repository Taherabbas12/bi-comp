@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1">👥 إدارة المستخدمين</h1>
                <p class="text-muted mb-0">عرض وتعديل المستخدمين</p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> إضافة مستخدم
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3">
            @forelse($users as $user)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card text-white bg-dark border-light h-100 shadow-sm">
                        <div class="card-body d-flex flex-column gap-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                @if ($user->role)
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-secondary">بدون دور</span>
                                @endif
                            </div>

                            <small class="text-muted">
                                <i class="bi bi-envelope"></i> {{ $user->email }}
                            </small>

                            <small>
                                <i class="bi bi-telephone"></i>
                                {{ $user->phone ?? '—' }}
                            </small>

                            <div class="mt-auto pt-3 d-flex justify-content-between gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info w-100">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('هل أنت متأكد؟')"
                                        class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-people fs-1"></i>
                    <p class="mt-2">لا يوجد مستخدمين</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
