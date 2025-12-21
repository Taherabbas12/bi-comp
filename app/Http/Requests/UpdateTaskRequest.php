<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $taskId = $this->route('task')->id;

        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date|before_or_equal:due_date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'assigned_to_user_id' => 'sometimes|required|exists:users,id',
            'supervisor_user_id' => 'nullable|exists:users,id',
            'priority_id' => 'sometimes|required|exists:priorities,id',
            'status_id' => 'sometimes|required|exists:task_statuses,id',
            'progress_percentage' => 'sometimes|integer|min:0|max:100',
            'score' => 'nullable|integer|min:1|max:10',
            'outcome_rating' => 'nullable|integer|min:0|max:100',

        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'حقل العنوان مطلوب.',
            'assigned_to_user_id.required' => 'يجب تحديد موظف لتنفيذ المهمة.',
            'assigned_to_user_id.exists' => 'الموظف المحدد غير موجود.',
            'progress_percentage.integer' => 'نسبة الإنجاز يجب أن تكون عددًا.',
            'progress_percentage.min' => 'نسبة الإنجاز لا يمكن أن تكون أقل من 0.',
            'progress_percentage.max' => 'نسبة الإنجاز لا يمكن أن تزيد عن 100.',
        ];
    }
}
