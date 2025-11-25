@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>✏️ تعديل الجهاز</h1>
        <p class="subtitle">تعديل بيانات الجهاز: {{ $laptop->name }}</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.laptops.update', $laptop) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="barcode" class="form-label">الباركود</label>
                        <input type="text" class="form-control" id="barcode" name="barcode"
                            value="{{ old('barcode', $laptop->barcode) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $laptop->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="brand" class="form-label">الشركة</label>
                        <input type="text" class="form-control" id="brand" name="brand"
                            value="{{ old('brand', $laptop->brand) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="model" class="form-label">الموديل</label>
                        <input type="text" class="form-control" id="model" name="model"
                            value="{{ old('model', $laptop->model) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="processor" class="form-label">المعالج</label>
                        <input type="text" class="form-control" id="processor" name="processor"
                            value="{{ old('processor', $laptop->processor) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="ram" class="form-label">الرام</label>
                        <input type="text" class="form-control" id="ram" name="ram"
                            value="{{ old('ram', $laptop->ram) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="storage" class="form-label">التخزين</label>
                        <input type="text" class="form-control" id="storage" name="storage"
                            value="{{ old('storage', $laptop->storage) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="screen" class="form-label">الشاشة</label>
                        <input type="text" class="form-control" id="screen" name="screen"
                            value="{{ old('screen', $laptop->screen) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">الكمية</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            value="{{ old('quantity', $laptop->quantity) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_numeric" class="form-label">السعر (رقمي، مثلاً: 500 → 500000)</label>
                        <input type="number" class="form-control" id="price_numeric" name="price_numeric"
                            value="{{ old('price_numeric', $laptop->price_numeric) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_display" class="form-label">السعر (لعرض، مثلاً: 500,000 د.ع)</label>
                        <input type="text" class="form-control" id="price_display" name="price_display"
                            value="{{ old('price_display', $laptop->price_display) }}" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_touch" name="is_touch"
                                {{ old('is_touch', $laptop->is_touch) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_touch">
                                شاشة لمس
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_convertible" name="is_convertible"
                                {{ old('is_convertible', $laptop->is_convertible) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_convertible">
                                قلاب (360°)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="gpu" class="form-label">الكرت الرسومي</label>
                        <input type="text" class="form-control" id="gpu" name="gpu"
                            value="{{ old('gpu', $laptop->gpu) }}">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">تحديث الجهاز</button>
                    <a href="{{ route('admin.laptops.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
