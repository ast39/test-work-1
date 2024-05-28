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
 *    schema="OptionResource",
 *    title="Карточка опции",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Аббревиатура", property="abbr", type="string", example="Test"),
 *    @OA\Property(title="Заголовок", property="title", type="string", example="Test"),
 *    @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00")
 *  )
 */
class OptionResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'abbr' => $this->abbr ?? null,
            'title' => $this->title ?? null,
            'value' => $this->pivot->value ?? null,
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
        ];
    }
}
