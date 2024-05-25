<?php

namespace App\Http\Controllers\Api\V1\Swagger;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Endless Profile API documentation",
 *     version="1.0.0"
 * ),
 * @OA\PathItem(
 *     path="/api/v1/"
 * ),
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer"
 *     )
 * )
 *
 */
class MainSwaggerController extends Controller
{
}
