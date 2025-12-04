<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'display_name', 'color_code', 'description'];
    public $incrementing = false;     // مهم
    protected $keyType = 'string';    // مهم جداً لأن UUID ليست int

    // علاقة: الحالة ترتبط بالعديد من المهام
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
