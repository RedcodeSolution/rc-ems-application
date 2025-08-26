<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $primaryKey = 'leave_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'leave_id', 'employee_id', 'leave_type', 'start_date', 'end_date',
        'duration', 'reason', 'contact_number', 'supporting_doc',
        'status', 'applied_date', 'approved_by', 'approved_date',
        'rejected_by', 'rejected_date', 'rejection_reason', 'comments'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applied_date' => 'datetime',
        'approved_date' => 'datetime',
        'rejected_date' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(Admin::class, 'rejected_by');
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeLeaveType($query, $type)
    {
        return $query->where('leave_type', $type);
    }

    public function scopeEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Accessors
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    public function getFormattedLeaveTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->leave_type));
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary'
        ][$this->status] ?? 'secondary';
    }

    // Methods
    public function canBeEdited()
    {
        return $this->status === 'pending';
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Generate unique leave ID
    public static function generateLeaveId()
    {
        $year = date('Y');
        $prefix = 'LEV-' . $year . '-';

        $lastLeave = self::where('leave_id', 'like', $prefix . '%')
                         ->orderBy('leave_id', 'desc')
                         ->first();

        if ($lastLeave) {
            $lastNumber = (int) substr($lastLeave->leave_id, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leave) {
            if (!$leave->leave_id) {
                $leave->leave_id = self::generateLeaveId();
            }

            if (!$leave->applied_date) {
                $leave->applied_date = now();
            }

            if (!$leave->status) {
                $leave->status = 'pending';
            }
        });
    }
}

