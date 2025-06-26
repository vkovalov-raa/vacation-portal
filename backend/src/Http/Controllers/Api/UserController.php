<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function __construct(private UserService $svc) {}

    public function index(): JsonResponse
    {
        return new JsonResponse($this->svc->all());
    }

    public function store(Request $r): JsonResponse
    {
        $data = json_decode($r->getContent(), true);
        $id   = $this->svc->create($data);
        return new JsonResponse(['id'=>$id], 201);
    }

    public function update(Request $r, int $id): JsonResponse
    {
        $this->svc->update($id, json_decode($r->getContent(), true));
        return new JsonResponse(['status'=>'ok']);
    }

    public function destroy(Request $r, int $id): JsonResponse
    {
        $this->svc->delete($id);
        return new JsonResponse(['status'=>'deleted']);
    }
}