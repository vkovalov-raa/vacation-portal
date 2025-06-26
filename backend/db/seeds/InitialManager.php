<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class InitialManager extends AbstractSeed
{
    public function run(): void
    {
        $password = password_hash('secret123', PASSWORD_DEFAULT);

        $this->insert('users', [
            'name'  => 'Admin Manager',
            'email' => 'admin@company.test',
            'employee_code' => 'ADM-001',
            'password_hash' => $password,
            'role'  => 'manager',
        ]);
    }
}
