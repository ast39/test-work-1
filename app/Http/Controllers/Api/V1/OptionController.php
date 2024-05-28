<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Option\OptionQueryRequest;
use App\Http\Requests\Api\Option\OptionStoreRequest;
use App\Http\Requests\Api\Option\OptionUpdateRequest;
use App\Http\Resources\Api\OptionResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\OptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class OptionController extends Controller {

    /**
     * @var OptionService
     */
    private OptionService $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    /**
     * Option list
     *
     * @OA\Get(
     *    path="/v1/option",
     *    operationId="v1.option.getList",
     *    tags={"Опции товаров"},
     *    summary="Api: Список опций",
     *    description="Список опций",
     *    security={{"sanctum": {} }},
     *
     *    @OA\Parameter(name="q", description="Поиск по совпадению", example="Test", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="status", description="Статус", in="query", required=false, allowEmptyValue=true, schema={"type": "integer", "enum": {1, 2}, "default": 1}),
     *    @OA\Parameter(name="page", description="Номер страницы", in="query", required=false, example="1", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="limit", description="Записей на страницу", in="query", required=false, example="10", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="order", description="Соротировка по полю", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"title", "status", "created"}, "default": "title"}),
     *    @OA\Parameter(name="reverse", description="Реверс сортировки", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"asc", "desc"}, "default": "asc"}),
     *
     *    @OA\Response(response=200, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Список опций", type="array", @OA\Items(ref="#/components/schemas/OptionResource"))
     *      )
     *    ),
     *    @OA\Response(response=400, description="Bad Request",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=401, description="Unauthenticated",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=500, description="server not available",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *  )
     */
    public function index(OptionQueryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $list = $this->optionService->index($data);

            DB::commit();

            return OptionResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Option By ID
     *
     * @OA\Get(
     *   path="/v1/option/{id}",
     *   operationId="v1.option.show",
     *   tags={"Опции товаров"},
     *   summary="Api: Просмотр отдельной опции",
     *   description="Просмотр отдельной опции",
     *   security={{"sanctum": {} }},
     *
     *   @OA\Parameter(name="id", description="ID опции", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Опция", ref="#/components/schemas/OptionResource"),
     *       examples={
     *         @OA\Examples(example="Some option", summary="Some option",
     *           value={"id":1, "title":"Test", "status":1, "created": "2024-03-01 11:00:00", "updated": "2024-03-01 11:00:00"}
     *         )
     *       }
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
    public function show(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $item = $this->optionService->show($id);

            DB::commit();

            return OptionResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Option
     *
     * @OA\Post(
     *    path="/v1/option",
     *    operationId="v1.option.store",
     *    tags={"Опции товаров"},
     *    summary="Api: Добавить опцию",
     *    description="Добавить опцию",
     *    security={{"sanctum": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные новой опции",
     *      @OA\JsonContent(
     *        required={"title"},
     *        @OA\Property(property="abbr",  title="Аббревиатура", nullable="false", example="Test", type="string"),
     *        @OA\Property(property="title",  title="Заголовок", nullable="false", example="Test", type="string"),
     *        examples={
     *          @OA\Examples(example="New option", summary="New option", value={"title":"Test 1"}),
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Опция", ref="#/components/schemas/OptionResource")
     *      )
     *    ),
     *    @OA\Response(response=400, description="Bad Request",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=401, description="Unauthenticated",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=500, description="server not available",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *  ),
     */
    public function store(OptionStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->optionService->store($data);

            DB::commit();

            return OptionResource::make($item)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update option
     *
     * @OA\Put(
     *     path="/v1/option/{id}",
     *     operationId="v1.option.update",
     *     tags={"Опции товаров"},
     *     summary="Api: Обновить опцию",
     *     description="Обновить опцию",
     *     security={{"sanctum": {} }},
     *
     *     @OA\Parameter(name="id", description="ID опции", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные опции",
     *       @OA\JsonContent(
     *         @OA\Property(property="abbr",  title="Аббревиатура", nullable="true", example="Test", type="string"),
     *         @OA\Property(property="title",  title="Заголовок", nullable="true", example="Test", type="string"),
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Опция", ref="#/components/schemas/OptionResource")
     *       )
     *     ),
     *     @OA\Response(response=400, description="Bad Request",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=404, description="Not found",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=500, description="server not available",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *   ),
     */
    public function update(OptionUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->optionService->update($id, $data);

            return OptionResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *   path="/v1/option/{id}",
     *   operationId="v1.option.destroy",
     *   summary="Api: Удаление опции",
     *   description="Удаление опции",
     *   tags={"Опции товаров"},
     *   security={{"sanctum": {} }},
     *
     *   @OA\Parameter(name="id", description="ID опции", in="path", required=true, example="1", @OA\Schema(type="integer")),
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
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->optionService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => __('msg.option.deleted')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
