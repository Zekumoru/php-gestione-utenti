<?php
// Importa la connessione al database e i repository necessari
require_once __DIR__ . '/../db/conn.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/CookieRepository.php';

// Ottiene il percorso assoluto della cartella del progetto
$projectRoot = realpath(__DIR__ . '/..');

// Ottiene il document root del server (cioè la cartella pubblica da cui vengono serviti i file)
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;

// Calcola il percorso web di base (es. "/miosito" se il progetto è in una sottocartella)
$baseWebPath = '';
if ($documentRoot && strncmp($projectRoot, $documentRoot, strlen($documentRoot)) === 0) {
    // Rimuove il document root dal project root per ottenere solo la parte relativa
    $baseWebPath = '/' . trim(str_replace($documentRoot, '', $projectRoot), '/');
}
// Se il risultato è solo "/", allora significa root → percorso vuoto
$baseWebPath = $baseWebPath === '/' ? '' : $baseWebPath;

// Costruisce gli URL delle pagine principali del sito
$loginUrl = ($baseWebPath ?: '') . '/login.php';
$signupUrl = ($baseWebPath ?: '') . '/sign-up.php';
$homeUrl = ($baseWebPath ?: '') . '/index.php';

// Imposta il percorso del cookie ("/" se in root, oppure "/miosito" se in sottocartella)
$cookiePath = $baseWebPath === '' ? '/' : $baseWebPath;

// Variabili che rappresentano l'utente corrente e il suo stato di autenticazione
$currentUser = null;
$isAuth = false;

// Controlla se esiste il cookie "credentials"
if (isset($_COOKIE['credentials'])) {
    $cookieRepository = new CookieRepository($conn);
    $token = $_COOKIE['credentials'];

    // Cerca il cookie nel database tramite il token
    $cookie = $cookieRepository->findByToken($token);

    // Se il cookie esiste ed è ancora valido
    if ($cookie && $cookie->scadenza > new DateTime()) {
        $userRepository = new UserRepository($conn);

        // Recupera l'utente associato al cookie
        $currentUser = $userRepository->findById($cookie->utente_id);

        // L'utente è considerato autenticato se esiste
        $isAuth = (bool) $currentUser;
    }
}

// Determina quale file PHP è stato richiesto
$currentFile = basename($_SERVER['SCRIPT_NAME']);

// Pagine che NON richiedono autenticazione
$publicPages = ['login.php', 'sign-up.php'];
$isPublicPage = in_array($currentFile, $publicPages, true);

// Se l'utente NON è autenticato e sta cercando di accedere a una pagina protetta → redirect al login
if (!$isAuth && !$isPublicPage) {
    header("Location: $loginUrl");
    exit;
}

// Se l'utente è autenticato e prova ad accedere a login o sign-up → redirect alla home
if ($isAuth && $isPublicPage) {
    header("Location: $homeUrl");
    exit;
}
