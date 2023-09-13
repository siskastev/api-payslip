<?php

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

