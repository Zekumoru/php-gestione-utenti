<?php

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "root";
$dbname = getenv("DB_NAME") ?: "php_gestione_utenti";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vedere se la tabella "utenti" esisteva già
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM information_schema.tables
        WHERE table_schema = :db AND table_name = 'utenti';
    ");
    $stmt->execute([':db' => $dbname]);
    $tableExistsBefore = $stmt->fetchColumn() > 0;

    $conn->exec("
        CREATE DATABASE IF NOT EXISTS $dbname;
        USE $dbname;

        CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100),
            cognome VARCHAR(100),
            telefono VARCHAR (15),	
            email VARCHAR(255)
        );

        CREATE TABLE IF NOT EXISTS indirizzi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            utente_id INT NOT NULL,
            via VARCHAR(100),
            citta VARCHAR(100),
            cap CHAR(5),
            FOREIGN KEY (utente_id) REFERENCES utenti(id) ON DELETE CASCADE
        );
    ");

    if (!$tableExistsBefore) {
        $conn->exec("
            INSERT INTO utenti (nome, cognome, email, telefono) VALUES
                ('Mario', 'Rossi', 'mario.rossi@email.com', '1234567890'),
                ('Luca', 'Bianchi', 'luca.bianchi@email.com', '0987654321'),
                ('Anna', 'Verdi', 'anna.verdi@email.com', '5551234567');
        ");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
