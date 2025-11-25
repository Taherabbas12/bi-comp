<div class="results-info">
    📊 عدد النتائج: <strong>{{ count($laptops) }}</strong> جهاز
</div>

<div class="row g-4">
    @forelse($laptops as $laptop)
        @php
            // تأكد أن دالة calculateMonthlyPayment موجودة في الموديل، وإلا ضع قيمة افتراضية
            $monthlyPayment10 = method_exists($laptop, 'calculateMonthlyPayment')
                ? $laptop->calculateMonthlyPayment(10)
                : 0;
            $monthlyPayment11 = method_exists($laptop, 'calculateMonthlyPayment')
                ? $laptop->calculateMonthlyPayment(11)
                : 0;

            // تجهيز معلومات اسم الكرت/المعالج/الرام بأمان
            $gpu = strtoupper((string) ($laptop->gpu ?? ''));
            $processor = strtoupper((string) ($laptop->processor ?? ''));
            $ramInt = 0;
            if (!empty($laptop->ram)) {
                // إزالة النصوص الغير رقمية
                preg_match('/\d+/', $laptop->ram, $m);
                $ramInt = isset($m[0]) ? (int) $m[0] : 0;
            }

            $hasGpu = in_array($gpu, ['NVIDIA', 'AMD', 'RADEON', 'MX150', 'MX130', 'QUADRO', 'GTX 1050']);
            $hasI7 = strpos($processor, 'I7') !== false || strpos($processor, 'RYZEN') !== false;
            $isGaming = $hasGpu || $hasI7;
            $hasEditingGpu = in_array($gpu, ['QUADRO', 'NVIDIA', 'AMD', 'RADEON']);
            $hasHighRam = $ramInt >= 16;
            $isEditing = $hasEditingGpu || $hasHighRam;

            $qtyClass = '';
            if ($laptop->quantity <= 0) {
                $qtyClass = 'out';
            } elseif ($laptop->quantity <= 2) {
                $qtyClass = 'low';
            }
        @endphp

        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            @if (!empty($laptop->barcode))
                <a href="{{ route('laptops.show', $laptop->barcode) }}" class="text-decoration-none">
            @endif

            <div class="product-card h-100 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    @if ($laptop->brand)
                        <span class="brand-badge">{{ $laptop->brand }}</span>
                    @endif
                    <span class="quantity-badge {{ $qtyClass }}">
                        {{ $laptop->quantity > 0 ? "متوفر: {$laptop->quantity}" : 'منتهية' }}
                    </span>
                </div>

                <div class="product-model">{{ $laptop->name ?? 'بدون اسم' }}</div>

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
                            <span class="spec-label">السعة</span>
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

                    @if ($isGaming)
                        <span class="feature-tag feature-gaming">🎯 ألعاب</span>
                    @endif
                    @if ($isEditing)
                        <span class="feature-tag feature-editing">🎬 مونتاج</span>
                    @endif
                </div>

                <div class="price-section">
                    <div class="price">{{ $laptop->price_display ?? '---' }}</div>
                    <div class="payment-info">
                        <small>10 أشهر: {{ number_format($monthlyPayment10, 0, ',', ',') }} د.ع/شهر</small><br>
                        <small>11 أشهر: {{ number_format($monthlyPayment11, 0, ',', ',') }} د.ع/شهر</small>
                    </div>

                    @if ($laptop->barcode)
                        <div class="barcode">🏷️ {{ $laptop->barcode }}</div>
                    @endif
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.laptops.edit', $laptop) }}" class="btn btn-sm btn-outline-light">✏️
                        تعديل</a>

                    <form action="{{ route('admin.laptops.destroy', $laptop) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">🗑️ حذف</button>
                    </form>
                </div>

            </div>

            @if (!empty($laptop->barcode))
                </a>
            @endif
        </div>

    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search-x-fill" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3 text-white">لا توجد نتائج</h4>
            <p class="text-muted">حاول تعديل الفلاتر أو البحث.</p>
        </div>
    @endforelse
</div>
