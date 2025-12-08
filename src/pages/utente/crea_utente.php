<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = strtolower(trim($_POST['email']));
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];

    if ($password === '' || strlen($password) < 8) {
        $error = "La password deve avere almeno 8 caratteri.";
    }

    $existing = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
    $existing->execute([$email]);
    if ($existing->fetch()) {
        $error = "Email già registrata.";
    }

    if (!isset($error)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, telefono, password) VALUES (:nome, :cognome, :email, :telefono, :password)");
        $stmt->execute([
            ':nome' => $nome,
            ':cognome' => $cognome,
            ':email' => $email,
            ':telefono' => $telefono ?: null,
            ':password' => $hashedPassword,
        ]);

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
