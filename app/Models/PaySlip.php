<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_salary',
        'salary_date',
        'allowance',
        'late_attendance',
    ];
}
