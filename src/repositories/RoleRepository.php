<?php

require_once __DIR__ . '/../models/Role.php';

class RoleRepository
{
    public function __construct(private PDO $conn)
    {
    }

    /**
     * @return Role[]
     */
    public function findAll(): array
    {
        $stmt = $this->conn->query("SELECT id, nome_ruolo FROM ruoli ORDER BY nome_ruolo");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => Role::fromArray($row), $rows);
    }

    public function findById(int $id): ?Role
    {
        $stmt = $this->conn->prepare("SELECT id, nome_ruolo FROM ruoli WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return Role::fromArray($row);
    }
}
