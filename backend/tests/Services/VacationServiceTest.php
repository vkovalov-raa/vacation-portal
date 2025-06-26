<?php

use App\Services\MailerService;
use App\Services\VacationService;
use PHPUnit\Framework\TestCase;

class VacationServiceTest extends TestCase
{
    private PDO $db;
    private VacationService $svc;

    protected function setUp(): void
    {
        $this->db  = $GLOBALS['pdo_test'];
        $mailer = $this->createMock(MailerService::class);
        $mailer->expects($this->once())
            ->method('notify');

        $this->svc = new VacationService($this->db, $mailer);
    }

    public function testCreate(): void
    {
        $id = $this->svc->create(2,'2025-07-01','2025-07-05',null);

        $row = $this->db->query("SELECT * FROM vacation_requests WHERE id=$id")
            ->fetch(PDO::FETCH_ASSOC);

        $this->assertSame('pending', $row['status']);
        $this->assertSame('2025-07-01', $row['start_date']);
    }
}
