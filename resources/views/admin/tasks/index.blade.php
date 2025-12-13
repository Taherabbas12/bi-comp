@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid"> <!-- 💥 استخدم container-fluid -->

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>إدارة المهام</h4>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">إضافة مهمة</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.tasks.index') }}" class="card p-3 mb-4">
                    <div class="row g-2"> <!-- 💥 استخدم gap (g-2) -->
                        <div class="col-12 col-md-3">
                            <label for="assigned_to">الموظف</label>
                            <select name="assigned_to" id="assigned_to" class="form-control">
                                <option value="">الكل</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="status">الحالة</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">الكل</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ request('status') == $status->id ? 'selected' : '' }}>
                                        {{ $status->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="priority">الأولوية</label>
                            <select name="priority" id="priority" class="form-control">
                                <option value="">الكل</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}"
                                        {{ request('priority') == $priority->id ? 'selected' : '' }}
                                        style="color: {{ $priority->color_code }}">{{ $priority->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="start_date">من تاريخ</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="end_date">إلى تاريخ</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-outline-primary form-control">ترشيح</button>
                        </div>
                    </div>
                </form>
                <!-- End Filters -->

                <!-- 💥 عرض الكروت -->
                <div class="row g-3"> <!-- 💥 استخدم Grid Bootstrap -->
                    @forelse($tasks as $task)
                        <div class="col-12 col-lg-6 col-xl-4"> <!-- 💥 كل كرت يأخذ عمودًا حسب حجم الشاشة -->
                            <div class="card text-white bg-dark border-light h-100"> <!-- 💥 استخدم Bootstrap Card -->
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <p class="card-text">
                                        <i class="bi bi-person"></i>
                                        <strong>الموظف:</strong> {{ $task->assignedTo->name ?? 'غير محدد' }}
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-calendar-check"></i>
                                        <strong>الحالة:</strong>
                                        <span class="badge"
                                            style="background-color: {{ $task->status->color_code }}; color: white;">
                                            {{ $task->status->display_name }}
                                        </span>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-flag"></i>
                                        <strong>الأولوية:</strong>
                                        <span class="badge"
                                            style="background-color: {{ $task->priority->color_code }}; color: white;">
                                            {{ $task->priority->display_name }}
                                        </span>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>تاريخ الانتهاء:</strong>
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-bar-chart-fill"></i>
                                        <strong>نسبة الإنجاز:</strong>
                                    </p>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->priority->color_code }};"
                                            aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $task->progress_percentage }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-light">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.tasks.show', $task) }}"
                                            class="btn btn-sm btn-outline-info">عرض</a>
                                        <a href="{{ route('admin.tasks.edit', $task) }}"
                                            class="btn btn-sm btn-outline-warning">تعديل</a>
                                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-list-check" style="font-size: 3rem; color: #adb5bd;"></i>
                                <p class="mt-3">لا توجد مهام.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <!-- 💥 نهاية عرض الكروت -->

            </div>
        </div>
    </div>

    <!-- JavaScript لحل مشكلة لون النص في خيارات القائمة المنسدلة -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('form[method="GET"] select');

            filterSelects.forEach(function(select) {
                updateOptionColors(select);
            });

            function updateOptionColors(selectElement) {
                const options = selectElement.querySelectorAll('option');

                options.forEach(function(option) {
                    const hasColorStyle = option.hasAttribute('style') && option.getAttribute('style')
                        .includes('color:');

                    if (!hasColorStyle) {
                        option.style.color = '#212529';
                    }
                });
            }
        });
    </script>
@endsection