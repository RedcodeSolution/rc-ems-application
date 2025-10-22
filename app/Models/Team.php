<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';
    protected $primaryKey = 'team_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'team_name',
        'department_id',
        'team_lead',
        'max_team_size',
        'monthly_budget',
        'team_status',
        'team_priority',
        'work_mode',
        'team_description',
        'team_goals',
        'skills_required'
    ];

    /** ✅ Relationship: Team belongs to a Department */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /** ✅ Relationship: One Team has many Projects */
    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id', 'team_id');
    }

    /** ✅ Relationship: Many Employees belong to many Teams */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_team', 'team_id', 'employee_id');
    }


    /** ✅ Relationship: A team lead is one employee */
    public function teamLead()
    {
        return $this->belongsTo(Employee::class, 'team_lead', 'employee_id');
    }
}
