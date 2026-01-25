@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">👥 إدارة المستخدمين</h1>
                <small class="text-muted">عرض، بحث، وتعديل الموظفين</small>
            </div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> إضافة مستخدم
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-12 col-md-8">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        placeholder="🔍 ابحث بالاسم، البريد، الهاتف، التلكرام، الرقم الوطني">
                </div>

                <div class="col-6 col-md-2">
                    <button class="btn btn-sm btn-primary w-100">
                        بحث
                    </button>
                </div>

                <div class="col-6 col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                        مسح
                    </a>
                </div>
            </div>
        </form>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success py-2">
                {{ session('success') }}
            </div>
        @endif

        <!-- Users Grid -->
        <div class="row g-3">
            @forelse($users as $user)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card text-white bg-dark border-light h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <!-- Name & Role & Position -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0 text-truncate">{{ $user->name }}</h6>
                                    @if ($user->position)
                                        <small class="text-warning d-block">{{ $user->position }}</small>
                                    @endif
                                </div>
                                @if ($user->role)
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-secondary">بدون دور</span>
                                @endif
                            </div>

                            <!-- Contact Info -->
                            <small class="text-muted text-truncate">
                                <i class="bi bi-envelope"></i> {{ $user->email }}
                            </small>

                            <small class="mt-1">
                                <i class="bi bi-telephone"></i>
                                {{ $user->phone ?? '—' }}
                            </small>

                            <!-- Employment Info -->
                            @if ($user->salary || $user->employment_type || $user->department)
                                <div class="mt-3 pt-3 border-top border-secondary">
                                    @if ($user->salary)
                                        <small class="d-block text-success fw-bold">
                                            💰 {{ number_format($user->salary, 0) }} {{ $user->salary_currency ?? 'IQD' }}
                                        </small>
                                    @endif
                                    @if ($user->employment_type)
                                        <small class="d-block text-info">
                                            <span class="badge bg-info bg-opacity-50">{{ $user->employment_type }}</span>
                                        </small>
                                    @endif
                                    @if ($user->department)
                                        <small class="d-block text-secondary">
                                            📊 {{ $user->department }}
                                        </small>
                                    @endif
                                    @if ($user->hire_date)
                                        <small class="d-block text-muted">
                                            📅 {{ \Carbon\Carbon::parse($user->hire_date)->format('Y-m-d') }}
                                        </small>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-auto pt-3 d-grid grid-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-people fs-1"></i>
                    <p class="mt-2">لا توجد نتائج</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <!-- Mobile Fix -->
    <style>
        .grid-actions {
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        @media (max-width: 576px) {
            .grid-actions .btn {
                padding: 6px 0;
                font-size: 0.75rem;
            }

            .pagination {
                font-size: 0.75rem;
            }

            .page-link {
                padding: 4px 8px;
            }
        }
    </style>
@endsection
