<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- إضافة الحذف الناعم

class Role extends Model
{
    use HasFactory, SoftDeletes; // <-- إضافة SoftDeletes

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'name',
    ];

    // علاقة: الدور يملك العديد من المستخدمين
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    // علاقة: الدور يملك العديد من الصلاحيات (Many-to-Many)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
