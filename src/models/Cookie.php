<?php

class Cookie
{
    public function __construct(
        public int $id,
        public int $utente_id,
        public string $token,
        public DateTime $scadenza,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['utente_id'],
            $row['token'],
            new DateTime($row['scadenza']),
        );
    }
}

class CreateCookieDTO
{
    public string $utente_id;
    public string $token;
    public string $scadenza;

    public function __construct(int $utente_id, string $token, DateTime $expiry)
    {
        $this->utente_id = (string) $utente_id;
        $this->token = $token;
        $this->scadenza = $expiry->format('Y-m-d H:i:s');
    }
}
