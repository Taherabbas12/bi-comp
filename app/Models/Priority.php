<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priority extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;     // مهم
    protected $keyType = 'string';    // مهم جداً لأن UUID ليست int

    protected $fillable = ['name', 'display_name', 'color_code'];

    // علاقة: الأولوية ترتبط بالعديد من المهام
    public function tasks()
    {
        return $this->hasMany(Task::class, 'priority_id');
    }
}
