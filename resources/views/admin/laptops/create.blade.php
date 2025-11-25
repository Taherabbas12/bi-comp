@extends('admin.layouts.app')

@section('content')
<div class="header">
    <h1>➕ إضافة جهاز جديد</h1>
    <p class="subtitle">املأ البيانات التالية</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.laptops.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="barcode" class="form-label">الباركود</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" required>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="brand" class="form-label">الشركة</label>
                    <input type="text" class="form-control" id="brand" name="brand">
                </div>
                <div class="col-md-6">
                    <label for="model" class="form-label">الموديل</label>
                    <input type="text" class="form-control" id="model" name="model">
                </div>
                <div class="col-md-6">
                    <label for="processor" class="form-label">المعالج</label>
                    <input type="text" class="form-control" id="processor" name="processor">
                </div>
                <div class="col-md-6">
                    <label for="ram" class="form-label">الرام</label>
                    <input type="text" class="form-control" id="ram" name="ram">
                </div>
                <div class="col-md-6">
                    <label for="storage" class="form-label">التخزين</label>
                    <input type="text" class="form-control" id="storage" name="storage">
                </div>
                <div class="col-md-6">
                    <label for="screen" class="form-label">الشاشة</label>
                    <input type="text" class="form-control" id="screen" name="screen">
                </div>
                <div class="col-md-6">
                    <label for="quantity" class="form-label">الكمية</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="col-md-6">
                    <label for="price_numeric" class="form-label">السعر (رقمي، مثلاً: 500 → 500000)</label>
                    <input type="number" class="form-control" id="price_numeric" name="price_numeric" required>
                </div>
                <div class="col-md-6">
                    <label for="price_display" class="form-label">السعر (لعرض، مثلاً: 500,000 د.ع)</label>
                    <input type="text" class="form-control" id="price_display" name="price_display" required>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_touch" name="is_touch">
                        <label class="form-check-label" for="is_touch">
                            شاشة لمس
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_convertible" name="is_convertible">
                        <label class="form-check-label" for="is_convertible">
                            قلاب (360°)
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="gpu" class="form-label">الكرت الرسومي</label>
                    <input type="text" class="form-control" id="gpu" name="gpu">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">إضافة الجهاز</button>
                <a href="{{ route('admin.laptops.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
