<?php


namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="MiseAuVert API",
 *     version="1.0.0",
 *     description="API documentation for MiseAuVert"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Entrez le token obtenu via /api/auth/login"
 * )
 */
class Swagger
{
    // This class is only for annotations
}
