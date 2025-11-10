<?php

namespace App\Modules\Passenger\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Passenger\DTO\PassengerDTO;
use App\Modules\Passenger\Services\PassengerService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="User Api",
 *      description="User API Description"
 * )
 */

class PassengerController extends Controller
{
    public function __construct(public PassengerService $passengerService)
    {}

    /**
     * @OA\Get(
     *     path="/api/passengers",
     *     summary="Получить список всех пользователей",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Список пользователей",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="passengers",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $passengers = $this->passengerService->index();

        return response()->json([
            'success' => true,
            'passengers' => $passengers,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/passengers/{id}",
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
     *             @OA\Property(property="passenger", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $passenger = $this->passengerService->show($id);

        return response()->json([
            'success' => true,
            'passenger' => $passenger,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/passengers",
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
     *             @OA\Property(property="passenger", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $passengerDto = new PassengerDTO($validated);

        $passenger = $this->passengerService->store($passengerDto);

        return response()->json([
            'success' => true,
            'passenger' => $passenger,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/passengers/{id}",
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
     *             @OA\Property(property="passenger", ref="#/components/schemas/User")
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

        $passenger = $this->passengerService->update($id, $validated);

        return response()->json([
            'success' => true,
            'passenger' => $passenger,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/passengers/{id}",
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
        $this->passengerService->destroy($id);

        return response()->json([
            'success' => true,
        ]);
    }

    public function actualRide(Request $request)
    {
        $ride = $this->passengerService->actualRide($request->user()->id);

        return response()->json([
            'success' => true,
            'ride' => $ride
        ]);
    }
}
