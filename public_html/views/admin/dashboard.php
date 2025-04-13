<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid admin-dashboard">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6">
                        <i class="fas fa-tachometer-alt text-primary me-3"></i>Tableau de bord Administrateur
                    </h1>
                    <p class="text-muted">Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                </div>
                
                <div>
                    <a href="<?= url('/?page=admin&action=create-user') ?>" class="btn btn-primary me-2">
                        <i class="fas fa-user-plus me-2"></i>Créer un utilisateur
                    </a>
                    <a href="<?= url('/?page=internships&action=create') ?>" class="btn btn-success">
                        <i class="fas fa-laptop me-2"></i>Nouvelle offre
                    </a>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-0">Utilisateurs</h5>
                                <p class="display-6 mb-0"><?= $stats['students'] + $stats['pilots'] + 1 ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle p-3 me-3">
                                <i class="fas fa-building-user fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-0">Entreprises</h5>
                                <p class="display-6 mb-0"><?= $stats['companies'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle p-3 me-3">
                                <i class="fas fa-laptop fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-0">Offres de stage</h5>
                                <p class="display-6 mb-0"><?= $stats['internships'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-info text-white rounded-circle p-3 me-3">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-0">Candidatures</h5>
                                <p class="display-6 mb-0"><?= $stats['applications'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières candidatures -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2 text-primary"></i>Dernières candidatures
                            </h5>
                            <a href="<?= url('/?page=applications') ?>" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($latestApplications as $app): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= url('/?page=users&action=view&id=' . $app['student_id']) ?>">
                                                    <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?= url('/?page=internships&action=view&id=' . $app['internship_id']) ?>">
                                                    <?= htmlspecialchars($app['internship_title']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($app['company_name']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($app['applied_at'])) ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?= $app['status'] === 'pending' ? 'bg-warning' : 
                                                        ($app['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                    <?= $app['status'] === 'pending' ? 'En attente' : 
                                                        ($app['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Utilisateurs récents -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2 text-primary"></i>Derniers utilisateurs
                            </h5>
                            <a href="<?= url('/?page=admin&action=users') ?>" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($latestUsers as $user): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                    </div>
                                    <span class="badge 
                                        <?= $user['role'] === 'admin' ? 'bg-danger' : 
                                            ($user['role'] === 'pilot' ? 'bg-warning' : 'bg-success') ?>">
                                        <?= $user['role'] === 'admin' ? 'Admin' : 
                                            ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>