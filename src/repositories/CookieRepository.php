<?php

require_once __DIR__ . '/../models/Cookie.php';

class CookieRepository
{
    public function __construct(private PDO $conn)
    {
    }

    public function findByToken(string $token): ?Cookie
    {
        $stmt = $this->conn->prepare("SELECT * FROM cookies WHERE token = ? AND scadenza > NOW()");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return Cookie::fromArray($row);
    }

    public function insertOne(CreateCookieDTO $cookie): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO cookies (utente_id, token, scadenza) VALUES (?, ?, ?)");
        return $stmt->execute([$cookie->utente_id, $cookie->token, $cookie->scadenza]);
    }

    public function deleteByToken(string $token): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM cookies WHERE token = ?");
        return $stmt->execute([$token]);
    }
}
