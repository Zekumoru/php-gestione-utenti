<?php
require_once '../../db/conn.php';

$error_msg = null;

$id = $_GET['id'];
$sql = "SELECT * FROM indirizzi WHERE id = $id;";
$stmt = $conn->prepare($sql);
$stmt->execute();
$indirizzo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $via = $_POST['via'];
    $civico = $_POST['civico'];
    $citta = $_POST['citta'];
    $cap = $_POST['cap'];

    $sql = "UPDATE indirizzi SET
            via = '$via',
            civico = $civico,
            citta = '$citta',
            cap = '$cap'
            WHERE id = $id;";

    $conn->prepare($sql)->execute();
    header("Location: lista_indirizzi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Modifica Indirizzo</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Modifica Indirizzo</h1>
                <p class="muted">Aggiorna le informazioni dell'indirizzo selezionato.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="utente_id">Utente ID</label>
                        <input class="input-field" id="utente_id" type="number" name="utente_id"
                            value="<?php echo $indirizzo['utente_id']; ?>" disabled required>
                    </div>
                    <div class="form-field">
                        <label for="via">Via</label>
                        <input class="input-field" id="via" type="text" name="via"
                            value="<?php echo $indirizzo['via']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="civico">Civico</label>
                        <input class="input-field" id="civico" type="number" name="civico"
                            value="<?php echo $indirizzo['civico']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="citta">Città</label>
                        <input class="input-field" id="citta" type="text" name="citta"
                            value="<?php echo $indirizzo['citta']; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="cap">CAP</label>
                        <input class="input-field" id="cap" type="text" name="cap"
                            value="<?php echo $indirizzo['cap']; ?>" required>
                    </div>
                </div>
                <div><?php echo $error_msg ?></div>
                <div class="actions">
                    <div class="actions-row">
                        <a class="action-link secondary" href="lista_indirizzi.php">Annulla</a>
                        <button class="btn" type="submit">Aggiorna Indirizzo</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
