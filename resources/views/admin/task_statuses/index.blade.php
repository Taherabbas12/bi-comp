@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>إدارة الحالات</h4>
                        <a href="{{ route('admin.task_statuses.create') }}" class="btn btn-primary">إضافة حالة</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الاسم المعروض</th>
                                    <th>اللون</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statuses as $status)
                                    <tr>
                                        <td>{{ $status->name }}</td>
                                        <td>{{ $status->display_name }}</td>
                                        <td><span class="badge"
                                                style="background-color: {{ $status->color_code }}">{{ $status->color_code }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.task_statuses.edit', $status) }}"
                                                class="btn btn-sm btn-warning">تعديل</a>
                                            <form action="{{ route('admin.task_statuses.destroy', $status) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد حالات.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
