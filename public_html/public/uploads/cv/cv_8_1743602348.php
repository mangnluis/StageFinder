<?php

// Charger les configurations globales
require_once '../config/config.php'; // Fichier contenant les constantes et paramètres globaux
require_once '../config/routes.php'; // Fichier contenant les routes de l'application

// Charger les classes principales
require_once '../core/App.php'; // Classe principale qui gère les routes
require_once '../core/Controller.php'; // Classe de base pour les contrôleurs
require_once '../core/Database.php'; // Classe pour la connexion à la base de données
require_once '../core/Model.php'; // Classe de base pour les modèles
require_once '../core/View.php'; // Classe pour gérer les vues

