<?php

namespace App\Interfaces;

use App\Models\PaySlip;
use Carbon\Carbon;

interface PaySlipServiceInterface
{
    public function getSalaryByDate(Carbon $date);
    public function save(Carbon $date);
}
