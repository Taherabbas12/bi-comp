@extends('layouts.employee-layout')

@section('title', 'قائمة المنتجات')

@section('content')

<style>
    :root {
        --primary: #2c3e50;
        --secondary: #34495e;
        --accent: #3498db;
        --light: #ecf0f1;
        --danger: #e74c3c;
        --warning: #f39c12;
    }

    .filter-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

/* ... باقي القواعد ... */

.form-control,
.form-select {
    height: 55px;
    font-size: 1.1em;
    border-radius: 12px;
    /* تغيير الخلفية لجعلها شفافة أكثر */
    background: rgba(255, 255, 255, 0.15);
    color: white !important;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 12px 15px;
}

/* تخصيص حقل البحث فقط */
#search {
    background: rgba(255, 255, 255, 0.2) !important; /* شفافية أكثر */
    border: 2px solid rgba(255, 255, 255, 0.4) !important;
    color: white !important;
    /* إزالة الظل الافتراضي */
    box-shadow: none !important;
    outline: none !important;
}

#search::placeholder {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* ... باقي القواعد ... */

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-label {
        font-weight: 700;
        color: white;
    }

    .btn-primary {
        background: var(--accent);
        border: none;
        height: 55px;
        font-size: 1.2em;
        font-weight: bold;
        border-radius: 12px;
    }

    .btn-outline-primary {
        height: 55px;
        font-size: 1.2em;
        border-radius: 12px;
        color: white;
        border-color: white;
    }

    /* الكروت */
    .product-card {
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(5px);
        color: white;
        padding: 18px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        transition: 0.25s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.4);
    }

    .brand-badge {
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        font-size: 0.85em;
        border-radius: 14px;
    }

    .quantity-badge {
        padding: 6px 12px;
        border-radius: 14px;
        font-size: 0.85em;
        font-weight: bold;
        color: white;
    }

    .quantity-badge.low { background: var(--warning); }
    .quantity-badge.out { background: var(--danger); }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        background: rgba(255,255,255,0.10);
        padding: 10px;
        border-radius: 12px;
    }

    .feature-tag {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: .75em;
        font-weight: bold;
        color: white;
    }

    .feature-touch { background: #1976d2; }
    .feature-convertible { background: #7b1fa2; }
    .feature-gpu { background: #e65100; }
    .feature-gaming { background: #c62828; }
    .feature-editing { background: #2e7d32; }

    .price {
        font-size: 1.6em;
        font-weight: bold;
        color: var(--accent);
    }

    .btn-order {
        background: var(--accent);
        border: none;
        width: 100%;
        padding: 12px;
        font-size: 1.1em;
        border-radius: 12px;
        color: white;
        font-weight: bold;
        margin-top: 10px;
    }
</style>


<h2 class="fw-bold text-white mb-4" style="font-size: 2.2em;">🖥️ قائمة المنتجات</h2>

{{-- فلاتر --}}
<div class="filter-card">
    <h2 class="text-white fw-bold mb-3">🔍 البحث والفلاتر</h2>

    {{-- البحث --}}
    <div class="mb-4">
        <input type="nubmer" id="search" class="form-control"
               placeholder="ابحث بالاسم، الماركة، الباركود...">
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label">الماركة</label>
            <select id="brand" class="form-select">
                <option value="">كل الماركات</option>
                @foreach($brands as $b) <option>{{ $b }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">الرام</label>
            <select id="ram" class="form-select">
                <option value="">الكل</option>
                @foreach($rams as $r) <option>{{ $r }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">التخزين</label>
            <select id="storage" class="form-select">
                <option value="">الكل</option>
                @foreach($storages as $s) <option>{{ $s }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">الشاشة</label>
            <select id="screen" class="form-select">
                <option value="">الكل</option>
                @foreach($screens as $s) <option>{{ $s }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">المعالج</label>
            <select id="processor" class="form-select">
                <option value="">الكل</option>
                @foreach($processors as $p) <option>{{ $p }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">الكرت الرسومي</label>
            <select id="gpu" class="form-select">
                <option value="">الكل</option>
                @foreach($gpus as $g) <option>{{ $g }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">السعر الأدنى</label>
            <input id="min_price" type="number" class="form-control" placeholder="200000">
        </div>

        <div class="col-md-6">
            <label class="form-label">السعر الأعلى</label>
            <input id="max_price" type="number" class="form-control" placeholder="700000">
        </div>

        <div class="col-md-12 d-flex flex-wrap gap-4 mt-3">
            <label><input type="checkbox" id="touch"> شاشة لمس</label>
            <label><input type="checkbox" id="convertible"> قلاب</label>
            <label><input type="checkbox" id="gaming"> ألعاب</label>
            <label><input type="checkbox" id="editing"> مونتاج</label>
            <label><input type="checkbox" id="hide_expired"> إخفاء المنتهية</label>
        </div>

        <div class="col-12 d-flex gap-3 mt-3">
            <button class="btn btn-primary w-100" onclick="fetchLaptops()">تطبيق</button>
            <button class="btn btn-outline-primary w-100" onclick="resetFilters()">إعادة</button>
        </div>

    </div>
</div>



{{-- النتائج --}}
<div id="laptopCards">
    @include('response.laptops.cards', ['laptops' => $laptops])
</div>

@endsection


@section('scripts')
<script>

    /** ==============================
     *       Real-Time Fetch
     * =============================== */

    let fetchTimeout = null;

    function realtimeFetch() {
        clearTimeout(fetchTimeout);

        // نضع Delay بسيط لتجنب الضغط على السيرفر
        fetchTimeout = setTimeout(() => {
            fetchLaptops();
        }, 300);
    }

function fetchLaptops(page = 1) {
    console.log("fetchLaptops called with page:", page);

    const params = new URLSearchParams({
        search: document.getElementById('search')?.value || '',
        brand: document.getElementById('brand')?.value || '',
        ram: document.getElementById('ram')?.value || '',
        storage: document.getElementById('storage')?.value || '',
        screen: document.getElementById('screen')?.value || '', // ✅ تأكد من أن القيمة فارغة بدل undefined
        processor: document.getElementById('processor')?.value || '',
        gpu: document.getElementById('gpu')?.value || '',
        min_price: document.getElementById('min_price')?.value || '',
        max_price: document.getElementById('max_price')?.value || '',
        touch: document.getElementById('touch')?.checked ? 1 : '',
        convertible: document.getElementById('convertible')?.checked ? 1 : '',
        gaming: document.getElementById('gaming')?.checked ? 1 : '',
        editing: document.getElementById('editing')?.checked ? 1 : '',
        hide_expired: document.getElementById('hide_expired')?.checked ? 1 : '',
        page: page
    });

    // ✅ تأكد من عدم إرسال قيم غير معرفة
    for (let [key, value] of params.entries()) {
        if (value === 'undefined') {
            params.set(key, '');
        }
    }

    console.log("Sending params:", params.toString());

    fetch("{{ route('response.laptops.filter') }}?" + params.toString(), {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => {
        console.log("Response status:", response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        console.log("Received HTML length:", html.length);
        const element = document.getElementById('laptopCards');
        if (element) {
            element.innerHTML = html;
            attachPagination();
        }
    })
    .catch(error => {
        console.error('Error fetching laptops:', error);
    });
}

    /** ==============================
     *   Pagination AJAX Hook
     * =============================== */
    function attachPagination() {
        document.querySelectorAll('#laptopCards .pagination a').forEach(link => {
            link.onclick = function(e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                fetchLaptops(page);
            }
        });
    }

    /** ==============================
     *      Reset Filters
     * =============================== */
    function resetFilters() {
        document.querySelectorAll("input, select").forEach(el => {
            if (el.type === "checkbox") el.checked = false;
            else el.value = "";
        });

        realtimeFetch();
    }

    /** ==============================
     *   Attach Real-Time Listeners
     * =============================== */
    window.onload = function() {

        // كل Input / Select يعمل RealTime
        document.querySelectorAll(
            "#search, #brand, #ram, #storage, #screen, #processor, #gpu, #min_price, #max_price"
        ).forEach(el => {

            el.addEventListener("input", realtimeFetch);
            el.addEventListener("change", realtimeFetch);

        });

        // كل CheckBox يفلتر تلقائيًا
        document.querySelectorAll(
            "#touch, #convertible, #gaming, #editing, #hide_expired"
        ).forEach(el => {
            el.addEventListener("change", realtimeFetch);
        });

    };

</script>
@endsection

