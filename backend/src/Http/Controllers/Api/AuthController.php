<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
    public function __construct(private AuthService $auth) {}

    public function login(Request $req): JsonResponse
    {
        $data = json_decode($req->getContent(), true) ?? [];
        $info = $this->auth->attempt($data['email'] ?? '', $data['password'] ?? '');

        return $info
            ? new JsonResponse($info)
            : new JsonResponse(['message' => 'Invalid credentials'], 401);
    }

    public function me(): JsonResponse
    {
        $payload = $GLOBALS['auth_user'] ?? null;
        if (!$payload) {
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }

        $user = $this->auth->findUser($payload['sub']);
        return $user
            ? new JsonResponse($user)
            : new JsonResponse(['message' => 'User not found'], 404);
    }
}