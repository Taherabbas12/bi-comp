<div class="filter-card">
    <h2>🔍 البحث والفلاتر</h2>
    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" id="search-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" name="search"
                        placeholder="🔍 ابحث بالاسم، الماركة أو الباركود..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">بحث</button>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <label class="form-label">الماركة</label>
                <select class="form-select" name="brand">
                    <option value="">جميع الماركات</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                            {{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">الرام</label>
                <select class="form-select" name="ram">
                    <option value="">جميع الأحجام</option>
                    @foreach ($rams as $ram)
                        <option value="{{ $ram }}" {{ request('ram') == $ram ? 'selected' : '' }}>
                            {{ $ram }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">التخزين</label>
                <select class="form-select" name="storage">
                    <option value="">جميع الأحجام</option>
                    @foreach ($storages as $storage)
                        <option value="{{ $storage }}" {{ request('storage') == $storage ? 'selected' : '' }}>
                            {{ $storage }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">الشاشة</label>
                <select class="form-select" name="screen">
                    <option value="">جميع الأحجام</option>
                    @foreach ($screens as $screen)
                        <option value="{{ $screen }}" {{ request('screen') == $screen ? 'selected' : '' }}>
                            {{ $screen }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">المعالج</label>
                <select class="form-select" name="processor">
                    <option value="">جميع المعالجات</option>
                    @foreach ($processors as $proc)
                        <option value="{{ $proc }}" {{ request('processor') == $proc ? 'selected' : '' }}>
                            {{ $proc }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">الكرت الرسومي</label>
                <select class="form-select" name="gpu">
                    <option value="">جميع الكروت</option>
                    @foreach ($gpus as $gpu)
                        <option value="{{ $gpu }}" {{ request('gpu') == $gpu ? 'selected' : '' }}>
                            {{ $gpu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">نطاق السعر من (أدخل رقم مثل 500 → 500,000 د.ع)</label>
                <input type="number" class="form-control" name="min_price" placeholder="500"
                    value="{{ request('min_price') }}">
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">إلى</label>
                <input type="number" class="form-control" name="max_price" placeholder="1000"
                    value="{{ request('max_price') }}">
            </div>

            <div class="col-12">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="checkbox-item">
                            <input type="checkbox" name="touch" value="1" id="touch"
                                {{ request('touch') ? 'checked' : '' }}>
                            <label for="touch" class="mb-0">شاشة لمس فقط</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox-item">
                            <input type="checkbox" name="convertible" value="1" id="convertible"
                                {{ request('convertible') ? 'checked' : '' }}>
                            <label for="convertible" class="mb-0">قلاب (360°) فقط</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox-item">
                            <input type="checkbox" name="gaming" value="1" id="gaming"
                                {{ request('gaming') ? 'checked' : '' }}>
                            <label for="gaming" class="mb-0">مخصص للألعاب</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox-item">
                            <input type="checkbox" name="editing" value="1" id="editing"
                                {{ request('editing') ? 'checked' : '' }}>
                            <label for="editing" class="mb-0">مخصص للمونتاج</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox-item">
                            <input type="checkbox" name="hide_expired" value="1" id="hide_expired"
                                {{ request('hide_expired') ? 'checked' : '' }}>
                            <label for="hide_expired" class="mb-0">إخفاء المنتهية</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <label class="form-label">الفرز</label>
                <div class="d-flex gap-2">
                    <select class="form-select" name="sort">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>السعر</option>
                        <option value="quantity" {{ request('sort') == 'quantity' ? 'selected' : '' }}>الكمية</option>
                        <option value="brand" {{ request('sort') == 'brand' ? 'selected' : '' }}>الماركة</option>
                    </select>
                    <select class="form-select" name="order">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6 col-lg-8 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">تطبيق الفلاتر</button>
                <a href="{{ request()->url() }}" class="btn btn-outline-primary">إعادة تعيين</a>
                @if (request('hide_expired'))
                    <a href="{{ request()->fullUrlWithQuery(['hide_expired' => null]) }}"
                        class="btn btn-danger">إظهار المنتهية</a>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['hide_expired' => 1]) }}" class="btn btn-danger">إخفاء
                        المنتهية</a>
                @endif
            </div>
        </div>
    </form>
</div>
