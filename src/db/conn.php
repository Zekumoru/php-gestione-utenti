<?php

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "root";
$dbname = getenv("DB_NAME") ?: "php_gestione_utenti";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("
        CREATE DATABASE IF NOT EXISTS `$dbname`;
        USE `$dbname`;

        CREATE TABLE IF NOT EXISTS ruoli (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_ruolo VARCHAR(50) NOT NULL UNIQUE
        );

        CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            cognome VARCHAR(100) NOT NULL,
            telefono VARCHAR (15),	
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            ruolo_id INT NULL,
            data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_utenti_ruolo FOREIGN KEY (ruolo_id) REFERENCES ruoli(id) ON DELETE SET NULL
        );

        CREATE TABLE IF NOT EXISTS cookies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            utente_id INT NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            scadenza TIMESTAMP NOT NULL,
            FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS indirizzi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            utente_id INT NOT NULL,
            via VARCHAR(100),
            civico INT,
            citta VARCHAR(100),
            cap CHAR(5),
            FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE
        );
    ");

    $existingRoles = (int) $conn->query("SELECT COUNT(*) FROM ruoli")->fetchColumn();
    if ($existingRoles === 0) {
        $conn->exec("INSERT INTO ruoli (nome_ruolo) VALUES ('Amministratore'), ('Editore'), ('Visitatore')");
    }

    $roleIds = [];
    $roleRows = $conn->query("SELECT id, nome_ruolo FROM ruoli")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($roleRows as $roleRow) {
        $roleIds[$roleRow['nome_ruolo']] = (int) $roleRow['id'];
    }

    $existingUtenti = (int) $conn->query("SELECT COUNT(*) FROM utenti")->fetchColumn();
    if ($existingUtenti === 0) {
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, telefono, password, ruolo_id) VALUES (?, ?, ?, ?, ?, ?)");
        $seedData = [
            ['Mario', 'Rossi', 'mario.rossi@email.com', '1234567890', $passwordHash, $roleIds['Visitatore'] ?? null],
            ['Luca', 'Bianchi', 'luca.bianchi@email.com', '0987654321', $passwordHash, $roleIds['Editore'] ?? null],
            ['Anna', 'Verdi', 'anna.verdi@email.com', '5551234567', $passwordHash, $roleIds['Visitatore'] ?? null],
            ['Demo', 'Admin', 'admin@example.com', '0000000000', $passwordHash, $roleIds['Amministratore'] ?? null],
        ];

        foreach ($seedData as $row) {
            $stmt->execute($row);
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
