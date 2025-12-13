@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card text-white bg-dark border-light">
                    <div class="card-header">
                        <h1 class="h4 mb-0">➕ إضافة دور جديد</h1>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted mb-4">املأ البيانات التالية</p>
                        <form action="{{ route('admin.roles.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">اسم الدور *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="permissions" class="form-label">الصلاحيات</label>
                                <div class="row g-2" id="permissions">
                                    @foreach ($permissions as $permission)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <!-- 💥 تعديل: حذف form-check، وجعل div خارجي هو "الزر" -->
                                            <div class="permission-item p-2 border rounded cursor-pointer" 
                                                 data-permission-id="{{ $permission->id }}" 
                                                 style="background-color: var(--bs-gray-dark);">
                                                <input class="form-check-input d-none @error('permissions') is-invalid @enderror" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="perm_{{ $permission->id }}" 
                                                       onchange="togglePermissionStyle(this)">
                                                <label class="form-check-label text-break w-100 h-100 d-flex align-items-center ps-4 mb-0 cursor-pointer" 
                                                       for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('permissions')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">إضافة الدور</button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary flex-fill">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* 💥 تعديل: أسلوب للعنصر المحدد */
        .permission-item.selected {
            background-color: var(--bs-primary) !important; /* لون خلفية عند التحديد */
            border-color: var(--bs-primary) !important; /* لون حد عند التحديد */
        }
        .permission-item {
            transition: background-color 0.2s, border-color 0.2s; /* انتقال ناعم */
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <script>
        // 💥 وظيفة لتبديل نمط العنصر عند تغيير الحالة
        function togglePermissionStyle(checkbox) {
            const item = checkbox.closest('.permission-item');
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        }

        // 💥 تهيئة الحالة عند تحميل الصفحة (مهم لتعديل الأدوار)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.permission-item input[type="checkbox"]').forEach(checkbox => {
                togglePermissionStyle(checkbox); // تطبيق الحالة الأولية
            });

            // 💥 إضافة مستمع حدث للنقر على العنصر بأكمله (الإطار)
            document.querySelectorAll('.permission-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // تجنب النقر على مدخل نصي أو عنصر آخر داخلي قد يسبب مشكلة
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                        const checkbox = this.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked; // تبديل الحالة
                        togglePermissionStyle(checkbox); // تحديث النمط
                    }
                });
            });
        });
    </script>
@endsection