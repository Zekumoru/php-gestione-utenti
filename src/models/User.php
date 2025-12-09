<?php

class User
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $cognome,
        public string $email,
        public ?string $telefono = null,
        public ?int $ruolo_id = null,
        public ?string $ruolo_nome = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['nome'],
            $row['cognome'],
            $row['email'],
            $row['telefono'] ?? null,
            isset($row['ruolo_id']) ? (int) $row['ruolo_id'] : null,
            $row['ruolo_nome'] ?? $row['nome_ruolo'] ?? null,
        );
    }

    public function fullName(): string
    {
        return trim($this->nome . ' ' . $this->cognome);
    }
}

class PasswordUser extends User
{
    public string $password;

    public function __construct(
        int $id,
        string $nome,
        string $cognome,
        string $email,
        ?string $telefono,
        string $password,
        ?int $ruolo_id = null,
        ?string $ruolo_nome = null,
    ) {
        parent::__construct($id, $nome, $cognome, $email, $telefono, $ruolo_id, $ruolo_nome);
        $this->password = $password;
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['nome'],
            $row['cognome'],
            $row['email'],
            $row['telefono'] ?? null,
            $row['password'],
            isset($row['ruolo_id']) ? (int) $row['ruolo_id'] : null,
            $row['ruolo_nome'] ?? $row['nome_ruolo'] ?? null,
        );
    }
}

class CreateUserDTO
{
    public string $nome;
    public string $cognome;
    public string $email;
    public string $password;
    public ?string $telefono;
    public ?int $ruolo_id;

    public function __construct(array $data)
    {
        $this->nome = ucwords(trim($data['nome'] ?? ''));
        $this->cognome = ucwords(trim($data['cognome'] ?? ''));
        $this->email = strtolower(trim($data['email'] ?? ''));
        $this->password = $data['password'] ?? '';
        $this->telefono = trim($data['telefono'] ?? '') ?: null;
        $this->ruolo_id = isset($data['ruolo_id']) && $data['ruolo_id'] !== '' ? (int) $data['ruolo_id'] : null;
    }

    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }
}

class UpdateUserDTO extends CreateUserDTO
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}

class LogInUserDTO
{
    public string $email;
    public string $password;

    public function __construct(array $data)
    {
        $this->email = strtolower(trim($data['email'] ?? ''));
        $this->password = $data['password'] ?? '';
    }

    public function verify(string $hashedPassword): bool
    {
        return password_verify($this->password, $hashedPassword);
    }
}
