<?php
require_once dirname(__DIR__, 2) . '/auth/auth.php';
require_once dirname(__DIR__, 2) . '/repositories/AddressRepository.php';
require_once dirname(__DIR__, 2) . '/repositories/UserRepository.php';

$addressRepository = new AddressRepository($conn);
$userRepository = new UserRepository($conn);

if (isset($_GET['elimina'])) {
    $addressRepository->deleteById((int) $_GET['elimina']);
}

$rows = $addressRepository->findAll();
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
                                <?php $owner = $userRepository->findById($row->utente_id); ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($owner ? $owner->fullName() : ('ID ' . $row->utente_id)); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row->via); ?></td>
                                    <td><?php echo $row->civico; ?></td>
                                    <td><?php echo htmlspecialchars($row->citta); ?></td>
                                    <td><?php echo htmlspecialchars($row->cap); ?></td>
                                    <td>
                                        <div class="actions-row">
                                            <a class="btn secondary"
                                                href="modifica_indirizzo.php?id=<?php echo $row->id; ?>">Modifica</a>
                                            <a class="btn danger"
                                                href="lista_indirizzi.php?elimina=<?php echo $row->id; ?>">Elimina</a>
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