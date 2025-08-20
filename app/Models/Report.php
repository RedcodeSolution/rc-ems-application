<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'report_id', 
        'report_name', 
        'super_admin_id',
        'report_type',
        'format',
        'file_path',
        'file_size',
        'status',
        'generated_by',
        'start_date',
        'end_date',
        'description',
        'special_instructions',
        'priority'
    ];

    public function superAdmin() {
        return $this->belongsTo(SuperAdmin::class, 'super_admin_id');
    }
}

