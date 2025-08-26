<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $primaryKey = 'admin_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['admin_id', 'admin_name'];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employees() {
        return $this->hasMany(Employee::class, 'admin_id');
    }
}

