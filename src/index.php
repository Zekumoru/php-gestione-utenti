<?php
require_once __DIR__ . '/auth/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gestione Utenti</title>
</head>

<body>
    <div class="app-shell" id="app">
        <?php include __DIR__ . '/components/main-nav.php'; ?>

        <main class="card hero">
            <div class="page-head">
                <h1 class="page-title">Gestisci gli utenti con stile</h1>
                <p class="muted">Crea, consulta e aggiorna i profili della tua organizzazione in pochi click.</p>
                <?php if (isset($currentUser) && $currentUser): ?>
                    <p class="muted subtle-badge">Accesso eseguito come <?php echo htmlspecialchars($currentUser->fullName()); ?></p>
                <?php endif; ?>
            </div>

            <div class="pill-grid">
                <a class="action-link" href="./pages/utente/crea_utente.php">➕ Crea un nuovo utente</a>
                <a class="action-link secondary" href="./pages/utente/lista_utenti.php">📋 Vai alla lista</a>
            </div>
        </main>
    </div>
</body>

</html>
