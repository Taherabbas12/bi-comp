@extends('admin.layouts.app')

@section('content')
    <div class="header">
        <h1>➕ إنشاء طلب جديد</h1>
        <p class="subtitle">أدخل تفاصيل الطلب واسم الزبون</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('response.orders.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customerName" class="form-label">اسم الزبون *</label>
                        <input type="text" class="form-control" id="customerName" name="customer_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="customerPhone" class="form-label">رقم الهاتف *</label>
                        <input type="tel" class="form-control" id="customerPhone" name="customer_phone" required>
                    </div>
                    <div class="col-md-12">
                        <label for="customerAddress" class="form-label">العنوان *</label>
                        <textarea class="form-control" id="customerAddress" name="customer_address" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="source" class="form-label">من أين أتى الطلب؟ *</label>
                        <select class="form-select" id="source" name="source" required>
                            <option value="">اختر...</option>
                            <option value="facebook">فيسبوك</option>
                            <option value="instagram">انستقرام</option>
                            <option value="other">آخر</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="paymentType" class="form-label">طريقة الدفع *</label>
                        <select class="form-select" id="paymentType" name="payment_type" required
                            onchange="toggleInstallmentFields()">
                            <option value="">اختر...</option>
                            <option value="cash">نقدًا</option>
                            <option value="installment">أقساط</option>
                            <option value="points">نقاط</option>
                        </select>
                    </div>
                    <div id="installmentFields" class="col-md-6" style="display: none;">
                        <label for="installmentMonths" class="form-label">مدة التقسيط *</label>
                        <select class="form-select" id="installmentMonths" name="installment_months">
                            <option value="10">10 أشهر</option>
                            <option value="11">11 أشهر</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="orderNotes" class="form-label">ملاحظات حول الطلب</label>
                        <textarea class="form-control" id="orderNotes" name="order_notes" rows="3"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="notes" class="form-label">ملاحظات إضافية</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <!-- حقل اختيار الجهاز -->
                    <div class="col-md-12">
                        <label for="laptopBarcode" class="form-label">الجهاز *</label>
                        <select class="form-select" id="laptopBarcode" name="laptop_barcode" required>
                            <option value="">اختر جهازًا...</option>
                            @foreach ($laptops as $laptop)
                                <option value="{{ $laptop->barcode }}" data-price="{{ $laptop->price_numeric }}">
                                    {{ $laptop->name }} ({{ $laptop->brand }}) - {{ $laptop->price_display }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">إنشاء الطلب</button>
                    <a href="{{ route('response.orders.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleInstallmentFields() {
            const paymentType = document.getElementById('paymentType').value;
            const installmentFields = document.getElementById('installmentFields');
            installmentFields.style.display = paymentType === 'installment' ? 'block' : 'none';
        }
    </script>
@endsection
