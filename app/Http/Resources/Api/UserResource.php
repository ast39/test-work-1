<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


/**
 * Transform the resource into an array.
 *
 * @OA\Schema(
 *   type="object",
 *   schema="UserResource",
 *   title="Карточка пользователя",
 *   @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *   @OA\Property(title="ФИО", property="name", type="string", example="Test User"),
 *   @OA\Property(title="Логин", property="email", type="string", example="test@test.com"),
 *   @OA\Property(title="Статус", property="status", type="integer", example="1"),
 *   @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *   @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00"),
 * )
 */
class UserResource extends ApiResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
