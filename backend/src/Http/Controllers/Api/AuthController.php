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
        return new JsonResponse($GLOBALS['auth_user']);
    }
}