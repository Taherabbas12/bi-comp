<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // يمكنك إضافة منطق تحقق هنا إذا أردت
    }

    public function rules(): array
    {
        return [
            'laptop_barcode' => 'required|exists:laptops,barcode', // تحقق من وجود الجهاز
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'order_notes' => 'nullable|string',
            'notes' => 'nullable|string', // <-- الحقل الجديد
            'source' => 'required|in:facebook,instagram,other',
            'payment_type' => 'required|in:cash,installment,points',
            'installment_months' => 'required_if:payment_type,installment|in:10,11',
            'employee_name' => 'nullable|string|max:255', // <-- يمكن حذفه إذا لم تستخدمه في هذه الوظيفة
        ];
    }

    public function messages(): array
    {
        return [
            'laptop_barcode.required' => 'رقم الباركود مطلوب.',
            'laptop_barcode.exists' => 'الجهاز المحدد غير موجود.',
            'customer_name.required' => 'اسم الزبون مطلوب.',
            'customer_phone.required' => 'رقم الهاتف مطلوب.',
            'customer_address.required' => 'العنوان مطلوب.',
            'source.required' => 'يرجى تحديد مصدر الطلب.',
            'payment_type.required' => 'يرجى تحديد طريقة الدفع.',
            'installment_months.required_if' => 'يرجى تحديد عدد أشهر التقسيط.',
            'installment_months.in' => 'عدد أشهر التقسيط يجب أن يكون 10 أو 11.',
            'employee_name.required' => 'اسم موظف الردود مطلوب.',
        ];
    }
}
