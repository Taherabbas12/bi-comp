<div class="card mb-4 shadow-sm filter-card" style="padding: 25px; border-radius: 20px;">
    <h4 class="text-white fw-bold mb-4">🔍 البحث والفلاتر</h4>

    <div class="row g-3">

        {{-- بحث عام --}}
        <div class="col-12">
            <input type="text" id="search" class="form-control form-control-lg"
                placeholder="ابحث بالاسم أو الماركة أو الباركود...">
        </div>

        {{-- الماركة --}}
        <div class="col-md-4">
            <label class="text-white">الماركة</label>
            <select id="brand" class="form-select form-select-lg">
                <option value="">كل الماركات</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}">{{ $brand }}</option>
                @endforeach
            </select>
        </div>

        {{-- الرام --}}
        <div class="col-md-4">
            <label class="text-white">الرام</label>
            <select id="ram" class="form-select form-select-lg">
                <option value="">كل الأنواع</option>
                @foreach ($rams as $ram)
                    <option value="{{ $ram }}">{{ $ram }}</option>
                @endforeach
            </select>
        </div>

        {{-- التخزين --}}
        <div class="col-md-4">
            <label class="text-white">التخزين</label>
            <select id="storage" class="form-select form-select-lg">
                <option value="">كل الأنواع</option>
                @foreach ($storages as $storage)
                    <option value="{{ $storage }}">{{ $storage }}</option>
                @endforeach
            </select>
        </div>

        {{-- الشاشة --}}
        <div class="col-md-4">
            <label class="text-white">الشاشة</label>
            <select id="screen" class="form-select form-select-lg">
                <option value="">كل المقاسات</option>
                @foreach ($screens as $screen)
                    <option value="{{ $screen }}">{{ $screen }}</option>
                @endforeach
            </select>
        </div>

        {{-- المعالج --}}
        <div class="col-md-4">
            <label class="text-white">المعالج</label>
            <select id="processor" class="form-select form-select-lg">
                <option value="">كل الأنواع</option>
                @foreach ($processors as $processor)
                    <option value="{{ $processor }}">{{ $processor }}</option>
                @endforeach
            </select>
        </div>

        {{-- كرت الشاشة --}}
        <div class="col-md-4">
            <label class="text-white">الكارت الرسومي</label>
            <select id="gpu" class="form-select form-select-lg">
                <option value="">كل الأنواع</option>
                @foreach ($gpus as $gpu)
                    <option value="{{ $gpu }}">{{ $gpu }}</option>
                @endforeach
            </select>
        </div>

        {{-- السعر الأدنى --}}
        <div class="col-md-6">
            <label class="text-white">السعر الأدنى</label>
            <input type="number" id="min_price" class="form-control form-control-lg" placeholder="0">
        </div>

        {{-- السعر الأعلى --}}
        <div class="col-md-6">
            <label class="text-white">السعر الأعلى</label>
            <input type="number" id="max_price" class="form-control form-control-lg" placeholder="9999999">
        </div>

        {{-- خيارات إضافية --}}
        <div class="col-md-12 mt-3">
            <label class="text-white fw-bold mb-2 d-block">⚙ خيارات إضافية:</label>

            <div class="d-flex flex-wrap gap-4">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="touch">
                    <label class="form-check-label text-white" for="touch">شاشة لمس</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="convertible">
                    <label class="form-check-label text-white" for="convertible">قلاب 360°</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gaming">
                    <label class="form-check-label text-white" for="gaming">مخصص للألعاب 🎮</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="editing">
                    <label class="form-check-label text-white" for="editing">مخصص للمونتاج 🎬</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hide_expired">
                    <label class="form-check-label text-white" for="hide_expired">إخفاء المنتهية</label>
                </div>

            </div>
        </div>

        {{-- أزرار التحكم --}}
        <div class="col-12 mt-4 d-flex gap-3">
            <button onclick="fetchLaptops()" class="btn btn-primary btn-lg w-100">تطبيق الفلاتر</button>
            <button onclick="resetFilters()" class="btn btn-outline-light btn-lg w-50">إعادة تعيين</button>
        </div>

    </div>
</div>
