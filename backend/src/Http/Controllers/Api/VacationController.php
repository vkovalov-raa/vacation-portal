<?php
namespace App\Http\Controllers\Api;

use App\Services\VacationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VacationController
{
    public function __construct(private VacationService $svc) {}

    /* GET /api/vacations */
    public function index(): JsonResponse
    {
        $userId = $GLOBALS['auth_user']['sub'];
        return new JsonResponse($this->svc->listForUser($userId));
    }

    /* POST /api/vacations */
    public function store(Request $req): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        $userId = $GLOBALS['auth_user']['sub'];

        $id = $this->svc->create($userId, $data['start_date'], $data['end_date'], $data['reason'] ?? null);

        return new JsonResponse(['id' => $id], 201);
    }

    /* GET /api/manager/vacations */
    public function all(): JsonResponse
    {
        return new JsonResponse($this->svc->listAll());
    }

    /* PATCH /api/manager/vacations/{id} */
    public function updateStatus(Request $req, int $id): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        $this->svc->setStatus($id, $data['status']);
        return new JsonResponse(['status' => 'ok']);
    }
}
