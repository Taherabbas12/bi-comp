@extends('layouts.employee-layout')

@section('title', 'إنشاء طلب')

@section('content')
    <style>
        /* Mobile App Style - حقول بارزة ومقاسات كبيرة */
        .mobile-card {
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: #ffffff;
        }

        .mobile-input-group {
            padding: 1.2rem 1.5rem;
            border-bottom: 2px solid #f5f5f5;
        }

        .mobile-input-group:last-child {
            border-bottom: none;
        }

        .mobile-label {
            font-size: 1.1rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: block;
            font-weight: 600;
            color: #4a5568;
        }

        .mobile-input {
            width: 100%;
            border: 3px solid #e2e8f0;
            outline: none;
            font-size: 1.2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .mobile-input:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .mobile-textarea {
            width: 100%;
            border: 3px solid #e2e8f0;
            outline: none;
            font-size: 1.2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            min-height: 120px;
            transition: all 0.3s ease;
        }

        .mobile-textarea:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .mobile-select {
            width: 100%;
            border: 3px solid #e2e8f0;
            outline: none;
            font-size: 1.2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-select:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .mobile-button {
            width: 100%;
            padding: 1.3rem;
            border: none;
            border-radius: 16px;
            font-size: 1.3rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .mobile-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .mobile-section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin: 1.5rem 0 1rem 1.5rem;
            text-align: right;
        }

        .product-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .product-info h4 {
            margin: 0 0 0.75rem 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .product-info p {
            margin: 0.4rem 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .product-info p strong {
            font-size: 1.3rem;
            color: #fff;
        }
    </style>

    <div class="container-fluid px-3 py-3">
        <!-- معلومات المنتج -->
        <div class="product-info">
            <h4>🛒 {{ $laptop->name }}</h4>
            <p>📱 {{ $laptop->brand ?? 'غير محدد' }}</p>
            <p>💰 <strong>{{ $laptop->price_display ?? number_format($laptop->price) . ' د.ع' }}</strong></p>
            <p>📦 المتوفر: {{ $laptop->quantity > 0 ? $laptop->quantity . ' قطعة' : 'منتهية' }}</p>
        </div>

        <form action="{{ route('response.laptops.storeOrder', $laptop) }}" method="POST">
            @csrf

            <!-- معلومات الطلب -->
            <h5 class="mobile-section-title">📝 معلومات الطلب</h5>
            <div class="mobile-card">
                <div class="mobile-input-group">
                    <label class="mobile-label">الكمية</label>
                    <input type="number" name="quantity" class="mobile-input" value="1" min="1"
                        max="{{ $laptop->quantity }}" required>
                </div>

                <div class="mobile-input-group">
                    <label class="mobile-label">طريقة الدفع</label>
                    <select name="payment_type" class="mobile-select" required>
                        <option value="cash">نقدًا</option>
                        <option value="installment">تقسيط</option>
                        <option value="credit">آجل</option>
                    </select>
                </div>

                <div class="mobile-input-group">
                    <label class="mobile-label">أشهر التقسيط (عدد الأشهر)</label>
                    <input type="number" name="installment_months" class="mobile-input" min="1" value="1" required>
                </div>
            </div>

            <!-- معلومات العميل -->
            <h5 class="mobile-section-title">👤 معلومات العميل</h5>
            <div class="mobile-card">
                <div class="mobile-input-group">
                    <label class="mobile-label">اسم الزبون</label>
                    <input type="text" name="client_name" class="mobile-input" required placeholder="أدخل الاسم الكامل">
                </div>

                <div class="mobile-input-group">
                    <label class="mobile-label">رقم الهاتف</label>
                    <input type="text" name="client_phone" class="mobile-input" required placeholder="07XXXXXXXXX">
                </div>

                <div class="mobile-input-group">
                    <label class="mobile-label">العنوان</label>
                    <textarea name="client_address" class="mobile-textarea" required
                        placeholder="أدخل العنوان بالتفصيل"></textarea>
                </div>
            </div>

            <!-- الملاحظات -->
            <h5 class="mobile-section-title">📋 الملاحظات</h5>
            <div class="mobile-card">
                <div class="mobile-input-group">
                    <label class="mobile-label">ملاحظات الطلب</label>
                    <textarea name="order_notes" class="mobile-textarea" required
                        placeholder="ملاحظات حول الطلب..."></textarea>
                </div>

                <div class="mobile-input-group">
                    <label class="mobile-label">ملاحظات إضافية</label>
                    <textarea name="notes" class="mobile-textarea" required placeholder="ملاحظات إضافية..."></textarea>
                </div>
            </div>

            <!-- زر الإرسال -->
            <div style="padding: 2rem 0;">
                <button type="submit" class="mobile-button"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    ✅ إنشاء الطلب
                </button>
            </div>
        </form>
    </div>
@endsection
