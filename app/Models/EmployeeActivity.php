<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'icon',
        'action',
        'details',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
