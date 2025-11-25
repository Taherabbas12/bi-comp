@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>👤 إدارة الأدوار</h1>
        <p class="subtitle">عرض وتعديل الأدوار وصلاحياتها</p>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>قائمة الأدوار</h5>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> إضافة دور جديد
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">الاسم</th>
                            <th scope="col">الصلاحيات</th>
                            <th scope="col">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @forelse($role->permissions as $permission)
                                        <span class="badge bg-secondary">{{ $permission->name }}</span>
                                    @empty
                                        <span class="text-muted">لا توجد صلاحيات</span>
                                    @endforelse
                                </td>
                                <td>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">لا توجد أدوار</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $roles->links() }}
        </div>
    </div>
@endsection
