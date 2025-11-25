<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or check permission
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:task_statuses,name',
            'display_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.unique' => 'الاسم مستخدم مسبقًا.',
            'display_name.required' => 'حقل الاسم المعروض مطلوب.',
        ];
    }
}
