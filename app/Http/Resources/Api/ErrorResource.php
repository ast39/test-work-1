<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *   type="object",
 *   schema="ErrorResource",
 *   title="Ответ с ошибкой",
 *   @OA\Property(title="Статус", property="status", type="boolean", format="int64", example="false"),
 *   @OA\Property(title="Код", property="code", type="integer", format="int64", example="500"),
 *   @OA\Property(title="Сообщение", property="msg", type="string", example="Test"),
 * )
 */
class ErrorResource extends JsonResource {

    public static $wrap = 'error';


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [

            'status' => false,
            'code' => $this->code ?? 500,
            'msg'  => $this->message ?? null,
        ];
    }

    public function jsonOptions()
    {
        return JSON_UNESCAPED_UNICODE;
    }
}
