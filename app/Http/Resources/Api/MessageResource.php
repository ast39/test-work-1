<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


/**
 * Transform the resource into an array.
 *
 * @OA\Schema(
 *   type="object",
 *   schema="MessageResource",
 *   title="Простой ответ",
 *   @OA\Property(title="Статус", property="status", type="boolean", example="true"),
 *   @OA\Property(title="Сообщение", property="msg", type="string", example="Test"),
 * )
 */
class MessageResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [
            'status' => true,
        ];
    }

    public function jsonOptions()
    {
        return JSON_UNESCAPED_UNICODE;
    }
}
