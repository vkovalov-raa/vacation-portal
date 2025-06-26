<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersAndVacations extends AbstractMigration
{
    public function change(): void
    {
        // users
        $this->table('users', ['id' => 'id', 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('employee_code', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('role', 'enum', ['values' => ['manager', 'employee'], 'default' => 'employee'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update'  => 'CURRENT_TIMESTAMP',
            ])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['employee_code'], ['unique' => true])
            ->create();

        // vacation_requests
        $this->table('vacation_requests', ['id' => 'id', 'signed' => false])
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('start_date', 'date')
            ->addColumn('end_date', 'date')
            ->addColumn('reason', 'text', ['null' => true])
            ->addColumn('status', 'enum', [
                'values' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update'  => 'CURRENT_TIMESTAMP',
            ])
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->addIndex(['user_id'])
            ->addIndex(['status'])
            ->create();
    }
}
