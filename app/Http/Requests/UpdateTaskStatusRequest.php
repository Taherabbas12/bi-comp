<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or check permission
    }

    public function rules()
    {
        $statusId = $this->route('taskStatus')->id; // Note: route parameter is 'taskStatus'

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('task_statuses', 'name')->ignore($statusId)],
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
