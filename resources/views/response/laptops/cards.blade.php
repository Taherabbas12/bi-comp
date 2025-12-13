<style>
    .results-info {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 4px 4px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        font-size: 1.1em;
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
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: white;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .brand-badge {
        background: linear-gradient(135deg, var(--accent) 0%, #2c3e50 100%);
        color: white;
        padding: 6px 5px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 700;
        text-transform: uppercase;
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
        font-size: 1.1em;
        font-weight: 700;
        margin: 10px 0;
        line-height: 1.4;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin: 10px 0;
        padding: 10px;
        background: rgba(248, 249, 250, 0.1);
        border-radius: 12px;
        font-size: 0.9em;
    }

    .spec-label {
        font-size: 0.8em;
        color: #bdc3c7;
    }

    .spec-value {
        font-weight: 700;
    }

    .features {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin: 10px 0;
    }

    .feature-tag {
        padding: 4px 10px;
        border-radius: 18px;
        font-size: 0.75em;
        font-weight: 600;
        color: white;
    }

    .price-section {
        margin-top: auto;
        padding-top: 12px;
        border-top: 2px solid rgba(238, 238, 238, 0.2);
        text-align: center;
    }

    .price {
        font-size: 1.5em;
        font-weight: 800;
        color: var(--accent);
    }

    .btn-order {
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1em;
        padding: 10px;
        margin-top: 15px;
        width: 100%;
        transition: 0.3s;
        font-weight: 600;
    }

    .btn-order:hover {
        background: #1b7fc9;
        transform: translateY(-3px);
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.25);
    }
</style>

<div class="results-info">
    📊 عدد النتائج: <strong>{{ $laptops->total() }}</strong> جهاز
</div>

<div class="row g-4">
    @forelse($laptops as $laptop)
        @php
            $monthlyPayment10 = method_exists($laptop, 'calculateMonthlyPayment') ? $laptop->calculateMonthlyPayment(10) : 0;
            $monthlyPayment11 = method_exists($laptop, 'calculateMonthlyPayment') ? $laptop->calculateMonthlyPayment(11) : 0;

            $gpu = strtoupper((string) $laptop->gpu);
            $processor = strtoupper((string) $laptop->processor);
            $hasGpu = str_contains($gpu, 'NVIDIA') || str_contains($gpu, 'RADEON') || str_contains($gpu, 'RTX') || str_contains($gpu, 'GTX');
            $hasI7 = str_contains($processor, 'I7') || str_contains($processor, 'I9') || str_contains($processor, 'RYZEN');
            $isGaming = $hasGpu || $hasI7;

            $hasEditingGpu = str_contains($gpu, 'QUADRO') || str_contains($gpu, 'NVIDIA') || str_contains($gpu, 'RADEON');
            $hasHighRam = str_contains((string) $laptop->ram, '16') || str_contains((string) $laptop->ram, '32');
            $isEditing = $hasEditingGpu || $hasHighRam;
        @endphp

        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

            <div class="product-card h-100 p-3">

                <div class="product-header">
                    @if ($laptop->brand)
                        <span class="brand-badge">{{ $laptop->brand }}</span>
                    @endif

                    <span
                        class="quantity-badge {{ $laptop->quantity <= 0 ? 'out' : ($laptop->quantity <= 2 ? 'low' : '') }}">
                        {{ $laptop->quantity > 0 ? "متوفر: {$laptop->quantity}" : 'منتهية' }}
                    </span>
                </div>

                <div class="product-model">{{ $laptop->name }}</div>

                <div class="specs-grid">
                    @if ($laptop->processor)
                        <div>
                            <span class="spec-label">المعالج</span>
                            <span class="spec-value">{{ $laptop->processor }}</span>
                        </div>
                    @endif

                    @if ($laptop->ram)
                        <div>
                            <span class="spec-label">الرام</span>
                            <span class="spec-value">{{ $laptop->ram }}</span>
                        </div>
                    @endif

                    @if ($laptop->storage)
                        <div>
                            <span class="spec-label">الهارد</span>
                            <span class="spec-value">{{ $laptop->storage }}</span>
                        </div>
                    @endif

                    @if ($laptop->screen)
                        <div>
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
                    <div class="price">
                        {{ $laptop->price_display ?? number_format($laptop->price ?? 0, 0) . ' د.ع' }}
                    </div>

                    @if ($monthlyPayment10)
                        <div class="payment-info">
                            <small>10 أشهر: {{ number_format($monthlyPayment10, 0) }} د.ع</small><br>
                        </div>
                    @endif

                    @if ($monthlyPayment11)
                        <div class="payment-info">
                            <small>11 أشهر: {{ number_format($monthlyPayment11, 0) }} د.ع</small>
                        </div>
                    @endif

                    @if ($laptop->barcode)
                        <div class="barcode">🏷️ {{ $laptop->barcode }}</div>
                    @endif
                </div>

                {{-- 🔥 زر الطلب --}}
                <a href="{{ route('response.laptops.createOrder', $laptop->id) }}" class="btn-order">
                    🛒 طلب هذا الجهاز
                </a>

            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search-x" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3 text-white">لا توجد نتائج</h4>
            <p class="text-muted">حاول تعديل الفلاتر أو البحث بكلمة أخرى.</p>
        </div>
    @endforelse
</div>

@if ($laptops->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $laptops->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
@endif
