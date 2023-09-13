<?php

namespace App\Interfaces;

use App\Models\PaySlip;
use Carbon\Carbon;

interface PaySlipRepositoryInterface
{
    public function getSalaryByDate(Carbon $date): ?PaySlip;
    public function create(array $payloads): PaySlip;
}
