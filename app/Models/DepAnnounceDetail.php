<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepAnnounceDetail extends Model
{
    use HasFactory;

    protected $table = 'dep_announce_details';

    protected $fillable = [
        'department_id',
        'announcement_id',
    ];
}
