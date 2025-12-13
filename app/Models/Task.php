<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Task extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public $incrementing = false;     // مهم
    protected $keyType = 'string';    // مهم جداً لأن UUID ليست int

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'due_date',
        'progress_percentage',
        'assigned_to_user_id',
        'supervisor_user_id',
        'created_by_user_id',
        'priority_id',
        'status_id',
    ];

    // علاقة: المهمة مسند لمستخدم (Employee)
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    // علاقة: المهمة مسند مراقبتها لمستخدم (Supervisor)
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_user_id');
    }

    // علاقة: المهمة أنشأها مستخدم (Creator)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    // علاقة: المهمة لها أولوية
    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    // علاقة: المهمة لها حالة
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }
}
