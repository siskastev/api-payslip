<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presences extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'clock_in',
        'clock_out'
    ];
}
