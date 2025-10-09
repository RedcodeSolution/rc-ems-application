<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $primaryKey = 'notifi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'notifi_id',
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'target',
        'reference_id',
        'reference_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            if (!$notification->notifi_id) {
                $notification->notifi_id = 'NOTI-' . date('Y') . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_notifications',
            'notifi_id',
            'employee_id',
            'notifi_id',
            'employee_id'
        );
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'reference_id', 'leave_id');
    }
}
