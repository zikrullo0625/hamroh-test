<?php

namespace App\Modules\Ride\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ride\DTO\RideDTO;
use App\Modules\Ride\Services\RideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="User Api",
 *      description="User API Description"
 * )
 */

class RideController extends Controller
{
    public function __construct(public RideService $rideService)
    {}

    /**
     * @OA\Get(
     *     path="/api/rides",
     *     summary="Получить список всех пользователей",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Список пользователей",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="rides",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $rides = $this->rideService->index();

        return response()->json([
            'success' => true,
            'rides' => $rides,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/rides/{id}",
     *     summary="Получить пользователя по ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о пользователе",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="ride", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $ride = $this->rideService->show($id);

        return response()->json([
            'success' => true,
            'ride' => $ride,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/rides",
     *     summary="Создать нового пользователя",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Abdu"),
     *             @OA\Property(property="email", type="string", format="email", example="abdu@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Созданный пользователь",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="ride", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'passenger_id'   => 'integer|nullable',
            'distance'       => 'required|numeric',
            'duration'       => 'required|numeric',
            'geometry'       => 'required|array',
            'price'          => 'required|integer',
            'from'           => 'required|array',
            'to'             => 'required|array',
            'from_address'   => 'string|nullable',
            'to_address'     => 'string|nullable',
        ]);

        $rideDto = RideDTO::fromArray($validated);

        $ride = $this->rideService->store($rideDto);

        return response()->json([
            'success' => true,
            'ride' => $ride,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/rides/{id}",
     *     summary="Обновить пользователя по ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Abdu"),
     *             @OA\Property(property="email", type="string", format="email", example="abdu@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Обновленный пользователь",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="ride", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $ride = $this->rideService->update($id, $validated);

        return response()->json([
            'success' => true,
            'ride' => $ride,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/rides/{id}",
     *     summary="Удалить пользователя по ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Статус удаления",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->rideService->destroy($id);

        return response()->json([
            'success' => true,
        ]);
    }

    public function calculateRide(Request $request)
    {
        $ride = $this->rideService->calculateRide($request->start, $request->end);

        return response()->json([
            'success' => true,
            'ride' => $ride,
        ]);
    }

    public function changeStatus($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:created,accepted,in_progress,completed,cancelled',
        ]);

        $this->rideService->changeStatus($id, $validated['status']);

        return response()->json([
            'success' => true,
        ]);
    }

    public function rate($id, Request $request)
    {
        $validated = $request->validate([
            'comment' => 'string|nullable',
            'rating' => 'required|integer|between:1,5',
            'user_id' => 'required'
        ]);

        $this->rideService->rate($id, $validated['comment'], $validated['rating'], $validated['user_id']);

        return response()->json([
            'success' => true
        ]);
    }

    public function takeRide($id, Request $request)
    {
        $ride = $this->rideService->takeRide($id, $request->user()->id);

        return response()->json([
            'success' => true,
            'ride' => $ride
        ]);
    }
}
