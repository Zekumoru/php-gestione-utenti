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
<html>

<head>
    <title>Crea Utente</title>
</head>

<body>
    <h1>Crea Nuovo Utente</h1>

    <div>
        <a href="../index.php">Home</a>
        <a href="crea_utente.php">Crea Utente</a>
        <a href="lista_utenti.php">Lista Utenti</a>
    </div>

    <form method="POST" action="">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cognome" placeholder="Cognome" required>
        <input type="text" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Telefono">
        <button type="submit">Crea Utente</button>
    </form>
</body>

</html>