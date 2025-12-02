<?php
require_once '../../db/conn.php';

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
    <title>Modifica Utente</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Modifica Utente</h1>
                <p class="muted">Aggiorna le informazioni del profilo selezionato.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nome">Nome</label>
                        <input class="input-field" id="nome" type="text" name="nome"
                            value="<?php echo $utente['nome']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="cognome">Cognome</label>
                        <input class="input-field" id="cognome" type="text" name="cognome"
                            value="<?php echo $utente['cognome']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input class="input-field" id="email" type="email" name="email"
                            value="<?php echo $utente['email']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="telefono">Telefono</label>
                        <input class="input-field" id="telefono" type="text" name="telefono"
                            value="<?php echo $utente['telefono']; ?>">
                    </div>
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
