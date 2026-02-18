<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $primaryKey = 'super_admin_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'super_admin_id',
        'super_admin_name',
        'super_admin_email',
        'password',
        'permissions',
        'status',
        'role'
    ];

    public function reports() {
        return $this->hasMany(Report::class, 'super_admin_id');
    }
}
