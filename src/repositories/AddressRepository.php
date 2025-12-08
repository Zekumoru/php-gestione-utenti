<?php

require_once __DIR__ . '/../models/Address.php';

class AddressRepository
{
    public function __construct(private PDO $conn)
    {
    }

    /**
     * @return Indirizzo[]
     */
    public function findAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM indirizzi ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => Indirizzo::fromArray($row), $rows);
    }

    public function findById(int $id): ?Indirizzo
    {
        $stmt = $this->conn->prepare("SELECT * FROM indirizzi WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return Indirizzo::fromArray($row);
    }

    public function insertOne(CreateIndirizzoDTO $dto): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO indirizzi (utente_id, via, civico, citta, cap)
            VALUES (:utente_id, :via, :civico, :citta, :cap)
        ");

        return $stmt->execute([
            ':utente_id' => $dto->utente_id,
            ':via' => $dto->via,
            ':civico' => $dto->civico,
            ':citta' => $dto->citta,
            ':cap' => $dto->cap,
        ]);
    }

    public function updateOne(int $id, CreateIndirizzoDTO $dto): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE indirizzi
            SET utente_id = :utente_id,
                via = :via,
                civico = :civico,
                citta = :citta,
                cap = :cap
            WHERE id = :id
        ");

        return $stmt->execute([
            ':utente_id' => $dto->utente_id,
            ':via' => $dto->via,
            ':civico' => $dto->civico,
            ':citta' => $dto->citta,
            ':cap' => $dto->cap,
            ':id' => $id,
        ]);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM indirizzi WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
