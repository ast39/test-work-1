<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Item\ItemQueryRequest;
use App\Http\Requests\Api\Item\ItemStoreRequest;
use App\Http\Requests\Api\Item\ItemUpdateRequest;
use App\Http\Resources\Api\ItemResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;


class ItemController extends Controller {

    /**
     * @var ItemService
     */
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Item list
     *
     * @OA\Get(
     *    path="/v1/item",
     *    operationId="v1.item.getList",
     *    tags={"Товары"},
     *    summary="Api: Список товаров",
     *    description="Список товаров",
     *    security={{"sanctum": {} }},
     *
     *    @OA\Parameter(name="q", description="Поиск по совпадению", example="Test", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="category", description="Поиск по категориям", example="1,2,3", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="status", description="Статус", in="query", required=false, allowEmptyValue=true, schema={"type": "integer", "enum": {1, 2}, "default": 1}),
     *    @OA\Parameter(name="page", description="Номер страницы", in="query", required=false, example="1", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="limit", description="Записей на страницу", in="query", required=false, example="10", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="order", description="Соротировка по полю", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"title", "status", "created"}, "default": "title"}),
     *    @OA\Parameter(name="reverse", description="Реверс сортировки", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"asc", "desc"}, "default": "asc"}),
     *
     *    @OA\Response(response=200, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Список товаров", type="array", @OA\Items(ref="#/components/schemas/ItemResource"))
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
    public function index(ItemQueryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $list = $this->itemService->index($data);

            DB::commit();

            return ItemResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Item By ID
     *
     * @OA\Get(
     *   path="/v1/item/{id}",
     *   operationId="v1.item.show",
     *   tags={"Товары"},
     *   summary="Api: Просмотр отдельного товара",
     *   description="Просмотр отдельного товара",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID товара", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/ItemResource"),
     *       examples={
     *         @OA\Examples(example="Some Item", summary="Some Item",
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

            $item =  $this->itemService->show($id);

            DB::commit();

            return ItemResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Item
     *
     * @OA\Post(
     *    path="/v1/item",
     *    operationId="v1.item.store",
     *    tags={"Товары"},
     *    summary="Api: Добавить товар",
     *    description="Добавить товар",
     *    security={{"sanctum": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные нового товара",
     *      @OA\JsonContent(
     *        required={"article", "title", "category_id", "price"},
     *        @OA\Property(property="article", title="Артикул", nullable="false", example="ABCDEF", type="string"),
     *        @OA\Property(property="title", title="Заголовок", nullable="false", example="Test", type="string"),
     *        @OA\Property(property="category_id", title="Категория", nullable="false", example="1", type="integer"),
     *        @OA\Property(property="price", title="Цена", nullable="false", example="499.90", type="decimal"),
     *        @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *        @OA\Property(property="images", title="Изображения", nullable="true", example="1,2", type="string"),
     *        examples={
     *          @OA\Examples(example="Active Item", summary="Active Item", value={"article":"ABCDEF", "title":"Test 1",
     *            "category_id": 1, "price":490.90, "status":1, "images":"1,2"}),
     *          @OA\Examples(example="Blocked Item", summary="Blocked Item", value={"article":"DFGERT","title":"Test 2",
     *            "category_id": 1, "price":590.90, "status":2}),
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/ItemResource")
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
    public function store(ItemStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->itemService->store($data);

            DB::commit();

            return ItemResource::make($item)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update Item
     *
     * @OA\Put(
     *     path="/v1/item/{id}",
     *     operationId="v1.item.update",
     *     tags={"Товары"},
     *     summary="Api: Обновить товар",
     *     description="Обновить товар",
     *     security={{"sanctum": {} }},
     *
     *     @OA\Parameter(name="id", description="ID товара", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные товара",
     *       @OA\JsonContent(
     *         @OA\Property(property="article", title="Артикул", nullable="true", example="ABCDEF", type="string"),
     *         @OA\Property(property="title", title="Заголовок", nullable="true", example="Test", type="string"),
     *         @OA\Property(property="category_id", title="Категория", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="price", title="Цена", nullable="true", example="499.90", type="decimal"),
     *         @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="images", title="Изображения", nullable="true", example="1,2", type="string")
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/ItemResource")
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
    public function update(ItemUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->itemService->update($id, $data);

            return ItemResource::make($item)->response();
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
     *   path="/v1/item/{id}",
     *   operationId="v1.item.destroy",
     *   summary="Api: Удаление товара",
     *   description="Удаление товара",
     *   tags={"Товары"},
     *   security={{"sanctum": {} }},
     *
     *   @OA\Parameter(name="id", description="ID товара", in="path", required=true, example="1", @OA\Schema(type="integer")),
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

            $this->itemService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => __('msg.item.deleted')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
