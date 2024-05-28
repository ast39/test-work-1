<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserWrongAuthDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Resources\Api\TokenResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use OpenApi\Annotations as OA;


class AuthController extends Controller {

    protected UserService $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *   path="/auth/login",
     *   operationId="auth.login",
     *   summary="Api: Авторизация",
     *   description="Получение персонального токена",
     *   tags={"Авторизация"},
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Учетные данные",
     *     @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", title="Логин (E-mail)", nullable="false", type="string"),
     *       @OA\Property(property="password", title="Пароль", nullable="false", type="string"),
     *       examples={
     *         @OA\Examples(example="Admin", summary="Admin", value={"email":"admin@test.com", "password":"admin"}),
     *         @OA\Examples(example="Moderator", summary="Moderator", value={"email":"employee@test.com", "password":"employee"}),
     *         @OA\Examples(example="User", summary="User", value={"email":"user1@test.com", "password":"user"})
     *       }
     *     ),
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Персональный токен", ref="#/components/schemas/TokenResource")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad Request",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=404, description="Not found",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=500, description="server not available",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = $this->userService->getByEmail($data['email']);

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new UserWrongAuthDataException();
            }

            $token = $user->createToken(
                'auth',
                ['*'],
                now()->addSeconds((int) env('TOKEN_LIFE_TIME', 3600))
            )->plainTextToken;

            DB::commit();

            return TokenResource::make($request)
                ->additional(['data' => ['token' => $token]])
                ->response()
                ->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Post(
     *   path="/auth/me",
     *   operationId="auth.me",
     *   summary="Api: Авторизованный пользователь",
     *   description="Информация об авторизованном пользователе",
     *   tags={"Авторизация"},
     *   security={{"sanctum": {} }},
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Пользователь", ref="#/components/schemas/UserResource")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad Request",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=404, description="Not found",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=500, description="server not available",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     * )
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            return UserResource::make($user)->response();
        } catch(\Exception $e) {
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Post(
     *   path="/auth/logout",
     *   operationId="auth.logout",
     *   summary="Api: Logout",
     *   description="Logout",
     *   tags={"Авторизация"},
     *   security={{"sanctum": {} }},
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Простой ответ", ref="#/components/schemas/MessageResource")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad Request",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=404, description="Not found",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=500, description="server not available",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();

            return MessageResource::make($request)
                ->additional(['data' => ['msg' => __('msg.auth.off')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
