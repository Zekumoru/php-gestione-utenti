<?php

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "root";
$dbname = getenv("DB_NAME") ?: "php_gestione_utenti";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("
        CREATE DATABASE IF NOT EXISTS $dbname;
        USE $dbname;

        CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            cognome VARCHAR(100) NOT NULL,
            telefono VARCHAR (15),	
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

    $existingUtenti = (int) $conn->query("SELECT COUNT(*) FROM utenti")->fetchColumn();
    if ($existingUtenti === 0) {
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, telefono, password) VALUES (?, ?, ?, ?, ?)");
        $seedData = [
            ['Mario', 'Rossi', 'mario.rossi@email.com', '1234567890', $passwordHash],
            ['Luca', 'Bianchi', 'luca.bianchi@email.com', '0987654321', $passwordHash],
            ['Anna', 'Verdi', 'anna.verdi@email.com', '5551234567', $passwordHash],
            ['Demo', 'Admin', 'admin@example.com', '0000000000', $passwordHash],
        ];

        foreach ($seedData as $row) {
            $stmt->execute($row);
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
