<?php

namespace App\Modules\Driver\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Driver\DTO\DriverDTO;
use App\Modules\Driver\Services\DriverService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="User Api",
 *      description="User API Description"
 * )
 */

class DriverController extends Controller
{
    public function __construct(public DriverService $userService)
    {}

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Получить список всех пользователей",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Список пользователей",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = $this->userService->index();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
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
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $user = $this->userService->show($id);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
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
     *             @OA\Property(property="user", ref="#/components/schemas/User")
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

        $userDto = new DriverDTO($validated);

        $user = $this->userService->store($userDto);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
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
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = $this->userService->update($id, $validated);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
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
        $this->userService->destroy($id);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
