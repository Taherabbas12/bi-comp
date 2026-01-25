@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h4 mb-1">📎 مرفقات الموظف</h1>
                        <small class="text-muted">{{ $user->name }}</small>
                    </div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary">
                        ← رجوع
                    </a>
                </div>

                <!-- Upload Form -->
                <div class="card bg-dark border-light mb-4">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h6 class="mb-0">📤 رفع مرفق جديد</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.attachments.store', $user) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <!-- File Input -->
                                <div class="col-12">
                                    <label class="form-label">📎 اختر ملف</label>
                                    <input type="file" name="file" class="form-control" required
                                        accept="image/*,.pdf,.doc,.docx">
                                    <small class="text-muted">الحد الأقصى: 10 MB (صور، PDF، Word)</small>
                                </div>

                                <!-- Attachment Type -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label">📋 نوع المرفق</label>
                                    <select name="attachment_type" class="form-control bg-dark text-light border-secondary"
                                        required>
                                        <option value="" class="bg-dark text-light">-- اختر --</option>
                                        @foreach ($attachmentTypes as $key => $label)
                                            <option value="{{ $key }}" class="bg-dark text-light">
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Primary -->
                                <div class="col-12 col-md-6">
                                    <div class="form-check pt-4">
                                        <input type="checkbox" class="form-check-input" name="is_primary" id="is_primary"
                                            value="1">
                                        <label class="form-check-label" for="is_primary">
                                            ⭐ جعله المرفق الأساسي من هذا النوع
                                        </label>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label">📝 ملاحظات (اختياري)</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="مثال: الهوية الوطنية الحالية..."></textarea>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-cloud-upload"></i> رفع المرفق
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Attachments List -->
                @if ($attachments->isEmpty())
                    <div class="alert alert-info text-center py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">لا توجد مرفقات حتى الآن</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($attachments as $attachment)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card bg-dark border-light h-100">
                                    <!-- Image Preview -->
                                    @if ($attachment->isImage())
                                        <div class="card-img-top bg-secondary" style="height: 200px; overflow: hidden;">
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                                alt="{{ $attachment->file_name }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                            style="height: 200px;">
                                            <div class="text-center">
                                                <i class="bi bi-file-earmark-pdf fs-1"></i>
                                                <p class="text-muted mt-2">{{ strtoupper($attachment->file_path) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        <!-- Type Badge -->
                                        <div class="mb-2">
                                            <span class="badge bg-info">{{ $attachment->attachment_type_name }}</span>
                                            @if ($attachment->is_primary)
                                                <span class="badge bg-warning">⭐ أساسي</span>
                                            @endif
                                        </div>

                                        <!-- File Name -->
                                        <p class="card-title text-truncate" title="{{ $attachment->file_name }}">
                                            {{ $attachment->file_name }}
                                        </p>

                                        <!-- File Info -->
                                        <small class="text-muted d-block">
                                            <i class="bi bi-file"></i> {{ $attachment->formatted_size }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-calendar"></i>
                                            {{ $attachment->created_at->format('Y-m-d H:i') }}
                                        </small>

                                        <!-- Description -->
                                        @if ($attachment->description)
                                            <p class="text-muted small mt-2 mb-0">
                                                {{ $attachment->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="card-footer bg-dark border-top border-secondary">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.attachments.download', [$user, $attachment]) }}"
                                                class="btn btn-sm btn-outline-success" title="تحميل">
                                                <i class="bi bi-download"></i> تحميل
                                            </a>

                                            @if (!$attachment->is_primary)
                                                <form
                                                    action="{{ route('admin.attachments.setPrimary', [$user, $attachment]) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100"
                                                        title="اجعله أساسي">
                                                        <i class="bi bi-star"></i> اجعله أساسي
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.attachments.destroy', [$user, $attachment]) }}"
                                                method="POST" onsubmit="return confirm('هل تريد حذف هذا المرفق؟');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                                    title="حذف">
                                                    <i class="bi bi-trash"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: #0dcaf0 !important;
            box-shadow: 0 0 15px rgba(13, 202, 240, 0.3);
        }

        .card-img-top {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-footer {
            padding: 0.75rem;
        }

        .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        /* Style for select dropdown */
        select.form-control {
            background-color: #212529;
            color: #fff;
            border-color: #495057;
        }

        select.form-control:focus {
            background-color: #212529;
            color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        select.form-control option {
            background-color: #212529;
            color: #fff;
        }
    </style>
@endsection
