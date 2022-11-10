<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ContentConflictException;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="backend challenge - docs"
 * )
 */
final class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    ){}

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/register/{provider}",
     *  operationId="create account",
     *  summary="create account to access the system",
     *  @OA\Parameter(
     *         description="provider name(user, seller)",
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="person_type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="document_id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "name": "Tiago Ferreira",
     *                          "person_type": "fisical",
     *                          "document_id": "12345678901",
     *                          "email": "mail@mail.com",
     *                          "password": "1234678"
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  @OA\Response(response="422",
     *    description="Invalid provider",
     *  ),
     * )
     */
    public function register(RegisterUserRequest $request, string $provider): JsonResponse
    {
        try {
            $this->service->register($provider, $request->only([
                'name',
                'person_type',
                'document_id',
                'email',
                'password',
            ]));

            return response()->json([
                'message' => 'Successfully registration'
            ], 201);
        } catch (InvalidDataProviderException $e) {
            return response()->json([ 'message' => $e->getMessage() ], 422);
        }
    }

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/login/{provider}",
     *  operationId="login",
     *  summary="make login to get authenticated",
     *  @OA\Parameter(
     *         description="provider name(user, seller)",
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@mail.com", "password": "test123"}
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "mail@mail.com", "password": "1234678"}
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  @OA\Response(response="401",
     *    description="Invalid Credentials",
     *  ),
     *  @OA\Response(response="422",
     *    description="Invalid provider",
     *  ),
     * )
     */
    public function login(Request $request, string $provider): JsonResponse
    {
        try {
            $token = $this->service->login($provider, $request->only(['email', 'password']));

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } catch (InvalidDataProviderException $e) {
            return response()->json([ 'message' => $e->getMessage() ], 422);
        } catch (AuthorizationException $e) {
            return response()->json([ 'message' => $e->getMessage() ], 401);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], 400);
        }
    }

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/logout/{provider}",
     *  operationId="logout",
     *  summary="logout and revoke token",
     *  @OA\Parameter(
     *         description="provider name(user, seller)",
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\Response(response="200",
     *    description="Successfully logged out",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function logout(string $provider): JsonResponse
    {
        try {
            $this->service->logout($provider);

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], 400);
        }

        
    }
}
