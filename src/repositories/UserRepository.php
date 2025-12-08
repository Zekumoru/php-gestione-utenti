<?php

require_once __DIR__ . '/../models/User.php';

class UserRepository
{
    public function __construct(private PDO $conn)
    {
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->conn->prepare("SELECT id, nome, cognome, email, telefono FROM utenti WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return User::fromArray($row);
    }

    public function findByEmail(string $email): ?PasswordUser
    {
        $stmt = $this->conn->prepare("SELECT id, nome, cognome, email, telefono, password FROM utenti WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return PasswordUser::fromArray($row);
    }

    public function insertOne(CreateUserDTO $user): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO utenti (nome, cognome, email, telefono, password) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user->nome, $user->cognome, $user->email, $user->telefono, $user->password]);
    }

    public function updateOne(int $id, UpdateUserDTO $user, ?string $newPassword = null): bool
    {
        $fields = ['nome' => $user->nome, 'cognome' => $user->cognome, 'email' => $user->email, 'telefono' => $user->telefono];
        $setParts = ['nome = :nome', 'cognome = :cognome', 'email = :email', 'telefono = :telefono'];

        if ($newPassword !== null) {
            $setParts[] = 'password = :password';
            $fields['password'] = $newPassword;
        }

        $fields['id'] = $id;

        $sql = "UPDATE utenti SET " . implode(', ', $setParts) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($fields);
    }
}
