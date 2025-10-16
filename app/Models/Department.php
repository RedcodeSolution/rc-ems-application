<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'department_name',
        'description',
        'employee_id',
        'location',
        'phone',
        'email',
        'budget',
        'status',
    ];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }

    // Get total number of employees in this department
    public function getTotalEmployeesAttribute()
    {
        return $this->employees()->count();
    }

    // Get active employees count
    public function getActiveEmployeesCountAttribute()
    {
        return $this->employees()->where('employee_status', 'Active')->count();
    }

    // Get inactive employees count
    public function getInactiveEmployeesCountAttribute()
    {
        return $this->employees()->where('employee_status', '!=', 'Active')->count();
    }

    // Get employees by status
    public function getEmployeesByStatus($status = 'Active')
    {
        return $this->employees()->where('employee_status', $status)->get();
    }

    // Check if department has employees
    public function hasEmployees()
    {
        return $this->employees()->exists();
    }

    // Get department capacity utilization (if you have a max capacity field)
    public function getCapacityUtilizationAttribute()
    {
        // You can add a max_capacity field to departments table if needed
        // return $this->max_capacity ? ($this->total_employees / $this->max_capacity) * 100 : 0;
        return null;
    }
    // In Employee.php
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    // Scope to get departments with employee counts
    public function scopeWithEmployeeCounts($query)
    {
        return $query->withCount([
            'employees',
            'employees as active_employees_count' => function ($query) {
                $query->where('employee_status', 'Active');
            },
            'employees as inactive_employees_count' => function ($query) {
                $query->where('employee_status', '!=', 'Active');
            }
        ]);
    }
}
