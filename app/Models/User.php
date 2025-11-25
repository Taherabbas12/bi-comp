<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- إضافة الحذف الناعم
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens; // <-- Import Str

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // <-- إضافة SoftDeletes

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', // <-- إضافة 'id' إذا كنت ترغب في تعيينه يدويًا
        'name',
        'email',
        'password',
        'role_id', // <-- إضافة 'role_id' كحقل قابل للتعبئة
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // علاقة: المستخدم يملك العديد من الطلبات (الزبون)
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // علاقة: المستخدم يملك العديد من الطلبات (كموظف ردود)
    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    // علاقة: المستخدم يملك دورًا واحدًا (Many-to-One) <-- هذه العلاقة المطلوبة
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id'); // <-- تأكد من وجود role_id في جدول users
    }

    // علاقة: المستخدم يملك العديد من الأدوار (Many-to-Many) <-- إذا كنت بحاجة إليها لاحقًا
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_roles'); // <-- افترض وجود جدول وصلي user_roles
    // }

    // دالة للتحقق من صلاحية المستخدم (Will be used in middleware/policies)
    public function hasPermission($permissionName)
    {
        if (! $this->role) { // <-- الآن $this->role سيُرجع العلاقة
            return false; // <-- تغيير إلى false إذا لم يكن له دور
        }

        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    protected static function boot()
    {
        parent::boot(); // <-- استدعاء boot الأصلية

        // قبل حفظ النموذج (create أو update)
        static::creating(function ($model) {
            // إذا لم يكن 'id' مُعينًا، قم بتعيين UUID جديد
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // <-- قم بتعيين UUID هنا
            }
        });
    }
}
