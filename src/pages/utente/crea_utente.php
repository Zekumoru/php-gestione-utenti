<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';
require_once dirname(__DIR__, 2) . '/models/User.php';
require_once dirname(__DIR__, 2) . '/repositories/UserRepository.php';
require_once dirname(__DIR__, 2) . '/repositories/RoleRepository.php';

$roleRepository = new RoleRepository($conn);
$userRepository = new UserRepository($conn);
$ruoli = $roleRepository->findAll();
$selectedRoleId = $_POST['ruolo_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dto = new CreateUserDTO($_POST);
    $selectedRoleId = $dto->ruolo_id ?? '';

    $validRoleIds = array_map(fn($ruolo) => $ruolo->id, $ruoli);
    if ($dto->ruolo_id !== null && !in_array($dto->ruolo_id, $validRoleIds, true)) {
        $error = "Ruolo selezionato non valido.";
    }

    if ($dto->password === '' || strlen($dto->password) < 8) {
        $error = "La password deve avere almeno 8 caratteri.";
    }

    if (!isset($error) && $userRepository->emailExists($dto->email)) {
        $error = "Email già registrata.";
    }

    if (!isset($error)) {
        $dto->hashPassword();
        $userRepository->insertOne($dto);

        header("Location: lista_utenti.php");
        exit;
    }
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

            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

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
                    <div class="form-field">
                        <label for="ruolo_id">Ruolo (opzionale)</label>
                        <select class="input-field" id="ruolo_id" name="ruolo_id">
                            <option value="">Senza ruolo</option>
                            <?php foreach ($ruoli as $ruolo): ?>
                                <option value="<?php echo $ruolo->id; ?>"
                                    <?php echo (string) $selectedRoleId === (string) $ruolo->id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ruolo->nome_ruolo); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input class="input-field" id="password" type="password" name="password" placeholder="Almeno 8 caratteri" required>
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
