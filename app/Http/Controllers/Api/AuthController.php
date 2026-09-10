<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Authenticate user and issue API token.
     */
    public function login(LoginRequest $request, LoginAction $loginAction): JsonResponse
    {
        $result = $loginAction->execute($request->validated());

        return response()->json([
            'message' => 'Connexion réussie.',
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => $result['user']->role->value,
                'organization_id' => $result['user']->organization_id,
            ],
        ]);
    }

    /**
     * Revoke current user API token.
     */
    public function logout(Request $request, LogoutAction $logoutAction): JsonResponse
    {
        $logoutAction->execute($request->user());

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }

    /**
     * Retrieve authenticated user profile with organization.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('organization'),
        ]);
    }
}
