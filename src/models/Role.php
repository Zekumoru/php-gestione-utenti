<?php

class Role
{
    public function __construct(
        public int $id,
        public string $nome_ruolo,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['nome_ruolo'],
        );
    }
}
