<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';
require_once dirname(__DIR__, 2) . '/models/User.php';
require_once dirname(__DIR__, 2) . '/repositories/UserRepository.php';
require_once dirname(__DIR__, 2) . '/repositories/RoleRepository.php';

$roleRepository = new RoleRepository($conn);
$ruoli = $roleRepository->findAll();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$userRepository = new UserRepository($conn);

$utente = $userRepository->findById($id);

if (!$utente) {
    header("Location: lista_utenti.php");
    exit;
}

$errors = [];
$dto = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dto = new UpdateUserDTO($_POST);

    if ($dto->nome === '') {
        $errors['nome'] = "Il nome è obbligatorio";
    }
    if ($dto->cognome === '') {
        $errors['cognome'] = "Il cognome è obbligatorio";
    }
    if ($dto->email === '' || !filter_var($dto->email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Inserisci un'email valida";
    }

    if ($dto->password !== '' && strlen($dto->password) < 8) {
        $errors['password'] = "Minimo 8 caratteri";
    }

    $validRoleIds = array_map(fn($ruolo) => $ruolo->id, $ruoli);
    if ($dto->ruolo_id !== null && !in_array($dto->ruolo_id, $validRoleIds, true)) {
        $errors['ruolo_id'] = "Seleziona un ruolo valido";
    }

    if ($userRepository->emailInUseByAnother($dto->email, $id)) {
        $errors['email'] = "Email già in uso da un altro account";
    }

    if (empty($errors)) {
        $newPassword = null;
        if ($dto->password !== '') {
            $dto->hashPassword();
            $newPassword = $dto->password;
        }

        $userRepository->updateOne($id, $dto, $newPassword);
        header("Location: lista_utenti.php");
        exit;
    }
} else {
    $dto = new UpdateUserDTO([
        'nome' => $utente->nome,
        'cognome' => $utente->cognome,
        'email' => $utente->email,
        'telefono' => $utente->telefono ?? '',
        'ruolo_id' => $utente->ruolo_id ?? '',
        'password' => '',
    ]);
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

            <?php if (!empty($errors)): ?>
                <div class="alert error">Correggi i campi evidenziati.</div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nome">Nome</label>
                        <input class="input-field" id="nome" type="text" name="nome"
                            value="<?php echo htmlspecialchars($dto ? $dto->nome : $utente->nome); ?>" required>
                        <?php if (isset($errors['nome'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['nome']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="cognome">Cognome</label>
                        <input class="input-field" id="cognome" type="text" name="cognome"
                            value="<?php echo htmlspecialchars($dto ? $dto->cognome : $utente->cognome); ?>" required>
                        <?php if (isset($errors['cognome'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['cognome']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input class="input-field" id="email" type="email" name="email"
                            value="<?php echo htmlspecialchars($dto ? $dto->email : $utente->email); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['email']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="telefono">Telefono</label>
                        <input class="input-field" id="telefono" type="text" name="telefono"
                            value="<?php echo htmlspecialchars($dto ? ($dto->telefono ?? '') : ($utente->telefono ?? '')); ?>">
                    </div>
                    <div class="form-field">
                        <label for="ruolo_id">Ruolo</label>
                        <select class="input-field" id="ruolo_id" name="ruolo_id">
                            <option value="">Senza ruolo</option>
                            <?php foreach ($ruoli as $ruolo): ?>
                                <option value="<?php echo $ruolo->id; ?>"
                                    <?php echo (string) ($dto?->ruolo_id ?? '') === (string) $ruolo->id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ruolo->nome_ruolo); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['ruolo_id'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['ruolo_id']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-field">
                        <label for="password">Nuova Password (opzionale)</label>
                        <input class="input-field" id="password" type="password" name="password"
                            placeholder="Lascia vuoto per mantenere quella attuale">
                        <?php if (isset($errors['password'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['password']); ?></p>
                        <?php endif; ?>
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
