<?php

namespace App\Http\Controllers;

use App\Models\PaySlip;
use App\Http\Requests\StorePaySlipRequest;
use App\Http\Requests\UpdatePaySlipRequest;
use App\Services\PaySlipService;
use Carbon\Carbon;

/**
 * @OA\Components(
 *     schemas={
 *         @OA\Schema(
 *             schema="PaySlipResponse",
 *             type="object",
 *             @OA\Property(property="month", type="string"),
 *             @OA\Property(
 *                 property="components",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="amount", type="integer")
 *                 )
 *             ),
 *             @OA\Property(property="take_home_pay", type="integer")
 *         )
 *     }
 * )
 */
class PaySlipController extends Controller
{

    private $paySlipService;

    public function __construct(PaySlipService $paySlipService)
    {
        $this->paySlipService = $paySlipService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/payslips/{id}",
     *     summary="Get a single payslip by ID",
     *     tags={"PaySlip"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the payslip to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaySlipResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payslip not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     * )
     */

    public function store(StorePaySlipRequest $request)
    {
        $filters = $request->only('month');

        $newDate = Carbon::parse($filters['month']);

        $resultSalary = $this->paySlipService->getSalaryByDate($newDate);
        if ($resultSalary) {
            $totalSalary = $resultSalary['basic_salary'] + $resultSalary['allowance'] - $resultSalary['late_attendance'];

            $resultSalary['take_home_pay'] = $totalSalary;

            $response = $this->mappingResponse($resultSalary, $filters['month']);

            return response()->json([
                'message' => 'Success Presence In',
                'data' => $response,
            ], 200);
        }

        $result =  $this->paySlipService->save($newDate);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Failed to Get PaySlips In',
                'error' => $result['message'],
            ], 500);
        }

        $response = $this->mappingResponse($result['data'], $filters['month']);

        return response()->json([
            'message' => 'Success Get PaySlips In',
            'data' => $response,
        ], 200);
    }

    private function mappingResponse($data, $month): array
    {
        if (empty($data)) {
            return [];
        }
        return [
            'month' => $month,
            'components' => [
                [
                    'name' => "Gaji Pokok",
                    'amount' => (int)$data['basic_salary']
                ],
                [
                    'name' => "Tunjangan Kinerja",
                    'amount' => (int)$data['allowance']
                ],
                [
                    'name' => "Potongan Keterlambatan",
                    'amount' => (int)$data['late_attendance'] === 0 ? 0 : (int)-$data['late_attendance']
                ],
            ],
            "take_home_pay" => (int)$data['take_home_pay']
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaySlip  $paySlip
     * @return \Illuminate\Http\Response
     */
    public function show(PaySlip $paySlip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaySlip  $paySlip
     * @return \Illuminate\Http\Response
     */
    public function edit(PaySlip $paySlip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaySlipRequest  $request
     * @param  \App\Models\PaySlip  $paySlip
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaySlipRequest $request, PaySlip $paySlip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaySlip  $paySlip
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaySlip $paySlip)
    {
        //
    }
}
