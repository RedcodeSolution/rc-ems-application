<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'employee_name',
        'employee_type',
        'employee_status',
        'contact_no',
        'email',
        'department_id',
        'admin_id',
        'paid_status',
        'role',
        'profile_photo'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function superAdmin()
    {
        return $this->hasOne(SuperAdmin::class, 'employee_id', 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'employee_announcement_details', 'employee_id', 'announcement_id');
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'employee_notifications', 'employee_id', 'notifi_id', 'employee_id', 'notifi_id');
    }
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'employee_team', 'employee_id', 'team_id')->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project', 'employee_id', 'project_id')
            ->withPivot('role_in_project', 'assigned_date')
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(EmployeeRating::class, 'employee_id', 'employee_id');
    }

    public function skills()
    {
        return $this->hasMany(\App\Models\EmployeeSkill::class, 'employee_id', 'employee_id');
    }
}
