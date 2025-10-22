<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $primaryKey = 'announcement_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'priority',
        'category',
        'content',
        'expires_at',
        'target_audience',
        'status',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'expires_at' => 'datetime',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'dep_announce_details', 'announcement_id', 'department_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_announce_details', 'announcement_id', 'team_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_announcement_details', 'announcement_id', 'employee_id')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
