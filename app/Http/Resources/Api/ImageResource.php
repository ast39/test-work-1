<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * Transform the resource into an array.
 *
 * @OA\Schema(
 *    type="object",
 *    schema="ImageResource",
 *    title="Карточка изображения",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Каталог", property="path", type="string", example="avatar"),
 *    @OA\Property(title="Имя файла", property="filename", type="string", example="test"),
 *    @OA\Property(title="Расширение файла", property="ext", type="string", example="png"),
 *    @OA\Property(title="URL для получения", property="url", type="string", example="https://site.com/test.png"),
 *    @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00")
 *  )
 */
class ImageResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'path' => $this->path ?? null,
            'filename' => $this->filename ?? null,
            'ext' => $this->ext ?? null,
            'url' => $this->url,
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
        ];
    }
}
