<?php

require_once __DIR__ . '/../models/User.php';

class UserRepository
{
    public function __construct(private PDO $conn)
    {
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $stmt = $this->conn->query("
            SELECT u.id, u.nome, u.cognome, u.email, u.telefono, u.ruolo_id, r.nome_ruolo AS ruolo_nome
            FROM utenti u
            LEFT JOIN ruoli r ON u.ruolo_id = r.id
            ORDER BY u.id DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => User::fromArray($row), $rows);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->conn->prepare("
            SELECT u.id, u.nome, u.cognome, u.email, u.telefono, u.ruolo_id, r.nome_ruolo AS ruolo_nome
            FROM utenti u
            LEFT JOIN ruoli r ON u.ruolo_id = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return User::fromArray($row);
    }

    public function findByEmail(string $email): ?PasswordUser
    {
        $stmt = $this->conn->prepare("
            SELECT u.id, u.nome, u.cognome, u.email, u.telefono, u.password, u.ruolo_id, r.nome_ruolo AS ruolo_nome
            FROM utenti u
            LEFT JOIN ruoli r ON u.ruolo_id = r.id
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return PasswordUser::fromArray($row);
    }

    public function insertOne(CreateUserDTO $user): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO utenti (nome, cognome, email, telefono, password, ruolo_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user->nome, $user->cognome, $user->email, $user->telefono, $user->password, $user->ruolo_id]);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT 1 FROM utenti WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        return (bool) $stmt->fetchColumn();
    }

    public function emailInUseByAnother(string $email, int $excludedUserId): bool
    {
        $stmt = $this->conn->prepare("SELECT 1 FROM utenti WHERE email = ? AND id <> ? LIMIT 1");
        $stmt->execute([$email, $excludedUserId]);

        return (bool) $stmt->fetchColumn();
    }

    public function updateOne(int $id, UpdateUserDTO $user, ?string $newPassword = null): bool
    {
        $fields = [
            'nome' => $user->nome,
            'cognome' => $user->cognome,
            'email' => $user->email,
            'telefono' => $user->telefono,
            'ruolo_id' => $user->ruolo_id,
        ];
        $setParts = ['nome = :nome', 'cognome = :cognome', 'email = :email', 'telefono = :telefono', 'ruolo_id = :ruolo_id'];

        if ($newPassword !== null) {
            $setParts[] = 'password = :password';
            $fields['password'] = $newPassword;
        }

        $fields['id'] = $id;

        $sql = "UPDATE utenti SET " . implode(', ', $setParts) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($fields);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM utenti WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
