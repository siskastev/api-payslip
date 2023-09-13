<?php

namespace App\Http\Controllers;

use App\Models\Presences;
use App\Http\Requests\PresencesRequest;
use App\Http\Requests\UpdatePresencesRequest;
use App\Services\PresencesService;


class PresencesController extends Controller
{
    private $presencesService;

    public function __construct(PresencesService $presencesService)
    {
        $this->presencesService = $presencesService;
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
     * @OA\Post(
     *     path="/api/presences",
     *     summary="Store a new presence",
     *     tags={"Presences"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     * )
     */
    public function store(PresencesRequest $request)
    {
        $payloads =  $request->all();

        $result =  $this->presencesService->save($payloads);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Failed to Presence In',
                'error' => $result['message'],
            ], 500);
        }

        return response()->json([
            'message' => 'Success Presence In',
            'data' => $result['data'],
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presences  $Presences
     * @return \Illuminate\Http\Response
     */
    public function show(Presences $Presences)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Presences  $Presences
     * @return \Illuminate\Http\Response
     */
    public function edit(Presences $Presences)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePresencesRequest  $request
     * @param  \App\Models\Presences  $Presences
     * @return \Illuminate\Http\Response
     */
    public function update(PresencesRequest $request, Presences $Presences)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presences  $Presences
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presences $Presences)
    {
        //
    }
}
