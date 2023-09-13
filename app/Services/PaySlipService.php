<?php

namespace App\Services;

use App\Interfaces\PaySlipServiceInterface;
use App\Models\PaySlip;
use App\Repositories\PaySlipRepository;
use App\Repositories\PresencesRepository;
use Carbon\Carbon;
use Exception;

class PaySlipService implements PaySlipServiceInterface
{

    private $presencesRepository;
    private $paySlipRepository;

    private const BASIC_SALARY = 2000000;
    private const ALLOWANCE = 15000;
    private const AMOUNT_LATE_DEFAULT_15_MINUTES = 5000;

    public function __construct(PaySlipRepository $paySlipRepository, PresencesRepository $presencesRepository)
    {
        $this->presencesRepository = $presencesRepository;
        $this->paySlipRepository = $paySlipRepository;
    }

    public function getSalaryByDate(Carbon $date)
    {
        return $this->paySlipRepository->getSalaryByDate($date);
    }

    public function save(Carbon $date)
    {
        try {
            $year = $date->year;
            $month = $date->month;

            $basicSalary = self::BASIC_SALARY;

            $resultCalculate = $this->calculateAllowanceAndLateAttendance($year, $month);

            if (empty($resultCalculate)) {
                return [
                    'success' => true,
                    'data' => []
                ];
            }

            // Calculate total salary
            $totalSalary = $basicSalary + $resultCalculate['total_allowance'] - $resultCalculate['total_late_attendance'];

            $payslip = $this->paySlipRepository->create([
                'basic_salary' => $basicSalary,
                'salary_date' => $date,
                'allowance' => $resultCalculate['total_allowance'],
                'late_attendance' => $resultCalculate['total_late_attendance']
            ]);

            $payslip['take_home_pay'] = $totalSalary;

            return [
                'success' => true,
                'data' => $payslip,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function getLastMinutes(Carbon $dateTime)
    {
        $workingHoursStart = $dateTime->copy()->setTime(8, 0, 0);
        return abs($dateTime->diffInMinutes($workingHoursStart, false));
    }

    private function calculateAllowanceAndLateAttendance(int $year, int $month): array
    {
        $totalAllowance = 0;
        $totalLateAttendanceDeduction = 0;
        $dailyPerformanceAllowance = self::ALLOWANCE;
        $lateAttendanceDeduction15 = self::AMOUNT_LATE_DEFAULT_15_MINUTES;

        $presences = $this->presencesRepository->getAllPresencesByDate($year, $month)->all();

        if (empty($presences)) {
            return [];
        }

        foreach ($presences as $presence) {
            $clockIn = Carbon::parse($presence->date . ' ' . $presence->clock_in);
            $clockOut = $presence->clock_out;

            if ($clockIn->year === $year && $clockIn->month === $month && $clockIn->isWeekday()) {
                $lateMinutes = $this->getLastMinutes($clockIn);

                if ($lateMinutes >= 60 || !$clockOut) {
                    // No Performance Allowance and no Late Attendance Deduction
                    // more than 1 hour and no checkout
                    $totalAllowance += 0;
                } elseif ($lateMinutes >= 15 && $lateMinutes < 60 && $clockIn && $clockOut) {
                    // Performance Allowance for the day, Late Attendance Deduction of 5,000
                    // if more than 15 minutes and less 1 hour and already clockin and already clockout
                    $totalAllowance += $dailyPerformanceAllowance;
                    $additionalIntervals = floor($lateMinutes / 15);
                    $totalLateAttendanceDeduction += $additionalIntervals * $lateAttendanceDeduction15;
                } else {
                    // Performance Allowance for the day, No Late Attendance Deduction
                    // if no late attendance and clock out
                    $totalAllowance += $dailyPerformanceAllowance;
                }
            }
        }

        return [
            'total_allowance' => $totalAllowance,
            'total_late_attendance' => $totalLateAttendanceDeduction
        ];
    }
}
