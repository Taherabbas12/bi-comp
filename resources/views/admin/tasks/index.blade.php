@extends('admin.layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>إدارة المهام</h4>
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">إضافة مهمة</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <label for="start_date">من تاريخ</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">إلى تاريخ</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-outline-primary form-control">ترشيح</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Filters -->

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>الموظف</th>
                                    <th>الحالة</th>
                                    <th>الأولوية</th>
                                    <th>نسبة الإنجاز</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->assignedTo->name ?? 'غير محدد' }}</td>
                                        <td><span class="badge"
                                                style="background-color: {{ $task->status->color_code }}">{{ $task->status->display_name }}</span>
                                        </td>
                                        <td><span class="badge"
                                                style="background-color: {{ $task->priority->color_code }}">{{ $task->priority->display_name }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $task->progress_percentage }}%; background-color: {{ $task->priority->color_code }};"
                                                    aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">{{ $task->progress_percentage }}%</div>
                                            </div>
                                        </td>
                                        <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tasks.show', $task) }}"
                                                class="btn btn-sm btn-info">عرض</a>
                                            <a href="{{ route('admin.tasks.edit', $task) }}"
                                                class="btn btn-sm btn-warning">تعديل</a>
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد مهام.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript لحل مشكلة لون النص في خيارات القائمة المنسدلة -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // احصل على جميع عناصر select في نموذج الترشيح
            const filterSelects = document.querySelectorAll('form[method="GET"] select');

            filterSelects.forEach(function(select) {
                // استمع لحدث فتح القائمة (focus) أو تغيير الخيار (change) لتطبيق التغيير ديناميكيًا إذا لزم الأمر
                // في هذه الحالة، نطبق التغيير بمجرد تحميل الصفحة
                updateOptionColors(select);
            });

            function updateOptionColors(selectElement) {
                // قم بحل مشكلة لون النص لكل خيار (option) داخل هذا العنصر (select)
                const options = selectElement.querySelectorAll('option');

                options.forEach(function(option) {
                    // تحقق مما إذا كان للخيار سمة style تحتوي على لون محدد
                    const hasColorStyle = option.hasAttribute('style') && option.getAttribute('style')
                        .includes('color:');

                    if (!hasColorStyle) {
                        // إذا لم يكن هناك لون محدد، قم بتعيين اللون الأسود
                        option.style.color = '#212529'; // <-- استخدم لونًا داكنًا مثل #212529
                    }
                    // إذا كان هناك لون محدد، فاتركه كما هو (color_code من $priority)
                });
            }
        });
    </script>
@endsection
