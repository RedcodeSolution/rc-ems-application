<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $primaryKey = 'announcement_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'priority',
        'category',
        'content',
        'expires_at',
        'target_audience',
        'status',
        'department_id'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'expires_at' => 'datetime'
    ];


    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'dep_announce_details', 'department_id', 'announcement_id');
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_announcement_details', 'announcement_id', 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'dep_announce_details', 'announcement_id', 'department_id');
    }


    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_announce_details', 'announcement_id', 'team_id');
    }
}
