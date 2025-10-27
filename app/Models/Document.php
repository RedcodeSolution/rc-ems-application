<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'document_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'document_id',
        'title',
        'description',
        'category',
        'department_id',
        'project_id',
        'access_level',
        'tags',
        'file_path',
        'notify_users',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    // Document.php
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
