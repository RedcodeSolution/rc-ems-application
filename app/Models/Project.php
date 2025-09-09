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
        'status',
        'start_date',
        'end_date',
        'milestone_info',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'project_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project', 'project_id', 'employee_id')
            ->withPivot('role_in_project', 'assigned_date')
            ->withTimestamps();
    }
}

