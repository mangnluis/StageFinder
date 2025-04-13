<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="dashboard">
    <h1 class="dashboard-title mb-4">
        <i class="fas fa-tachometer-alt text-primary me-2"></i> Tableau de bord
    </h1>
    
    <!-- Bienvenue -->
    <div class="alert alert-info mb-4">
        <h4 class="alert-heading">Bienvenue, <?= htmlspecialchars($user['first_name']) ?> !</h4>
        <p>Voici un aperçu de vos activités sur StageFinder.</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-icon bg-primary text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5 class="ms-3 mb-0">Mes candidatures</h5>
                    </div>
                    
                    <?php 
                    $totalApplications = count($applications);
                    $pendingCount = 0;
                    $acceptedCount = 0;
                    $rejectedCount = 0;
                    
                    foreach ($applications as $app) {
                        if ($app['status'] === 'pending') $pendingCount++;
                        elseif ($app['status'] === 'accepted') $acceptedCount++;
                        elseif ($app['status'] === 'rejected') $rejectedCount++;
                    }
                    ?>
                    
                    <h2 class="dashboard-counter"><?= $totalApplications ?></h2>
                    
                    <div class="dashboard-stats">
                        <div class="stat">
                            <span class="stat-label">En attente</span>
                            <span class="stat-value badge bg-warning"><?= $pendingCount ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Acceptées</span>
                            <span class="stat-value badge bg-success"><?= $acceptedCount ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Refusées</span>
                            <span class="stat-value badge bg-danger"><?= $rejectedCount ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= url('/?page=applications&action=my') ?>" class="btn btn-sm btn-primary">
                        Voir toutes mes candidatures
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-icon bg-danger text-white">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h5 class="ms-3 mb-0">Ma wishlist</h5>
                    </div>
                    
                    <h2 class="dashboard-counter"><?= count($wishlist) ?></h2>
                    
                    <?php if (!empty($wishlist)): ?>
                        <div class="dashboard-list small">
                            <?php foreach (array_slice($wishlist, 0, 3) as $item): ?>
                                <div class="dashboard-list-item">
                                    <a href="<?= url('/?page=internships&action=view&id=' . $item['internship_id']) ?>">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                    <span class="text-muted"><?= htmlspecialchars($item['company_name']) ?></span>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (count($wishlist) > 3): ?>
                                <div class="dashboard-list-item text-center">
                                    <a href="<?= url('/?page=wishlist') ?>">Voir plus...</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune offre dans votre wishlist.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="<?= url('/?page=wishlist') ?>" class="btn btn-sm btn-danger">
                        Gérer ma wishlist
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-icon bg-success text-white">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h5 class="ms-3 mb-0">Offres disponibles</h5>
                    </div>
                    
                    <h2 class="dashboard-counter"><?= $internshipCount ?? count($latestInternships) ?></h2>
                    
                    <div class="dashboard-stats">
                        <div class="stat">
                            <span class="stat-label">Dernières 24h</span>
                            <span class="stat-value badge bg-info"><?= $recentStats['last24h'] ?? 0 ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Cette semaine</span>
                            <span class="stat-value badge bg-info"><?= $recentStats['lastWeek'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= url('/?page=internships') ?>" class="btn btn-sm btn-success">
                        Explorer les offres
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Boxes -->
    <div class="row">
        <!-- Latest Applications -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes dernières candidatures</h5>
                    <a href="<?= url('/?page=applications&action=my') ?>" class="btn btn-sm btn-outline-primary">Tout voir</a>
                </div>
                <div class="card-body">
                    <?php if (empty($applications)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="mb-0">Vous n'avez pas encore postulé à des offres de stage.</p>
                            <a href="<?= url('/?page=internships') ?>" class="btn btn-primary mt-3">
                                Découvrir les offres
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($applications, 0, 5) as $app): ?>
                                <a href="<?= url('/?page=applications&action=view&id=' . $app['id']) ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($app['title']) ?></h6>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($app['applied_at'])) ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small"><?= htmlspecialchars($app['company_name']) ?></p>
                                    <div>
                                        <span class="badge <?= $app['status'] === 'pending' ? 'bg-warning' : ($app['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                            <?= $app['status'] === 'pending' ? 'En attente' : ($app['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Internships -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dernières offres de stage</h5>
                    <a href="<?= url('/?page=internships') ?>" class="btn btn-sm btn-outline-primary">Tout voir</a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($latestInternships as $internship): ?>
                            <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($internship['title']) ?></h6>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($internship['created_at'])) ?></small>
                                </div>
                                <p class="mb-1 text-muted small"><?= htmlspecialchars($internship['company_name']) ?></p>
                                <?php if (!empty($internship['compensation'])): ?>
                                    <small class="text-success">
                                    <i class="fas fa-euro-sign"></i> <?= htmlspecialchars($internship['compensation']) ?>
                                    </small>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>