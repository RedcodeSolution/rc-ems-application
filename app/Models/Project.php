<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $primaryKey = 'project_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'project_name',
        'description',
        'client',
        'team_id',
        'status',
        'start_date',
        'end_date',
        'milestone_info',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project', 'project_id', 'employee_id')
            ->withPivot('role', 'status', 'assigned_date', 'progress', 'deadline');
    }






}

