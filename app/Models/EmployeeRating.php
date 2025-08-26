<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRating extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'rating',
        'comment',
        'rated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}
