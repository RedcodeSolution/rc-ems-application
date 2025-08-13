<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $primaryKey = 'super_admin_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['super_admin_id', 'super_admin_name'];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'super_admin_id');
    }
}

