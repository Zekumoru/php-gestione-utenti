<?php
require_once '../../db/conn.php';

if ($_POST) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO utenti (nome, cognome, email, telefono) 
            VALUES ('$nome', '$cognome', '$email', '$telefono')";

    $conn->prepare($sql)->execute();
    header("Location: lista_utenti.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Crea Utente</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Crea Nuovo Utente</h1>
                <p class="muted">Compila i campi per aggiungere rapidamente un nuovo profilo.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nome">Nome</label>
                        <input class="input-field" id="nome" type="text" name="nome" placeholder="Nome" required>
                    </div>
                    <div class="form-field">
                        <label for="cognome">Cognome</label>
                        <input class="input-field" id="cognome" type="text" name="cognome" placeholder="Cognome"
                            required>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input class="input-field" id="email" type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-field">
                        <label for="telefono">Telefono</label>
                        <input class="input-field" id="telefono" type="text" name="telefono" placeholder="Telefono">
                    </div>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Crea Utente</button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
