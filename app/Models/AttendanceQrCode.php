<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceQrCode extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'attendance_qr_codes';

    protected $fillable = ['id', 'code', 'is_active'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid();
            }
        });
    }
}