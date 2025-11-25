<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePriorityRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or check permission
    }

    public function rules()
    {
        $priorityId = $this->route('priority')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('priorities', 'name')->ignore($priorityId)],
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
