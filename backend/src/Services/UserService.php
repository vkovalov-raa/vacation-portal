<?php

namespace App\Services;

use PDO;

class UserService
{
    public function __construct(private PDO $db) {}

    /**
     * @return array<int,array{id:int,name:string,email:string,employee_code:?string,role:string}>
     */
    public function all(): array
    {
        return $this->db->query('SELECT id,name,email,employee_code,role FROM users')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name,email,employee_code,password_hash,role)
                VALUES (:n,:e,:c,:p,:r)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'n'=>$data['name'],
            'e'=>$data['email'],
            'c'=>$data['employee_code'] ?? null,
            'p'=>password_hash($data['password'], PASSWORD_DEFAULT),
            'r'=>$data['role'] ?? 'employee',
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * @param int $id
     * @param array{
     *   name?:string,
     *   email?:string,
     *   password?:string,
     *   role?:string,
     *   employee_code?:string|null
     * } $data
     */
    public function update(int $id, array $data): void
    {
        $fields = [];
        $params = [];
        foreach (['name','email','employee_code','role'] as $f) {
            if (isset($data[$f])) { $fields[]="$f=:{$f}"; $params[$f]=$data[$f]; }
        }
        if (isset($data['password'])) {
            $fields[]='password_hash=:ph'; $params['ph']=password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $params['id']=$id;
        $sql = 'UPDATE users SET '.implode(',',$fields).' WHERE id=:id';
        $this->db->prepare($sql)->execute($params);
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
    }
}