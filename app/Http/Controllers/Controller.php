<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations as OA;

# php artisan l5-swagger:generate


/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Test work 1",
 *   description="Test work of Rest Api by Laravel 11 platform",
 *   @OA\Contact(
 *     email="alexandr.statut@gmail.com",
 *     name="ASt"
 *   ),
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_DEV_HOST,
 *   description="Dev API server"
 * )
 * @OA\Server(
 *   url=L5_SWAGGER_PROD_HOST,
 *   description="Prod API server"
 * )
 *
 * @OA\SecurityScheme(
 *   type="http",
 *   description="Your token providing after auth in Api",
 *   name="Api Client",
 *   in="header",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   securityScheme="sanctum",
 * )
 *
 * @OA\ExternalDocumentation(
 *   description="Api Docs",
 *   url="https://127.0.0.1:8000/api/docs",
 * )
 *
 * @OA\Tag(
 *   name="Авторизация",
 *   description="Блок авторизации"
 * ),
 * @OA\Tag(
 *   name="Опции товаров",
 *   description="Блок опций товаров"
 * ),
 * @OA\Tag(
 *   name="Изображения",
 *   description="Блок изображений"
 * ),
 * @OA\Tag(
 *   name="Товары",
 *   description="Блок товаров"
 * ),
 */
abstract class Controller {

    use AuthorizesRequests;
}
