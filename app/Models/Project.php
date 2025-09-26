<?php

namespace App\Models;

use Illuminate\Console\View\Components\Task;
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

    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'project_id', 'project_id');
    }
}
