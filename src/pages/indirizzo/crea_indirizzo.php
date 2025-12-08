<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';
require_once dirname(__DIR__, 2) . '/repositories/AddressRepository.php';
require_once dirname(__DIR__, 2) . '/models/Address.php';

$error_msg = null;
$addressRepository = new AddressRepository($conn);
$dto = new CreateIndirizzoDTO([]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dto = new CreateIndirizzoDTO($_POST);

    if ($dto->utente_id <= 0) {
        $error_msg = "Utente non valido";
    } elseif ($dto->via === '' || $dto->citta === '' || $dto->cap === '' || $dto->civico <= 0) {
        $error_msg = "Tutti i campi sono obbligatori.";
    }

    if (!$error_msg) {
        try {
            $addressRepository->insertOne($dto);
            header("Location: lista_indirizzi.php");
            exit;
        } catch (PDOException $e) {
            $error_msg = $e->getMessage();
        }
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
                    <div class="form-field">
                        <label for="utente_id">Utente ID</label>
                        <input class="input-field" id="utente_id" type="number" name="utente_id" placeholder="Utente ID"
                            value="<?php echo htmlspecialchars($dto->utente_id); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="via">Via</label>
                        <input class="input-field" id="via" type="text" name="via" placeholder="Via"
                            value="<?php echo htmlspecialchars($dto->via); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="civico">Civico</label>
                        <input class="input-field" id="civico" type="number" name="civico" placeholder="Civico"
                            value="<?php echo htmlspecialchars($dto->civico); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="citta">Città</label>
                        <input class="input-field" id="citta" type="text" name="citta" placeholder="Città"
                            value="<?php echo htmlspecialchars($dto->citta); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="cap">CAP</label>
                        <input class="input-field" id="cap" type="text" name="cap" placeholder="CAP"
                            value="<?php echo htmlspecialchars($dto->cap); ?>" required>
                    </div>
                </div>
                <div><?php echo $error_msg ? '<div class="alert error">' . htmlspecialchars($error_msg) . '</div>' : ''; ?>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Crea Indirizzo</button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
