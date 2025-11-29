<?php
require_once '../db/conn.php';

if ($_POST) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO utenti (nome, cognome, email, telefono) 
            VALUES ('$nome', '$cognome', '$email', '$telefono')";

    $conn->exec($sql);
    header("Location: lista_utenti.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Crea Utente</title>
</head>

<body>
    <div class="app-shell">
        <header class="top-nav">
            <div class="brand">Gestione Utenti</div>
            <nav class="nav-links">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link active" href="crea_utente.php">Crea Utente</a>
                <a class="nav-link" href="lista_utenti.php">Lista Utenti</a>
            </nav>
        </header>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Crea Nuovo Utente</h1>
                <p class="muted">Compila i campi per aggiungere rapidamente un nuovo profilo.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <input class="input-field" type="text" name="nome" placeholder="Nome" required>
                    <input class="input-field" type="text" name="cognome" placeholder="Cognome" required>
                    <input class="input-field" type="email" name="email" placeholder="Email" required>
                    <input class="input-field" type="text" name="telefono" placeholder="Telefono">
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Crea Utente</button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
