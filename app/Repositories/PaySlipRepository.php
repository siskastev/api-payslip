<?php

namespace App\Repositories;

use App\Interfaces\PaySlipRepositoryInterface;
use App\Models\PaySlip;
use Carbon\Carbon;

class PaySlipRepository implements PaySlipRepositoryInterface
{
    public function getSalaryByDate(Carbon $date): ?PaySlip
    {
        return PaySlip::select([
            'salary_date',
            'basic_salary',
            'allowance',
            'late_attendance',
        ])->whereDate('salary_date', $date)->first();
    }

    public function create(array $payloads): PaySlip
    {
        return PaySlip::create($payloads);
    }
}
