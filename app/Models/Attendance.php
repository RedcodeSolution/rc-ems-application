<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'hours_worked',
        'overtime_hours',
        'notes',
        'break_start_time',
        'break_end_time',
        'break_duration',
        'is_on_break',

        // ✅ Emergency-related fields
        'is_on_emergency',
        'emergency_type',
        'emergency_description',
        'emergency_start_time',
        'emergency_end_time',
        'emergency_duration',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
        'hours_worked' => 'decimal:2',
        'break_duration' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'is_on_break' => 'boolean',

        // ✅ Cast emergency timestamps and duration
        'is_on_emergency' => 'boolean',
        'emergency_start_time' => 'datetime',
        'emergency_end_time' => 'datetime',
        'emergency_duration' => 'decimal:2',
    ];

    /**
     * Get the employee that owns the attendance record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
