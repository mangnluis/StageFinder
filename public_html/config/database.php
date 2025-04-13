<?php
// Configuration de la base de données
define('DB_HOST', '193.203.168.188');
define('DB_NAME', 'u892507993_stagefinder');
define('DB_USER', 'u892507993_root');
define('DB_PASS', '!4;bxjzI?PwS');
define('DB_CHARSET', 'utf8mb4');

// Connexion à la base de données
function getDbConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true
    ];
    
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}