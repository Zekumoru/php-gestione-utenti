<?php
require_once __DIR__ . '/db/conn.php';
require_once __DIR__ . '/repositories/CookieRepository.php';

$projectRoot = realpath(__DIR__);
$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : null;

$baseWebPath = '';
if ($documentRoot && strncmp($projectRoot, $documentRoot, strlen($documentRoot)) === 0) {
    $baseWebPath = '/' . trim(str_replace($documentRoot, '', $projectRoot), '/');
}
$baseWebPath = $baseWebPath === '/' ? '' : $baseWebPath;
$redirectUrl = ($baseWebPath ?: '') . '/login.php';
$cookiePath = $baseWebPath === '' ? '/' : $baseWebPath;

if (isset($_COOKIE['credentials'])) {
    $cookieRepository = new CookieRepository($conn);
    $cookieRepository->deleteByToken($_COOKIE['credentials']);
}

setcookie('credentials', '', [
    'expires' => time() - 3600,
    'path' => $cookiePath,
    'httponly' => true,
    'samesite' => 'Lax',
]);
header("Location: $redirectUrl");
exit;
