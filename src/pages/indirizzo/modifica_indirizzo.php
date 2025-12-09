<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';
require_once dirname(__DIR__, 2) . '/repositories/AddressRepository.php';
require_once dirname(__DIR__, 2) . '/models/Address.php';

$error_msg = null;
$addressRepository = new AddressRepository($conn);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$indirizzo = $addressRepository->findById($id);

if (!$indirizzo) {
    header("Location: lista_indirizzi.php");
    exit;
}

$dto = new UpdateIndirizzoDTO([
    'utente_id' => $indirizzo->utente_id,
    'via' => $indirizzo->via,
    'civico' => $indirizzo->civico,
    'citta' => $indirizzo->citta,
    'cap' => $indirizzo->cap,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dto = new UpdateIndirizzoDTO($_POST);

    if ($dto->utente_id <= 0) {
        $error_msg = "Utente non valido";
    } elseif ($dto->via === '' || $dto->citta === '' || $dto->cap === '' || $dto->civico <= 0) {
        $error_msg = "Tutti i campi sono obbligatori.";
    }

    if (!$error_msg) {
        try {
            $addressRepository->updateOne($id, $dto);
            header("Location: lista_indirizzi.php");
            exit;
        } catch (PDOException $e) {
            $error_msg = $e->getMessage();
        }
    }
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

            <?php if ($error_msg): ?>
                <div class="alert error"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="utente_id">Utente ID</label>
                        <input class="input-field" id="utente_id" type="number" name="utente_id"
                            value="<?php echo htmlspecialchars($dto->utente_id); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="via">Via</label>
                        <input class="input-field" id="via" type="text" name="via"
                            value="<?php echo htmlspecialchars($dto->via); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="civico">Civico</label>
                        <input class="input-field" id="civico" type="number" name="civico"
                            value="<?php echo htmlspecialchars($dto->civico); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="citta">Città</label>
                        <input class="input-field" id="citta" type="text" name="citta"
                            value="<?php echo htmlspecialchars($dto->citta); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="cap">CAP</label>
                        <input class="input-field" id="cap" type="text" name="cap"
                            value="<?php echo htmlspecialchars($dto->cap); ?>" required>
                    </div>
                </div>
                <div class="actions">
                    <div class="actions-row">
                        <a class="btn secondary" href="lista_indirizzi.php">Annulla</a>
                        <button class="btn primary" type="submit">Aggiorna Indirizzo</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>

</html>