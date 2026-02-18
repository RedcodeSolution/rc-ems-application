<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'type',
        'description',
        'date',
        'time',
        'duration',
        'status',
        'location',
        'attendees',
        'organizer',
        'agenda',
        'requirements',
        'contact_email',
        'contact_phone'
    ];
}

