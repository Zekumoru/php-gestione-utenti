<?php

class Indirizzo
{
    public function __construct(
        public int $id,
        public int $utente_id,
        public string $via,
        public int $civico,
        public string $citta,
        public string $cap,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['utente_id'],
            $row['via'],
            (int) $row['civico'],
            $row['citta'],
            $row['cap'],
        );
    }
}

class CreateIndirizzoDTO
{
    public int $utente_id;
    public string $via;
    public int $civico;
    public string $citta;
    public string $cap;

    public function __construct(array $data)
    {
        $this->utente_id = isset($data['utente_id']) ? (int) $data['utente_id'] : 0;
        $this->via = trim($data['via'] ?? '');
        $this->civico = isset($data['civico']) ? (int) $data['civico'] : 0;
        $this->citta = trim($data['citta'] ?? '');
        $this->cap = trim($data['cap'] ?? '');
    }
}

class UpdateIndirizzoDTO extends CreateIndirizzoDTO
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
