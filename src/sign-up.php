<?php
require_once __DIR__ . '/auth/auth.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/CookieRepository.php';

$nome = '';
$cognome = '';
$email = '';
$telefono = '';
$password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userRepository = new UserRepository($conn);
    $userDto = new CreateUserDTO($_POST);

    $nome = $userDto->nome;
    $cognome = $userDto->cognome;
    $email = $userDto->email;
    $telefono = $userDto->telefono ?? '';
    $password = $userDto->password;

    if ($nome === '') {
        $errors['nome'] = "Il nome è obbligatorio";
    } elseif (!preg_match("/^[\\p{L} '-]+$/u", $nome)) {
        $errors['nome'] = "Usa solo lettere e apostrofi";
    }

    if ($cognome === '') {
        $errors['cognome'] = "Il cognome è obbligatorio";
    } elseif (!preg_match("/^[\\p{L} '-]+$/u", $cognome)) {
        $errors['cognome'] = "Usa solo lettere e apostrofi";
    }

    if ($email === '') {
        $errors['email'] = "L'email è obbligatoria";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Inserisci un'email valida";
    } elseif ($userRepository->findByEmail($email)) {
        $errors['email'] = "Email già registrata";
    }

    if ($password === '') {
        $errors['password'] = "La password è obbligatoria";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Almeno 8 caratteri";
    }

    if (empty($errors)) {
        $userDto->hashPassword();
        $created = $userRepository->insertOne($userDto);

        if ($created) {
            $expiry = new DateTime("+7 days");
            $token = bin2hex(random_bytes(32));

            $cookieRepository = new CookieRepository($conn);
            $cookieDto = new CreateCookieDTO((int) $conn->lastInsertId(), $token, $expiry);
            $cookieRepository->insertOne($cookieDto);

            setcookie('credentials', $token, [
                'expires' => $expiry->getTimestamp(),
                'path' => $cookiePath,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            header("Location: $homeUrl");
            exit;
        } else {
            $errors['form'] = "Impossibile creare l'account, riprova.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registrati | Gestione Utenti</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/components/main-nav.php'; ?>

        <main class="card auth-card">
            <div class="page-head">
                <h1 class="page-title">Crea il tuo profilo</h1>
                <p class="muted">Registrati per iniziare a gestire gli utenti.</p>
            </div>

            <?php if (isset($errors['form'])): ?>
                <div class="alert error"><?php echo htmlspecialchars($errors['form']); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nome">Nome</label>
                        <input class="input-field" id="nome" type="text" name="nome" placeholder="Mario"
                            value="<?php echo htmlspecialchars($nome); ?>" required>
                        <?php if (isset($errors['nome'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['nome']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="cognome">Cognome</label>
                        <input class="input-field" id="cognome" type="text" name="cognome" placeholder="Rossi"
                            value="<?php echo htmlspecialchars($cognome); ?>" required>
                        <?php if (isset($errors['cognome'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['cognome']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input class="input-field" id="email" type="email" name="email" placeholder="tua@email.com"
                            value="<?php echo htmlspecialchars($email); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['email']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label for="telefono">Telefono (opzionale)</label>
                        <input class="input-field" id="telefono" type="text" name="telefono" placeholder="1234567890"
                            value="<?php echo htmlspecialchars($telefono); ?>">
                    </div>

                    <div class="form-field">
                        <label for="password">Password</label>
                        <input class="input-field" id="password" type="password" name="password"
                            placeholder="Almeno 8 caratteri" required>
                        <?php if (isset($errors['password'])): ?>
                            <p class="field-error"><?php echo htmlspecialchars($errors['password']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="auth-actions">
                    <button class="btn primary" type="submit">Registrati</button>
                    <a class="btn secondary" href="<?php echo $loginUrl; ?>">Hai già un
                        account?</a>
                </div>
            </form>
        </main>
    </div>
</body>

</html>