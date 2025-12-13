<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'work_date',
        'check_in_at',
        'check_out_at',
        'ip_address',
        'lat',
        'lng',
        'is_inside_office',
        'source',
        'distance_meters',
    ];

    protected $casts = [
        'work_date'    => 'date',
        'check_in_at'  => 'datetime',
        'check_out_at' => 'datetime',
        'is_inside_office' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // مدة هذه الجلسة بالدقائق
    public function getSessionMinutesAttribute(): int
    {
        if (! $this->check_in_at || ! $this->check_out_at) {
            return 0;
        }

        return $this->check_in_at->diffInMinutes($this->check_out_at);
    }
}