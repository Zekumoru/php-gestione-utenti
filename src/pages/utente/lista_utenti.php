<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';

if (isset($_GET['elimina'])) {
    $id = $_GET['elimina'];
    $sql = "DELETE FROM utenti WHERE id = $id";
    $conn->prepare($sql)->execute();
}

$sql = "SELECT * FROM utenti";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td class="muted" colspan="6">Nessun utente presente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nome']; ?></td>
                                    <td><?php echo $row['cognome']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['telefono']; ?></td>
                                    <td>
                                        <div class="actions-row">
                                            <a class="action-link"
                                                href="modifica_utente.php?id=<?php echo $row['id']; ?>">Modifica</a>
                                            <a class="action-link danger"
                                                href="lista_utenti.php?elimina=<?php echo $row['id']; ?>">Elimina</a>
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
