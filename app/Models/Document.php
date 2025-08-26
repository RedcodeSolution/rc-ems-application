<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'document_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'document_id', 'employee_id', 'doc_path', 'version',
        'review_date', 'access_permission', 'project_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

