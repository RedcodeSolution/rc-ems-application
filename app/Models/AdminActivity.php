<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'action',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
