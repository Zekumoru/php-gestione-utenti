<?php
require_once '../db/conn.php';

if (isset($_GET['elimina'])) {
    $id = $_GET['elimina'];
    $sql = "DELETE FROM utenti WHERE id = $id";
    $conn->exec($sql);
}

$sql = "SELECT * FROM utenti";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lista Utenti</title>
</head>

<body>
    <h1>Lista Utenti</h1>

    <div>
        <a href="../index.php">Home</a>
        <a href="crea_utente.php">Crea Utente</a>
        <a href="lista_utenti.php">Lista Utenti</a>
    </div>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Azioni</th>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['cognome']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['telefono']; ?></td>
                <td>
                    <a href="modifica_utente.php?id=<?php echo $row['id']; ?>">Modifica</a>
                    <a href="lista_utenti.php?elimina=<?php echo $row['id']; ?>">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>