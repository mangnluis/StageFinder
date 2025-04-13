<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Administration' ?> | StageFinder</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Stylesheets - L'ordre est important -->
    <link rel="stylesheet" href="<?= asset('/css/common.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/responsive.css') ?>">
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= url('admin') ?>">
                <i class="fas fa-laptop me-2"></i>StageFinder<span class="ms-2 badge badge-light">Admin</span>
            </a>
            
            <div class="d-flex align-items-center">
                <!-- Admin Navigation -->
                <ul class="navbar-nav me-3">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('admin') && (!isset($_GET['action']) || $_GET['action'] === 'index') ? 'active' : '' ?>" href="<?= url('admin') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] === 'users' ? 'active' : '' ?>" href="<?= url('admin', 'users') ?>">
                            <i class="fas fa-users me-1"></i> Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('companies') ? 'active' : '' ?>" href="<?= url('companies') ?>">
                            <i class="fas fa-building-user me-1"></i> Entreprises
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('internships') ? 'active' : '' ?>" href="<?= url('internships') ?>">
                            <i class="fas fa-laptop me-1"></i> Offres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] === 'stats' ? 'active' : '' ?>" href="<?= url('admin', 'stats') ?>">
                            <i class="fas fa-chart-bar me-1"></i> Statistiques
                        </a>
                    </li>
                </ul>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?= url('profile') ?>">
                                <i class="fas fa-user fa-fw me-2"></i> Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= url('home') ?>">
                                <i class="fas fa-home fa-fw me-2"></i> Retour au site
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= url('auth', 'logout') ?>">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Admin Content -->
    <div class="container main-content">
        <!-- Flash Messages -->
        <?php if (isset($flash) && $flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>