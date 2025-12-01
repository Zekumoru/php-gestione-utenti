<?php
require_once "db/conn.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gestione Utenti</title>
</head>

<body>
    <div class="app-shell" id="app">
        <header class="top-nav">
            <div class="brand">Gestione Utenti</div>
            <nav class="nav-links">
                <a class="nav-link active" href="index.php">Home</a>
                <a class="nav-link" href="./pages/utente/crea_utente.php">Crea Utente</a>
                <a class="nav-link" href="./pages/utente/lista_utenti.php">Lista Utenti</a>
            </nav>
        </header>

        <main class="card hero">
            <div class="page-head">
                <h1 class="page-title">Gestisci gli utenti con stile</h1>
                <p class="muted">Crea, consulta e aggiorna i profili della tua organizzazione in pochi click.</p>
            </div>

            <div class="pill-grid">
                <a class="action-link" href="./pages/utente/crea_utente.php">➕ Crea un nuovo utente</a>
                <a class="action-link secondary" href="./pages/utente/lista_utenti.php">📋 Vai alla lista</a>
            </div>
        </main>
    </div>
</body>

</html>
