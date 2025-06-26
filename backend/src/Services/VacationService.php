<?php
namespace App\Services;

use App\Services\MailerService;
use PDO;

class VacationService
{
    public function __construct(
        private PDO $db,
        private MailerService $mail
    ) {}

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
        $userId = (int) $stmt->fetchColumn();

        /* notify manager */
        $author = $this->db->query("SELECT name,email FROM users WHERE id=$userId")
            ->fetch(PDO::FETCH_ASSOC);

        $this->mail->notify(
            'admin@company.test',
            'New vacation request',
            "<p>User <b>{$author['name']}</b> requested vacation <b>$start – $end</b>.</p>"
        );

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

//        $this->db->prepare('UPDATE vacation_requests SET status=? WHERE id=?')
//            ->execute([$status, $id]);

        /* notify employee */
        $row = $this->db->query(
            "SELECT v.start_date,v.end_date,u.email,u.name
             FROM vacation_requests v JOIN users u ON u.id = v.user_id
             WHERE v.id=$id"
        )->fetch(PDO::FETCH_ASSOC);

        $this->mail->notify(
            $row['email'],
            "Your vacation request is $status",
            "<p>Hello {$row['name']},<br>
             Your request <b>{$row['start_date']} – {$row['end_date']}</b> has been
             <b>$status</b>.</p>"
        );
    }
}
