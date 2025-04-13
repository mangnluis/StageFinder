<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=users') ?>">Utilisateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></li>
        </ol>
    </nav>

    <!-- En-tête du profil -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 28px;">
                            <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h1 class="h2 mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                            <div class="d-flex flex-wrap align-items-center">
                                <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : ($user['role'] === 'pilot' ? 'bg-primary' : 'bg-success') ?> me-2">
                                    <?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                </span>
                                
                                <?php if ($user['role'] === 'student' && $studentInfo): ?>
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-graduation-cap text-primary me-1"></i> Promotion <?= htmlspecialchars($studentInfo['promotion'] ?? 'Non spécifiée') ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i> Inscrit le <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                        <?php if (Auth::isAdmin() || (Auth::isPilot() && $user['role'] === 'student')): ?>
                            <a href="<?= url('/?page=users&action=edit&id=' . $user['id']) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-pencil-alt me-2"></i> Modifier
                            </a>
                            <?php if (Auth::isAdmin() && $user['id'] != Auth::getUserId()): ?>
                                <a href="<?= url('/?page=users&action=delete&id=' . $user['id']) ?>" class="btn btn-outline-danger">
                                    <i class="fas fa-trash-alt me-2"></i> Supprimer
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Informations de contact -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i> Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <span class="text-muted d-block small">Email</span>
                                    <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                        <?php if ($user['role'] === 'student' && $studentInfo): ?>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small">Promotion</span>
                                        <strong><?= htmlspecialchars($studentInfo['promotion'] ?? 'Non spécifiée') ?></strong>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                        
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <span class="text-muted d-block small">Date d'inscription</span>
                                    <strong><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Wishlists -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heart text-primary me-2"></i> Wishlist
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($wishlists)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                            <p class="mb-0">Aucune offre dans la wishlist.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wishlists as $offer): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= url('/?page=internships&action=view&id=' . $offer['internship_id']) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($offer['internship_title']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?= url('/?page=companies&action=view&id=' . $offer['company_id']) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($offer['company_name']) ?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                            <a href="<?= url('/?page=wishlist&action=remove&student_id=' . $user['id'] . '&internship_id=' . $offer['internship_id']) ?>" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Retirer
                                            </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($user['role'] === 'student'): ?>
            <div class="col-lg-8">
                <!-- Statistiques -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i> Statistiques des candidatures
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Récupérer les statistiques de candidature
                        $db = Database::getInstance();
                        $stats = [
                            'total' => $db->fetchColumn(
                                "SELECT COUNT(*) FROM applications WHERE student_id = ?",
                                [$user['id']]
                            ),
                            'pending' => $db->fetchColumn(
                                "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'pending'",
                                [$user['id']]
                            ),
                            'accepted' => $db->fetchColumn(
                                "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'accepted'",
                                [$user['id']]
                            ),
                            'rejected' => $db->fetchColumn(
                                "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'rejected'",
                                [$user['id']]
                            ),
                            'wishlist' => $db->fetchColumn(
                                "SELECT COUNT(*) FROM wishlist WHERE student_id = ?",
                                [$user['id']]
                            )
                        ];
                        ?>
                        
                        <div class="row mb-4">
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2"><?= $stats['total'] ?></div>
                                        <div class="text-muted">Total des candidatures</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2"><?= $stats['pending'] ?></div>
                                        <div class="text-warning">En attente</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2"><?= $stats['accepted'] ?></div>
                                        <div class="text-success">Acceptées</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm h-100 bg-danger bg-opacity-10">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2"><?= $stats['rejected'] ?></div>
                                        <div class="text-danger">Refusées</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2"><?= $stats['wishlist'] ?></div>
                                        <div class="text-info">Offres en wishlist</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="progress-stacked" style="height: 30px;">
                                    <?php 
                                    $totalApps = $stats['total'] > 0 ? $stats['total'] : 1;
                                    $pendingPercent = round(($stats['pending'] / $totalApps) * 100);
                                    $acceptedPercent = round(($stats['accepted'] / $totalApps) * 100);
                                    $rejectedPercent = round(($stats['rejected'] / $totalApps) * 100);
                                    ?>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $pendingPercent ?>%" 
                                        aria-valuenow="<?= $pendingPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $pendingPercent ?>%
                                    </div>
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $acceptedPercent ?>%" 
                                        aria-valuenow="<?= $acceptedPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $acceptedPercent ?>%
                                    </div>
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $rejectedPercent ?>%" 
                                        aria-valuenow="<?= $rejectedPercent ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $rejectedPercent ?>%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-warning">En attente</small>
                                    <small class="text-success">Acceptées</small>
                                    <small class="text-danger">Refusées</small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                            <div class="text-center mt-3">
                                <a href="<?= url('/?page=statistics&action=student&id=' . $user['id']) ?>" class="btn btn-primary">
                                    <i class="fas fa-chart-line me-2"></i> Voir les statistiques détaillées
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Dernières candidatures -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt text-primary me-2"></i> Dernières candidatures
                        </h5>
                        <a href="<?= url('/?page=applications&action=student&id=' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($applications)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-0">Aucune candidature pour le moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Offre</th>
                                            <th>Entreprise</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($applications as $application): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= url('/?page=internships&action=view&id=' . $application['internship_id']) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($application['internship_title']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= url('/?page=companies&action=view&id=' . $application['company_id']) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($application['company_name']) ?>
                                                    </a>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($application['applied_at'])) ?></td>
                                                <td>
                                                    <span class="badge <?= $application['status'] === 'pending' ? 'bg-warning' : ($application['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                        <?= $application['status'] === 'pending' ? 'En attente' : ($application['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= url('/?page=applications&action=view&id=' . $application['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>