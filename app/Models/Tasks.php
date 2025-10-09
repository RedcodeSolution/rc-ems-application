<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{

    protected $primaryKey = 'task_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'assigned_by',
        'status',
        'progress',
        'personal_notes',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by', 'employee_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_task', 'task_id', 'employee_id')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id', 'task_id')->with('employee');
    }
}
