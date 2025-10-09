<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id',
        'report_name',
        'report_type',
        'start_date',
        'end_date',
        'employee_id',
        'department_id',
        'report_format',
        'priority',
        'email',
        'description',
        'special_instructions',

    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function Project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

}

