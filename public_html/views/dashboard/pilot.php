<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="fas fa-tachometer-alt text-primary me-2"></i> Tableau de bord pilote
        </h1>
        <a href="<?= url('/?page=internships&action=create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Créer une offre de stage
        </a>
    </div>

    <!-- Message de bienvenue -->
    <div class="alert alert-info mb-4">
        <h4 class="alert-heading">Bienvenue, <?= htmlspecialchars($user['first_name']) ?> !</h4>
        <p>Vous pouvez ici suivre les candidatures de vos étudiants et gérer vos offres de stage.</p>
    </div>

    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-7">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 text-center bg-light">
                                <h3 class="text-primary"><?= $stats['internships'] ?></h3>
                                <p class="mb-0">Offres de stage</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 text-center bg-light">
                                <h3 class="text-primary"><?= $stats['applications'] ?></h3>
                                <p class="mb-0">Candidatures</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 text-center bg-light">
                                <h3 class="text-warning"><?= $stats['statusStats']['pending'] ?? 0 ?></h3>
                                <p class="mb-0">En attente</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 text-center bg-light">
                                <h3 class="text-success"><?= $stats['statusStats']['accepted'] ?? 0 ?></h3>
                                <p class="mb-0">Acceptées</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 text-center bg-light">
                                <h3 class="text-danger"><?= $stats['statusStats']['rejected'] ?? 0 ?></h3>
                                <p class="mb-0">Refusées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="col-md-5">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-bolt text-primary me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= url('/?page=internships&action=create') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> Créer une offre de stage
                        </a>
                        <a href="<?= url('/?page=internships&action=my') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-list-ul me-2 text-primary"></i> Mes offres de stage
                        </a>
                        <a href="<?= url('/?page=users&action=list') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2 text-primary"></i> Liste des étudiants
                        </a>
                        <a href="<?= url('/?page=profile') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2 text-primary"></i> Mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières candidatures -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt text-primary me-2"></i>Dernières candidatures</h5>
                    <a href="<?= url('/?page=applications') ?>" class="btn btn-sm btn-outline-primary">
                        Voir toutes les candidatures
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($latestApplications)): ?>
                        <div class="alert alert-info m-3">
                            <i class="fas fa-info-circle me-2"></i> Aucune candidature pour le moment.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($latestApplications as $application): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></td>
                                            <td>
                                                <a href="<?= url('/?page=internships&action=view&id=' . $application['internship_id']) ?>">
                                                    <?= htmlspecialchars($application['internship_title']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($application['company_name']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($application['applied_at'])) ?></td>
                                            <td>
                                                <span class="badge <?= $application['status'] === 'pending' ? 'bg-warning' : ($application['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                    <?= $application['status'] === 'pending' ? 'En attente' : ($application['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= url('/?page=applications&action=view&id=' . $application['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Détails
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
    </div>

    <!-- Mes dernières offres de stage -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0"><i class="fas fa-laptop text-primary me-2"></i>Mes dernières offres de stage</h5>
                    <a href="<?= url('/?page=internships&action=my') ?>" class="btn btn-sm btn-outline-primary">
                        Voir toutes mes offres
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($latestInternships)): ?>
                        <div class="alert alert-warning m-3">
                            <i class="fas fa-exclamation-triangle me-2"></i> Vous n'avez pas encore créé d'offres de stage.
                            <a href="<?= url('/?page=internships&action=create') ?>" class="alert-link">Créer une offre</a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($latestInternships as $internship): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>">
                                                    <?= htmlspecialchars($internship['title']) ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted"><?= htmlspecialchars($internship['company_name']) ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary rounded-pill">
                                                <?= $internship['application_count'] ?> candidature(s)
                                            </span>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    Créée le <?= date('d/m/Y', strtotime($internship['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>