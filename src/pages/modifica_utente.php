<?php
require_once '../db/conn.php';

$id = $_GET['id'];

$sql = "SELECT * FROM utenti WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE utenti SET 
            nome = '$nome', 
            cognome = '$cognome', 
            email = '$email', 
            telefono = '$telefono' 
            WHERE id = $id";

    $conn->exec($sql);
    header("Location: lista_utenti.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifica Utente</title>
</head>

<body>
    <h1>Modifica Utente</h1>

    <div>
        <a href="../index.php">Home</a>
        <a href="crea_utente.php">Crea Utente</a>
        <a href="lista_utenti.php">Lista Utenti</a>
    </div>

    <form method="POST" action="">
        <input type="text" name="nome" value="<?php echo $utente['nome']; ?>" required>
        <input type="text" name="cognome" value="<?php echo $utente['cognome']; ?>" required>
        <input type="text" name="email" value="<?php echo $utente['email']; ?>" required>
        <input type="text" name="telefono" value="<?php echo $utente['telefono']; ?>">
        <button type="submit">Aggiorna Utente</button>
        <a href="lista_utenti.php">Annulla</a>
    </form>
</body>

</html>