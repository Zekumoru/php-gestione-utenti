<?php
require_once '../db/conn.php';

$id = $_GET['id'];

$sql = "SELECT * FROM utenti WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE utenti SET 
            nome = '$nome', 
            cognome = '$cognome', 
            email = '$email', 
            telefono = '$telefono' 
            WHERE id = $id";

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
    <title>Modifica Utente</title>
</head>

<body>
    <div class="app-shell">
        <header class="top-nav">
            <div class="brand">Gestione Utenti</div>
            <nav class="nav-links">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link" href="crea_utente.php">Crea Utente</a>
                <a class="nav-link active" href="lista_utenti.php">Lista Utenti</a>
            </nav>
        </header>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Modifica Utente</h1>
                <p class="muted">Aggiorna le informazioni del profilo selezionato.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <input class="input-field" type="text" name="nome" value="<?php echo $utente['nome']; ?>" required>
                    <input class="input-field" type="text" name="cognome" value="<?php echo $utente['cognome']; ?>" required>
                    <input class="input-field" type="email" name="email" value="<?php echo $utente['email']; ?>" required>
                    <input class="input-field" type="text" name="telefono" value="<?php echo $utente['telefono']; ?>">
                </div>
                <div class="actions">
                    <div class="actions-row">
                        <a class="action-link secondary" href="lista_utenti.php">Annulla</a>
                        <button class="btn" type="submit">Aggiorna Utente</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
