<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- إضافة الحذف الناعم

class Permission extends Model
{
    use HasFactory, SoftDeletes; // <-- إضافة SoftDeletes

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'name',
    ];

    // علاقة: الصلاحية تابعة لعدة أدوار (Many-to-Many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
