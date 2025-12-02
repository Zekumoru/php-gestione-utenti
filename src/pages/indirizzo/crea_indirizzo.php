<?php
require_once '../../db/conn.php';

$error_msg = null;

if ($_POST) {
    $utente_id = $_POST['utente_id'];
    $via = $_POST['via'];
    $civico = $_POST['civico'];
    $citta = $_POST['citta'];
    $cap = $_POST['cap'];

    $sql = "INSERT INTO indirizzi (utente_id, via, civico, citta, cap)
            VALUES ($utente_id, '$via', $civico, '$citta', '$cap');";

    try {
        $conn->prepare($sql)->execute();
        header("Location: lista_indirizzi.php");
        exit;
    } catch (PDOException $e) {
        $error_msg = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Crea Indirizzo</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Crea Nuovo Indirizzo</h1>
                <p class="muted">Compila i campi per aggiungere un nuovo indirizzo ad un utente.</p>
            </div>

            <form method="POST" action="">
                <div class="form-grid">
                    <input class="input-field" type="number" name="utente_id" placeholder="Utente ID" required>
                    <input class="input-field" type="text" name="via" placeholder="Via" required>
                    <input class="input-field" type="number" name="civico" placeholder="Civico" required>
                    <input class="input-field" type="text" name="citta" placeholder="Citta" required>
                    <input class="input-field" type="text" name="cap" placeholder="CAP" required>
                </div>
                <div><?php echo $error_msg ?></div>
                <div class="actions">
                    <button class="btn" type="submit">Crea Indirizzo</button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>