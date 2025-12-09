<?php
require_once __DIR__ . '/auth/auth.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/CookieRepository.php';

$email = '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userRepository = new UserRepository($conn);
    $userDto = new LogInUserDTO($_POST);
    $email = $userDto->email;

    $user = $userRepository->findByEmail($userDto->email);
    if (!$user || !$userDto->verify($user->password)) {
        $error = "Credenziali non valide. Riprova.";
    } else {
        $expiry = new DateTime("+7 days");
        $token = bin2hex(random_bytes(32));

        $cookieRepository = new CookieRepository($conn);
        $cookieDto = new CreateCookieDTO($user->id, $token, $expiry);
        $cookieRepository->insertOne($cookieDto);

        setcookie('credentials', $token, [
            'expires' => $expiry->getTimestamp(),
            'path' => $cookiePath,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        header("Location: $homeUrl");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Accedi | Gestione Utenti</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/components/main-nav.php'; ?>

        <main class="card auth-card">
            <div class="page-head">
                <h1 class="page-title">Bentornato</h1>
                <p class="muted">Accedi per gestire utenti e indirizzi.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input class="input-field" id="email" type="email" name="email" placeholder="tua@email.com"
                            value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input class="input-field" id="password" type="password" name="password" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="auth-actions">
                    <button class="btn primary" type="submit">Accedi</button>
                    <a class="btn secondary" href="<?php echo $signupUrl; ?>">Crea un
                        account</a>
                </div>
            </form>
        </main>
    </div>
</body>

</html>