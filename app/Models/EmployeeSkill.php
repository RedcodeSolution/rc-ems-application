<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    protected $table = 'employee_skill';

    protected $fillable = ['employee_id', 'skill_name', 'proficiency_level', 'category'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','employee_id');
    }
}
