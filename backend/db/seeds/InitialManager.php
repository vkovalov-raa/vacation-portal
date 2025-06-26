<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class InitialManager extends AbstractSeed
{
    public function run(): void
    {
        $password = password_hash('Secret123', PASSWORD_DEFAULT);

        $this->insert('users', [
            'name'  => 'Admin Manager',
            'email' => 'admin@company.test',
            'employee_code' => 'ADM-001',
            'password_hash' => $password,
            'role'  => 'manager',
        ]);

        $this->insert('users', [
            'name'  => 'Employee 1',
            'email' => 'employee1@company.test',
            'employee_code' => 'USR-001',
            'password_hash' => $password,
            'role'  => 'employee',
        ]);

        $this->insert('users', [
            'name'  => 'Employee 2',
            'email' => 'employee2@company.test',
            'employee_code' => 'USR-002',
            'password_hash' => $password,
            'role'  => 'employee',
        ]);
    }
}
