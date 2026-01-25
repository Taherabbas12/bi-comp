<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class UserAttachment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'attachment_type',
        'description',
        'is_primary',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_primary' => 'boolean',
    ];

    // العلاقة مع الموظف
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الحصول على حجم الملف بصيغة مقروءة
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    // الحصول على نوع المرفق بصيغة عربية
    public function getAttachmentTypeNameAttribute()
    {
        $types = [
            'id_card' => 'بطاقة هوية',
            'passport' => 'جواز سفر',
            'driver_license' => 'رخصة قيادة',
            'birth_certificate' => 'شهادة ميلاد',
            'profile_picture' => 'صورة شخصية',
            'contract' => 'العقد الوظيفي',
            'cv' => 'السيرة الذاتية',
            'certificate' => 'شهادات أكاديمية',
            'insurance' => 'وثائق التأمين',
            'bank_account' => 'بيانات الحساب البنكي',
            'other' => 'أخرى',
        ];

        return $types[$this->attachment_type] ?? 'غير معروف';
    }

    // التحقق من أنها صورة
    public function isImage()
    {
        $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($this->mime_type, $imageMimes);
    }
}
