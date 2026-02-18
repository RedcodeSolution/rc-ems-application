<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdminActivity extends Model
{
    protected $fillable = [
        'super_admin_id',
        'type',
        'icon',
        'action',
        'details'
    ];
}

