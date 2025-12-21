<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or check permission
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date|before_or_equal:due_date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'assigned_to_user_id' => 'required|exists:users,id',
            'supervisor_user_id' => 'nullable|exists:users,id',

            'priority_id' => 'nullable|string|exists:priorities,id',
            'status_id' => 'nullable|string|exists:task_statuses,id',
            'score' => 'nullable|integer|min:1|max:10',           // ← جديد
            'outcome_rating' => 'nullable|integer|min:0|max:100',  // ← جديد


        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'حقل العنوان مطلوب.',
            'assigned_to_user_id.required' => 'يجب تحديد موظف لتنفيذ المهمة.',
            'assigned_to_user_id.exists' => 'الموظف المحدد غير موجود.',
            'start_date.before_or_equal' => 'يجب أن يكون تاريخ البدء قبل أو يساوي تاريخ الانتهاء.',
            'due_date.after_or_equal' => 'يجب أن يكون تاريخ الانتهاء بعد أو يساوي تاريخ البدء.',
        ];
    }
}
