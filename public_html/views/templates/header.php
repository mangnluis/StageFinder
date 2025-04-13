<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StageFinder' ?> | Trouvez votre stage idéal</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/responsive.css') ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-primary sticky-top shadow-sm w-100">
        <div class="container">
            
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="<?= url('/') ?>">
                <i class="fas fa-laptop me-2"></i>
                <span>StageFinder</span>
            </a>
            
            <!-- Toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Left side links -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('home') ?>" href="<?= url('/') ?>">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= isActive('companies') ?>" href="<?= url('/?page=companies') ?>">
                            <i class="fas fa-building-user"></i> Entreprises
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= isActive('internships') ?>" href="<?= url('/?page=internships') ?>">
                            <i class="fas fa-search"></i> Offres de stage
                        </a>
                    </li>
                    
                    
                    <?php if (Auth::isLoggedIn()): ?>
                    
                        <?php if (Auth::isStudent() || Auth::isPilot()):?>
                            <li class="nav-item">
                                <a class="nav-link <?= isActive('dashboard') ?>" href="<?= url('/?page=dashboard') ?>">
                                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>


                <!-- Admin links -->
                <ul class="navbar-nav ms-auto">
                    <?php if (Auth::isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= isActive('admin') ?>" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-terminal"></i> Administration
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= url('/?page=dashboard') ?>">
                                        <i class="fas fa-tachometer-alt fa-fw"></i> Tableau de bord admin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/?page=users&action=list') ?>">
                                        <i class="fas fa-users fa-fw"></i> Gestion des utilisateurs
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/?page=admin&action=stats') ?>">
                                        <i class="fas fa-chart-bar fa-fw"></i> Statistiques
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Right side links -->
                <ul class="navbar-nav ms-auto">
                    <?php if (Auth::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= url('/?page=profile') ?>">
                                        <i class="fas fa-user fa-fw"></i> Mon profil
                                    </a>
                                </li>
                                <?php if (Auth::isAdmin()): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/switch.php?action=enable') ?>">
                                            <i class="fas fa-file-alt fa-fw"></i> SWITCH BOUTTON
                                        </a>
                                </li>
                                <?php endif; ?>
                                <?php if (Auth::isStudent()): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= url('/?page=applications&action=my') ?>">
                                            <i class="fas fa-file-alt fa-fw"></i> Mes candidatures
                                        </a>
                                    </li>
                                
                                    <a class="dropdown-item-heart" href="<?= url('/?page=wishlist') ?>">
                                        <i class="fas fa-heart" ></i> Wishlist 
                                        <?php 
                                        $wishlistModel = new WishlistModel();
                                        $wishlistCount = $wishlistModel->countItems(Auth::getUserId());
                                        
                                        if ($wishlistCount > 0): 
                                        ?>

                                        <span class="position-absolute top0 start-100 translate-middle badge rounded-pill bg-danger " style ="margin-left: -80px; margin-top: 12px;">
                                            <?= $wishlistCount ?>
                                        </span>
                                    
                                <?php endif; ?>
                                </a>
                        <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/?page=auth&action=logout') ?>">
                                        <i class="fas fa-sign-out-alt fa-fw"></i> Déconnexion
                                    </a>
                                </li>

                            </ul>
                        </li>

                    <?php
                    $notificationModel = new NotificationModel();
                    $unreadCount = $notificationModel->countUnread(Auth::getUserId());
                    $notifications = $notificationModel->getByUser(Auth::getUserId(), false, 5);
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge rounded-pill bg-danger"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 300px;">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <h6 class="dropdown-header m-0 p-0">Notifications</h6>
                                <?php if ($unreadCount > 0): ?>
                                    <a href="<?= url('/?page=notifications&action=markAllAsRead') ?>" class="text-decoration-none small">Marquer tout comme lu</a>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (empty($notifications)): ?>
                                <div class="dropdown-item text-center py-3">
                                    <span class="text-muted">Aucune notification</span>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notification): ?>
                                    <a href="<?= url('/?page=notifications&action=markAsRead&id=' . $notification['id']) ?>" class="dropdown-item <?= $notification['is_read'] ? '' : 'bg-light' ?> py-2">
                                        <div class="d-flex">
                                            <div class="me-2">
                                                <i class="fas fa-bell text-primary"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 small"><?= htmlspecialchars($notification['message']) ?></p>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?= url('/?page=notifications') ?>" class="dropdown-item text-center py-2">
                                    <small>Voir toutes les notifications</small>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/?page=auth&action=login') ?>">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                        <?php /*
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="<?= url('/?page=auth&action=register') ?>">
                                <i class="fas fa-user-plus"></i> Inscription
                            </a>
                        </li>*/
                        ?>
                    <?php endif; ?>


                    
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content Container -->
    <div class="container py-4">
        <!-- Flash Messages -->
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>