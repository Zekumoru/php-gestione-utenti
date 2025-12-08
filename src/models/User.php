<?php

class User
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $cognome,
        public string $email,
        public ?string $telefono = null,
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
        );
    }

    public function fullName(): string
    {
        return trim($this->nome . ' ' . $this->cognome);
    }
}

class PasswordUser extends User
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $cognome,
        public string $email,
        public ?string $telefono,
        public string $password,
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
            $row['password'],
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

    public function __construct(array $data)
    {
        $this->nome = ucwords(trim($data['nome'] ?? ''));
        $this->cognome = ucwords(trim($data['cognome'] ?? ''));
        $this->email = strtolower(trim($data['email'] ?? ''));
        $this->password = $data['password'] ?? '';
        $this->telefono = trim($data['telefono'] ?? '') ?: null;
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
