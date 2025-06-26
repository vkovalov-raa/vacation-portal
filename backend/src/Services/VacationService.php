<?php
namespace App\Services;

use PDO;

class VacationService
{
    public function __construct(private PDO $db) {}

    public function listForUser(int $userId): array
    {
        return $this->db->query(
            'SELECT * FROM vacation_requests WHERE user_id = '.$userId.' ORDER BY created_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $userId, string $start, string $end, ?string $reason): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO vacation_requests (user_id,start_date,end_date,reason) VALUES (?,?,?,?)'
        );
        $stmt->execute([$userId, $start, $end, $reason]);
        return (int)$this->db->lastInsertId();
    }

    public function listAll(): array
    {
        return $this->db->query(
            'SELECT v.*, u.name AS user_name FROM vacation_requests v 
             JOIN users u ON u.id = v.user_id ORDER BY v.created_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, ['approved', 'rejected'], true)) {
            throw new \InvalidArgumentException('Bad status');
        }
        $stmt = $this->db->prepare('UPDATE vacation_requests SET status=? WHERE id=?');
        $stmt->execute([$status, $id]);
    }
}
