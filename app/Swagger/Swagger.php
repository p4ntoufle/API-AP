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
 *
 * @OA\Schema(
 *     schema="Pension",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="ville", type="string"),
 *     @OA\Property(property="adresse", type="string"),
 *     @OA\Property(property="telephone", type="string"),
 *     @OA\Property(property="responsable", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Box",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="pension_id", type="integer"),
 *     @OA\Property(property="superficie", type="number", format="float"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="TypeGardiennage",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="pension_id", type="integer"),
 *     @OA\Property(property="libelle", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Tarif",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="pension_id", type="integer"),
 *     @OA\Property(property="type_gardiennage_id", type="integer"),
 *     @OA\Property(property="prix", type="number", format="float"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Swagger
{
    // This class is only for annotations
}
