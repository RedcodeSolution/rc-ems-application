<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'category', 'description',
        'file_name', 'file_path', 'file_type', 'file_size'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
