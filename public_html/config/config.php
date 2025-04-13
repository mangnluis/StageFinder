<?php
// Détection automatique de l'environnement
$currentPath = $_SERVER['REQUEST_URI'];
$isDevEnvironment = (strpos($currentPath, '/dev_site/') !== false) || 
                  (isset($_COOKIE['test_version']) && $_COOKIE['test_version'] == '1');

// Configuration spécifique à l'environnement
if ($isDevEnvironment) {
    define('ENV', 'development');
    define('ENV_PATH', '/dev_site');
} else {
    define('ENV', 'production');
    define('ENV_PATH', '');
}

// Configuration générale
define('APP_NAME', 'StageFinder');

// Détection automatique de l'URL de base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . ENV_PATH;

// Configuration des chemins et URLs
define('BASE_URL', $baseUrl);
define('STATIC_URL', $baseUrl);
define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views');
define('UPLOADS_PATH', ROOT_PATH . '/public/uploads');
define('ITEMS_PER_PAGE', 10);

// Mode debug (à désactiver en production)
define('DEBUG', true);
if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

// Inclure la configuration de la base de données
require_once 'database.php';

// Démarrer la session
session_start();

// Fonctions utilitaires
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

function url($path) {
    return BASE_URL . $path;
}

function asset($path) {
    return BASE_URL . '/public' . $path;
}

function view($name, $data = []) {
    extract($data);
    require VIEWS_PATH . '/' . $name . '.php';
}

function flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function isActive($page) {
    $currentPage = $_GET['page'] ?? 'home';
    return $currentPage === $page ? 'active' : '';
}

// Indicateur de mode développement
function dev_mode_indicator() {
    if (ENV === 'development') {
        echo '<div style="position:fixed; top:0; right:0; background-color:#ff6b6b; color:white; padding:5px 10px; z-index:9999; font-weight:bold;">MODE DÉVELOPPEMENT</div>';
    }
}