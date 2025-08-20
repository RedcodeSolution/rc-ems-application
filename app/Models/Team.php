<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $primaryKey = 'team_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['team_id', 'team_name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_team', 'team_id', 'employee_id')->withTimestamps();
    }

}

