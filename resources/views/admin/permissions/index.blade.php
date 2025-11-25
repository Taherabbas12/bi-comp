@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>🔐 إدارة الصلاحيات</h1>
        <p class="subtitle">عرض الصلاحيات</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>قائمة الصلاحيات</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">الاسم</th>
                            <!-- يمكنك إضافة أعمدة أخرى لاحقًا -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="1" class="text-center text-muted">لا توجد صلاحيات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($permissions->hasPages())
                <nav class="d-flex justify-content-center mt-4">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($permissions->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">السابق</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $permissions->previousPageUrl() }}" rel="prev">السابق</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($permissions->getUrlRange(1, $permissions->lastPage()) as $page => $url)
                            @if ($page == $permissions->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($permissions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $permissions->nextPageUrl() }}" rel="next">التالي</a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">التالي</span></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
@endsection
