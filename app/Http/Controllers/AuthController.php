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

final class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    ){}

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
