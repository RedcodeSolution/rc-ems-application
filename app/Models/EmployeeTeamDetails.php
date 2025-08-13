<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTeamDetails extends Model
{
    protected $table = 'employee_team'; // specify the pivot table name
    public $timestamps = true; // if your pivot table has timestamps

    protected $fillable = ['employee_id', 'team_id'];
}
