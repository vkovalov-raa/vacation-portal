<?php
namespace App\Services;

use PDO;

class VacationService
{
    public function __construct(
        private PDO $db,
        private MailerService $mail
    ) {}

    /**
     * @param  int $userId
     * @return array<int,array{id:int,user_id:int,start_date:string,end_date:string,reason:?string,status:string,created_at:string}>
     */
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

        $managers = $this->db->query(
            "SELECT email FROM users WHERE role = 'manager'"
        )->fetchAll(PDO::FETCH_COLUMN);

        if ($managers) {
            $subject = 'New vacation request';
            $html    = "<p>User <b>{$author['name']}</b> requested vacation "
                . "<b>$start – $end</b>.</p>";

            foreach ($managers as $email) {
                $this->mail->notify($email, $subject, $html);
            }
        }

        return (int)$this->db->lastInsertId();
    }

    /**
     * @return array<int,array>
     */
    public function listAll(): array
    {
        return $this->db->query(
            'SELECT v.*, u.name AS user_name FROM vacation_requests v
             JOIN users u ON u.id = v.user_id ORDER BY v.created_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @param string $status
     *
     * @throws \InvalidArgumentException
     */
    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, ['approved', 'rejected'], true)) {
            throw new \InvalidArgumentException('Bad status');
        }

        $this->db->prepare('UPDATE vacation_requests SET status=? WHERE id=?')
            ->execute([$status, $id]);

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
