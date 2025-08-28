<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
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

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_team', 'team_id', 'employee_id')->withTimestamps();
    }
}
