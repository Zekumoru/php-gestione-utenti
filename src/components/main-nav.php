<?php
$srcRoot = realpath(__DIR__ . '/..');
$currentDir = realpath(dirname($_SERVER['SCRIPT_FILENAME']));

// Build the relative path from the current script directory back to the src root.
$relativeToRoot = (function (string $from, string $to): string{
    $fromParts = explode(DIRECTORY_SEPARATOR, trim($from, DIRECTORY_SEPARATOR));
    $toParts = explode(DIRECTORY_SEPARATOR, trim($to, DIRECTORY_SEPARATOR));

    while (!empty($fromParts) && !empty($toParts) && $fromParts[0] === $toParts[0]) {
        array_shift($fromParts);
        array_shift($toParts);
    }

    $ups = array_fill(0, count($fromParts), '..');
    $relativeParts = array_merge($ups, $toParts);

    return implode('/', $relativeParts);
})($currentDir, $srcRoot);

$baseHref = $relativeToRoot === '' ? '.' : $relativeToRoot;
$currentFile = basename($_SERVER['SCRIPT_NAME']);

$links = [
    [
        'label' => 'Home',
        'href' => $baseHref . '/index.php',
        'active' => $currentFile === 'index.php',
    ],
    [
        'label' => 'Crea Utente',
        'href' => $baseHref . '/pages/utente/crea_utente.php',
        'active' => $currentFile === 'crea_utente.php',
    ],
    [
        'label' => 'Lista Utenti',
        'href' => $baseHref . '/pages/utente/lista_utenti.php',
        'active' => in_array($currentFile, ['lista_utenti.php', 'modifica_utente.php'], true),
    ],
    [
        'label' => 'Crea Indirizzo',
        'href' => $baseHref . '/pages/indirizzo/crea_indirizzo.php',
        'active' => $currentFile === 'crea_indirizzo.php',
    ],
    [
        'label' => 'Lista Indirizzi',
        'href' => $baseHref . '/pages/indirizzo/lista_indirizzi.php',
        'active' => in_array($currentFile, ['lista_indirizzi.php', 'modifica_indirizzo.php'], true),
    ]
];

$isAuth ??= false;
$currentUser ??= null;
$userName = $currentUser ? $currentUser->fullName() : 'Ospite';
?>

<header class="top-nav">
    <div class="brand">Gestione Utenti</div>
    <div class="nav-rail">
        <?php if ($isAuth): ?>
            <nav class="nav-links">
                <?php foreach ($links as $link): ?>
                    <a class="nav-link<?php echo $link['active'] ? ' active' : ''; ?>" href="<?php echo $link['href']; ?>">
                        <?php echo $link['label']; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <div class="nav-actions">
            <?php if ($isAuth): ?>
                <span class="user-pill">👤 <?php echo htmlspecialchars($userName); ?></span>
                <a class="nav-cta" href="<?php echo $baseHref . '/logout.php'; ?>">Esci</a>
            <?php else: ?>
                <a class="nav-cta ghost" href="<?php echo $baseHref . '/login.php'; ?>">Accedi</a>
                <a class="nav-cta" href="<?php echo $baseHref . '/sign-up.php'; ?>">Registrati</a>
            <?php endif; ?>
        </div>
    </div>
</header>
