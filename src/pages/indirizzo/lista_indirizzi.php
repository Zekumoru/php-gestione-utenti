<?php
require_once '../../db/conn.php';

if (isset($_GET['elimina'])) {
    $id = $_GET['elimina'];
    $sql = "DELETE FROM indirizzi WHERE id = $id";
    $conn->prepare($sql)->execute();
}

$sql = "SELECT i.*, u.nome, u.cognome FROM indirizzi i INNER JOIN utenti u ON i.utente_id = u.id;";
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
    <title>Lista Indirizzi</title>
</head>

<body>
    <div class="app-shell">
        <?php include __DIR__ . '/../../components/main-nav.php'; ?>

        <main class="card">
            <div class="page-head">
                <h1 class="page-title">Lista Indirizzi</h1>
                <p class="muted">Consulta, modifica o elimina gli indirizzi degli utenti.</p>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Via</th>
                            <th>Civico</th>
                            <th>Citta</th>
                            <th>CAP</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td class="muted" colspan="6">Nessun indirizzo presente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo $row['nome'] . ' ' . $row['cognome']; ?></td>
                                    <td><?php echo $row['via']; ?></td>
                                    <td><?php echo $row['civico']; ?></td>
                                    <td><?php echo $row['citta']; ?></td>
                                    <td><?php echo $row['cap']; ?></td>
                                    <td>
                                        <div class="actions-row">
                                            <a class="action-link"
                                                href="modifica_indirizzo.php?id=<?php echo $row['id']; ?>">Modifica</a>
                                            <a class="action-link danger"
                                                href="lista_indirizzi.php?elimina=<?php echo $row['id']; ?>">Elimina</a>
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
