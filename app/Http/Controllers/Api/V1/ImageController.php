<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Image\ImageStoreRequest;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\ImageResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;


class ImageController extends Controller {


    /**
     * @var ImageService
     */
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }


    /**
     * File By ID
     *
     * @OA\Get(
     *   path="/v1/image/{id}",
     *   operationId="v1.image.show",
     *   tags={"Изображения"},
     *   summary="Api: Получение отдельного изображения",
     *   description="Получение отдельного изображения",
     *   security={{"sanctum": {} }},
     *
     *   @OA\Parameter(name="id", description="ID товара", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Карточка изображения", ref="#/components/schemas/ImageResource"),
     *       examples={
     *         @OA\Examples(example="Some File", summary="Some File",
     *           value={"id":1, "path":"avatar", "filename":"test", "ext":"png", "created": "2024-03-01 11:00:00", "updated": "2024-03-01 11:00:00"}
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

            $image = $this->imageService->show($id);

            DB::commit();

            return ImageResource::make($image)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add File
     *
     * @OA\Post(
     *    path="/v1/image",
     *    operationId="v1.image.store",
     *    tags={"Изображения"},
     *    summary="Api: Загрузить изображение",
     *    description="Загрузить изображение",
     *    security={{"sanctum": {} }},
     *
     *    @OA\RequestBody(
     *      description="Данные загружаемого изображения",
     *      @OA\MediaType(mediaType="multipart/form-data",
     *        @OA\Schema(type="object",
     *          @OA\Property(property="path", title="Артикул", nullable="false", example="avatar", type="string"),
     *          @OA\Property(property="file", type="file", type="string", format="binary"),
     *        ),
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Карточка изображения", ref="#/components/schemas/ImageResource")
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
    public function store(ImageStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $image = $this->imageService->store($data);

            DB::commit();

            return ImageResource::make($image)->response()->setStatusCode(201);
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
     *   path="/v1/image/{id}",
     *   operationId="v1.iamge.destroy",
     *   summary="Api: Удаление изображения",
     *   description="Удаление изображения",
     *   tags={"Изображения"},
     *   security={{"sanctum": {} }},
     *
     *   @OA\Parameter(name="id", description="ID файла", in="path", required=true, example="1", @OA\Schema(type="integer")),
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

            $this->imageService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => __('msg.image.deleted')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
