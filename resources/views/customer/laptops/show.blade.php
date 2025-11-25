<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المنتج - {{ $laptop->name }}</title>
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
            color: white;
            padding: 20px 0;
        }

        .container {
            padding: 0 15px;
        }

        .product-detail-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .order-form-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1,
        h2,
        h3 {
            color: white;
        }

        .price {
            font-size: 2em;
            font-weight: 800;
            color: var(--accent);
            margin: 15px 0;
        }

        .payment-info {
            background: rgba(248, 249, 250, 0.2);
            padding: 15px;
            border-radius: 12px;
            margin: 15px 0;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: rgba(255, 255, 255, 0.15);
            color: white;
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
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('laptops.index') }}" class="btn btn-outline-light mb-3">← العودة للقائمة</a>
        <div class="product-detail-card">
            <h2>تفاصيل المنتج</h2>
            <h3>{{ $laptop->name }}</h3>
            <p><strong>الموديل:</strong> {{ $laptop->name }}</p>
            <p><strong>الشركة:</strong> {{ $laptop->brand }}</p>
            <div class="price">{{ $laptop->price_display }}</div>
            <div class="payment-info">
                <h5>أقساط:</h5>
                <p>10 أشهر: <strong>{{ number_format($monthlyPayment10, 0, ',', ',') }} د.ع</strong> / شهر</p>
                <p>11 أشهر: <strong>{{ number_format($monthlyPayment11, 0, ',', ',') }} د.ع</strong> / شهر</p>
            </div>
        </div>

        <div class="order-form-card">
            <h2>طلب شراء</h2>
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf
                <input type="hidden" name="laptop_barcode" value="{{ $laptop->barcode }}">
                <div class="mb-3">
                    <label for="customerName" class="form-label">اسم الزبون</label>
                    <input type="text" class="form-control" id="customerName" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="customerPhone" class="form-label">رقم الهاتف</label>
                    <input type="tel" class="form-control" id="customerPhone" name="customer_phone" required>
                </div>
                <div class="mb-3">
                    <label for="customerAddress" class="form-label">العنوان</label>
                    <textarea class="form-control" id="customerAddress" name="customer_address" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="orderNotes" class="form-label">ملاحظات حول الطلب</label>
                    <textarea class="form-control" id="orderNotes" name="order_notes" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="source" class="form-label">من أين أتى الطلب؟</label>
                    <select class="form-select" id="source" name="source" required>
                        <option value="">اختر...</option>
                        <option value="facebook">فيسبوك</option>
                        <option value="instagram">انستقرام</option>
                        <option value="other">آخر</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">طريقة الدفع</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_type" id="paymentCash"
                                value="cash" required onchange="toggleInstallmentOptions(false)">
                            <label class="form-check-label" for="paymentCash">نقدًا</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_type" id="paymentInstallment"
                                value="installment" required onchange="toggleInstallmentOptions(true)">
                            <label class="form-check-label" for="paymentInstallment">أقساط</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_type" id="paymentPoints"
                                value="points" required onchange="toggleInstallmentOptions(false)">
                            <label class="form-check-label" for="paymentPoints">نقاط</label>
                        </div>
                    </div>
                </div>
                <div id="installmentOptions" class="alert alert-info" style="display: none;">
                    <label class="form-label">اختر مدة التقسيط:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="installment_months" id="months10"
                                value="10" checked>
                            <label class="form-check-label" for="months10">10 أشهر (قسط شهري:
                                {{ number_format($monthlyPayment10, 0, ',', ',') }} د.ع)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="installment_months" id="months11"
                                value="11">
                            <label class="form-check-label" for="months11">11 أشهر (قسط شهري:
                                {{ number_format($monthlyPayment11, 0, ',', ',') }} د.ع)</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="employeeName" class="form-label">اسم موظف الردود</label>
                    <input type="text" class="form-control" id="employeeName" name="employee_name" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">إرسال الطلب</button>
            </form>
        </div>

        <!-- Optional: Display summary if needed -->
        <!--
        <div class="order-form-card">
            <h2>الملخص</h2>
            <textarea class="form-control" rows="8" readonly>{{ $summaryText ?? '' }}</textarea>
        </div>
        -->
    </div>

    <script>
        function toggleInstallmentOptions(show) {
            const optionsDiv = document.getElementById('installmentOptions');
            optionsDiv.style.display = show ? 'block' : 'none';
        }
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
