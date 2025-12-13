@extends('layouts.employee-layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">➕ إنشاء طلب جديد</h1>
                </div>
                <p class="text-muted mb-4">أدخل تفاصيل الطلب واسم الزبون</p>

                <div class="card text-white bg-dark border-light">
                    <div class="card-body">
                        <form action="{{ route('response.orders.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customerName" class="form-label">اسم الزبون *</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customerName" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customerPhone" class="form-label">رقم الهاتف *</label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customerPhone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="customerAddress" class="form-label">العنوان *</label>
                                    <textarea class="form-control @error('customer_address') is-invalid @enderror" id="customerAddress" name="customer_address" rows="2" required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="source" class="form-label">من أين أتى الطلب؟ *</label>
                                    <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" required>
                                        <option value="">اختر...</option>
                                        <option value="facebook" {{ old('source') == 'facebook' ? 'selected' : '' }}>فيسبوك</option>
                                        <option value="instagram" {{ old('source') == 'instagram' ? 'selected' : '' }}>انستقرام</option>
                                        <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>آخر</option>
                                    </select>
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="paymentType" class="form-label">طريقة الدفع *</label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" id="paymentType" name="payment_type" required onchange="toggleInstallmentFields()">
                                        <option value="">اختر...</option>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>نقدًا</option>
                                        <option value="installment" {{ old('payment_type') == 'installment' ? 'selected' : '' }}>أقساط</option>
                                        <option value="points" {{ old('payment_type') == 'points' ? 'selected' : '' }}>نقاط</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="installmentFields" class="col-md-6" style="display: {{ old('payment_type') == 'installment' ? 'block' : 'none' }};">
                                    <label for="installmentMonths" class="form-label">مدة التقسيط *</label>
                                    <select class="form-select @error('installment_months') is-invalid @enderror" id="installmentMonths" name="installment_months">
                                        <option value="10" {{ old('installment_months') == '10' ? 'selected' : '' }}>10 أشهر</option>
                                        <option value="11" {{ old('installment_months') == '11' ? 'selected' : '' }}>11 أشهر</option>
                                    </select>
                                    @error('installment_months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="orderNotes" class="form-label">ملاحظات حول الطلب</label>
                                    <textarea class="form-control @error('order_notes') is-invalid @enderror" id="orderNotes" name="order_notes" rows="3">{{ old('order_notes') }}</textarea>
                                    @error('order_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="notes" class="form-label">ملاحظات إضافية</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- حقل اختيار الجهاز -->
                                <div class="col-md-12">
                                    <label for="laptopBarcode" class="form-label">الجهاز *</label>
                                    <select class="form-select @error('laptop_barcode') is-invalid @enderror" id="laptopBarcode" name="laptop_barcode" required>
                                        <option value="">اختر جهازًا...</option>
                                        @foreach ($laptops as $laptop)
                                            <option value="{{ $laptop->barcode }}" data-price="{{ $laptop->price_numeric }}" {{ old('laptop_barcode') == $laptop->barcode ? 'selected' : '' }}>
                                                {{ $laptop->name }} ({{ $laptop->brand }}) - {{ $laptop->price_display }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('laptop_barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-primary flex-fill me