<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر BI للحاسبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* نفس CSS من الكود الأصلي */
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #212529;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding: 20px 0;
        }

        .container {
            padding: 0 15px;
        }

        .header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            color: white;
            font-weight: 800;
            font-size: 2.8em;
            margin-bottom: 5px;
            letter-spacing: -1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            color: #ecf0f1;
            font-size: 1.3em;
            font-weight: 500;
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .filter-card h2 {
            color: white;
            font-weight: 700;
            font-size: 1.8em;
            margin-bottom: 25px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .btn-outline-primary {
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: white;
            color: var(--accent);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .results-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            font-size: 1.2em;
            font-weight: 600;
            color: #ecf0f1;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .product-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            /* لون النص الرئيسي */
            cursor: pointer;
            /* لجعل الكارت قابلاً للنقر */
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .brand-badge {
            background: linear-gradient(135deg, var(--accent) 0%, #2c3e50 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quantity-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            color: white;
        }

        .quantity-badge.low {
            background: var(--warning);
        }

        .quantity-badge.out {
            background: var(--danger);
        }

        .product-model {
            font-size: 1.3em;
            font-weight: 700;
            color: white;
            /* لون النص */
            margin: 15px 0;
            line-height: 1.4;
            /* إزالة الارتفاع الثابت والاقتطاع */
            white-space: normal;
            /* السماح للنص بالالتفاف */
            overflow: visible;
            /* عرض النص بالكامل */
            text-overflow: clip;
            /* لا اقتطاع */
            flex-grow: 1;
            /* لجعل الكارت يملأ المساحة المتوفرة */
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
            padding: 15px;
            background: rgba(248, 249, 250, 0.2);
            border-radius: 12px;
            font-size: 0.9em;
            color: white;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
        }

        .spec-label {
            font-size: 0.8em;
            color: #bdc3c7;
            /* لون فاتح للوسم */
            margin-bottom: 3px;
        }

        .spec-value {
            font-weight: 700;
            color: white;
        }

        .features {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        .feature-tag {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            color: white;
        }

        .feature-touch {
            background: #e3f2fd;
            color: #1976d2;
        }

        .feature-convertible {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .feature-gpu {
            background: #fff3e0;
            color: #e65100;
        }

        .feature-gaming {
            background: #ffebee;
            color: #c62828;
        }

        .feature-editing {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .price-section {
            margin-top: auto;
            padding-top: 20px;
            border-top: 2px solid rgba(238, 238, 238, 0.3);
            text-align: center;
        }

        .price {
            font-size: 1.8em;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 5px;
        }

        .payment-info {
            font-size: 0.9em;
            color: #ecf0f1;
            margin-top: 5px;
        }

        .barcode {
            font-size: 0.85em;
            color: #bdc3c7;
            /* لون فاتح للوسم */
            font-family: 'Courier New', monospace;
            margin-top: 5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--accent);
        }

        label {
            color: white;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.2em;
            }

            .subtitle {
                font-size: 1.1em;
            }

            .product-card {
                margin-bottom: 20px;
            }

            .filter-card {
                padding: 20px;
            }

            .filter-card h2 {
                font-size: 1.5em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>🖥️ متجر BI للحاسبات</h1>
            <p class="subtitle">أفضل العروض على الأجهزة عالية الجودة</p>
            <!-- زر تسجيل الخروج -->
            <a href="{{ route('logout') }}" class="btn btn-danger mt-3"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </header>
        <div class="filter-card">
            <h2>🔍 البحث والفلاتر</h2>
            <form method="GET" action="">
                <!-- البحث -->
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
                <!-- الفلاتر -->
                <div class="row g-3">
                    <!-- العمود الأيسر -->
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
                                <option value="{{ $storage }}"
                                    {{ request('storage') == $storage ? 'selected' : '' }}>{{ $storage }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">الشاشة</label>
                        <select class="form-select" name="screen">
                            <option value="">جميع الأحجام</option>
                            @foreach ($screens as $screen)
                                <option value="{{ $screen }}"
                                    {{ request('screen') == $screen ? 'selected' : '' }}>{{ $screen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">المعالج</label>
                        <select class="form-select" name="processor">
                            <option value="">جميع المعالجات</option>
                            @foreach ($processors as $proc)
                                <option value="{{ $proc }}"
                                    {{ request('processor') == $proc ? 'selected' : '' }}>{{ $proc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">الكرت الرسومي</label>
                        <select class="form-select" name="gpu">
                            <option value="">جميع الكروت</option>
                            @foreach ($gpus as $gpu)
                                <option value="{{ $gpu }}" {{ request('gpu') == $gpu ? 'selected' : '' }}>
                                    {{ $gpu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- نطاق السعر -->
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
                    <!-- خيارات متقدمة -->
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
                    <!-- زر الفرز -->
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">الفرز</label>
                        <div class="d-flex gap-2">
                            <select class="form-select" name="sort">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم
                                </option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>السعر
                                </option>
                                <option value="quantity" {{ request('sort') == 'quantity' ? 'selected' : '' }}>الكمية
                                </option>
                                <option value="brand" {{ request('sort') == 'brand' ? 'selected' : '' }}>الماركة
                                </option>
                            </select>
                            <select class="form-select" name="order">
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي
                                </option>
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي
                                </option>
                            </select>
                        </div>
                    </div>
                    <!-- أزرار التحكم -->
                    <div class="col-md-6 col-lg-8 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">تطبيق الفلاتر</button>
                        <a href="{{ request()->url() }}" class="btn btn-outline-primary">إعادة تعيين</a>
                        @if (request('hide_expired'))
                            <a href="{{ request()->fullUrlWithQuery(['hide_expired' => null]) }}"
                                class="btn btn-danger">إظهار المنتهية</a>
                        @else
                            <a href="{{ request()->fullUrlWithQuery(['hide_expired' => 1]) }}"
                                class="btn btn-danger">إخفاء المنتهية</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="results-info">
            📊 عدد النتائج: <strong>{{ count($laptops) }}</strong> جهاز
        </div>
        <div class="row g-4">
            @forelse($laptops as $laptop)
                @php
                    $monthlyPayment10 = $laptop->calculateMonthlyPayment(10);
                    $monthlyPayment11 = $laptop->calculateMonthlyPayment(11);
                @endphp
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <!-- تعديل: استخدام رابط لفتح صفحة التفاصيل -->
                    @if (!empty($laptop->barcode))
                        <a href="{{ route('laptops.show', $laptop->barcode) }}" class="text-decoration-none">
                    @endif
                    <div class="product-card h-100">
                        <div class="product-header">
                            @if ($laptop->brand)
                                <span class="brand-badge">{{ $laptop->brand }}</span>
                            @endif
                            @php
                                $qtyClass = '';
                                if ($laptop->quantity <= 0) {
                                    $qtyClass = 'out';
                                } elseif ($laptop->quantity <= 2) {
                                    $qtyClass = 'low';
                                }
                            @endphp
                            <span class="quantity-badge {{ $qtyClass }}">
                                {{ $laptop->quantity > 0 ? "متوفر: {$laptop->quantity}" : 'منتهية' }}
                            </span>
                        </div>
                        <div class="product-model">{{ $laptop->name }}</div>
                        <div class="specs-grid">
                            @if ($laptop->processor)
                                <div class="spec-item">
                                    <span class="spec-label">المعالج</span>
                                    <span class="spec-value">{{ $laptop->processor }}</span>
                                </div>
                            @endif
                            @if ($laptop->ram)
                                <div class="spec-item">
                                    <span class="spec-label">الرام</span>
                                    <span class="spec-value">{{ $laptop->ram }}</span>
                                </div>
                            @endif
                            @if ($laptop->storage)
                                <div class="spec-item">
                                    <span class="spec-label">الهارد</span>
                                    <span class="spec-value">{{ $laptop->storage }}</span>
                                </div>
                            @endif
                            @if ($laptop->screen)
                                <div class="spec-item">
                                    <span class="spec-label">الشاشة</span>
                                    <span class="spec-value">{{ $laptop->screen }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="features">
                            @if ($laptop->is_touch)
                                <span class="feature-tag feature-touch">✋ لمس</span>
                            @endif
                            @if ($laptop->is_convertible)
                                <span class="feature-tag feature-convertible">🔄 قلاب</span>
                            @endif
                            @if ($laptop->gpu)
                                <span class="feature-tag feature-gpu">🎮 {{ $laptop->gpu }}</span>
                            @endif
                            @php
                                $gpu = strtoupper($laptop->gpu);
                                $processor = strtoupper($laptop->processor);
                                $ram = (int) str_replace([' GB', ' GB'], '', $laptop->ram);
                                $hasGpu = in_array($gpu, [
                                    'NVIDIA',
                                    'AMD',
                                    'RADEON',
                                    'MX150',
                                    'MX130',
                                    'QUADRO',
                                    'GTX 1050',
                                ]);
                                $hasI7 = strpos($processor, 'I7') !== false || strpos($processor, 'RYZEN') !== false;
                                $isGaming = $hasGpu || $hasI7;
                                $hasEditingGpu = in_array($gpu, ['QUADRO', 'NVIDIA', 'AMD', 'RADEON']);
                                $hasHighRam = $ram >= 16;
                                $isEditing = $hasEditingGpu || $hasHighRam;
                            @endphp
                            @if ($isGaming)
                                <span class="feature-tag feature-gaming">🎯 ألعاب</span>
                            @endif
                            @if ($isEditing)
                                <span class="feature-tag feature-editing">🎬 مونتاج</span>
                            @endif
                        </div>
                        <div class="price-section">
                            <div class="price">{{ $laptop->price_display }}</div>
                            <!-- إضافة معلومات الأقساط -->
                            <div class="payment-info">
                                <small>10 أشهر: {{ number_format($monthlyPayment10, 0, ',', ',') }}
                                    د.ع/شهر</small><br>
                                <small>11 أشهر: {{ number_format($monthlyPayment11, 0, ',', ',') }} د.ع/شهر</small>
                            </div>
                            @if ($laptop->barcode)
                                <div class="barcode">🏷️ {{ $laptop->barcode }}</div>
                            @endif
                        </div>
                    </div>
                    </a> <!-- نهاية الرابط -->
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search-x-fill" style="font-size: 5rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-white">لا توجد نتائج</h4>
                    <p class="text-muted">حاول تعديل الفلاتر أو البحث.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleHideExpired(hide) {
            const params = new URLSearchParams(window.location.search);
            if (hide) {
                params.set('hide_expired', '1');
            } else {
                params.delete('hide_expired');
            }
            window.location.search = params.toString();
        }
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
