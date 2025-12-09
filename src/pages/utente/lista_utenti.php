<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';

$userRepository = new UserRepository($conn);

if (isset($_GET['elimina'])) {
    $userRepository->deleteById((int) $_GET['elimina']);
}

$users = $userRepository->findAll();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Lista Utenti</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Lista Utenti</h1>
                <p class="muted">Consulta, modifica o elimina i profili già registrati.</p>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cognome</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Ruolo</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td class="muted" colspan="7">Nessun utente presente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user->id; ?></td>
                                    <td><?php echo htmlspecialchars($user->nome); ?></td>
                                    <td><?php echo htmlspecialchars($user->cognome); ?></td>
                                    <td><?php echo htmlspecialchars($user->email); ?></td>
                                    <td><?php echo htmlspecialchars($user->telefono ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($user->ruolo_nome ?? '—'); ?></td>
                                    <td>
                                        <div class="actions-row">
                                            <a class="btn secondary"
                                                href="modifica_utente.php?id=<?php echo $user->id; ?>">Modifica</a>
                                            <a class="btn danger"
                                                href="lista_utenti.php?elimina=<?php echo $user->id; ?>">Elimina</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>