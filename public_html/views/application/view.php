<?php include VIEWS_PATH . '/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <?php if ($isOwner): ?>
            <li class="breadcrumb-item"><a href="<?= url('/?page=applications&action=my') ?>">Mes candidatures</a></li>
        <?php else: ?>
            <li class="breadcrumb-item"><a href="<?= url('/?page=applications') ?>">Candidatures</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page">Détails de la candidature</li>
    </ol>
</nav>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Candidature pour : <?= htmlspecialchars($application['internship_title']) ?></h1>
        
        <?php if (!$isOwner && (Auth::isAdmin() || Auth::isPilot())): ?>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog me-1"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown">
                    <li>
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="fas fa-edit fa-fw me-2"></i> Changer le statut
                        </button>
                    </li>
                    <li>
                        <a class="dropdown-item" href="mailto:<?= htmlspecialchars($application['email']) ?>">
                            <i class="fas fa-envelope fa-fw me-2"></i> Contacter l'étudiant
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <!-- Informations sur la candidature -->
                <div class="mb-4">
                    <h5 class="mb-3">Informations sur la candidature</h5>
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>Statut</span>
                                <span class="badge <?= $application['status'] === 'pending' ? 'bg-warning' : ($application['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                    <?= $application['status'] === 'pending' ? 'En attente' : ($application['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                </span>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>Date de candidature</span>
                                <span><?= date('d/m/Y H:i', strtotime($application['applied_at'])) ?></span>
                            </div>
                        </div>
                        <?php if (!empty($application['cv_filename'])): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>CV</span>
                                    <a href="<?= asset('/uploads/cv/' . $application['cv_filename']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> Voir le CV
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Informations sur l'offre -->
                <div class="mb-4">
                    <h5 class="mb-3">Détails de l'offre</h5>
                    <div class="card internship-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($application['internship_title']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($application['company_name']) ?></h6>
                            <a href="<?= url('/?page=internships&action=view&id=' . $application['internship_id']) ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-eye me-1"></i> Voir l'offre complète
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Informations sur l'étudiant -->
                <div class="mb-4">
                    <h5 class="mb-3">Informations sur l'étudiant</h5>
                    <div class="card student-card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle bg-primary text-white me-3">
                                    <?= strtoupper(substr($application['first_name'], 0, 1) . substr($application['last_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></h5>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($application['email']) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                                <a href="<?= url('/?page=admin&action=edit-user&id=' . $application['student_id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user me-1"></i> Voir le profil complet
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Lettre de motivation -->
                <div class="mb-4">
                    <h5 class="mb-3">Lettre de motivation</h5>
                    <div class="motivation-letter p-3 border rounded">
                        <?= nl2br(htmlspecialchars($application['motivation_letter'])) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-between">
            <?php if ($isOwner): ?>
                <a href="<?= url('/?page=applications&action=my') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à mes candidatures
                </a>
            <?php else: ?>
                <a href="<?= url('/?page=applications') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux candidatures
                </a>
            <?php endif; ?>
            
            <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                <?php if ($application['status'] === 'pending'): ?>
                    <div>
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#acceptModal">
                            <i class="fas fa-check me-2"></i> Accepter
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i> Refuser
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals pour les administrateurs et pilotes -->
<?php if (Auth::isAdmin() || Auth::isPilot()): ?>
    <!-- Modal de changement de statut -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= url('/?page=applications&action=update-status&id=' . $application['id']) ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Changer le statut de la candidature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Nouveau statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?= $application['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                <option value="accepted" <?= $application['status'] === 'accepted' ? 'selected' : '' ?>>Acceptée</option>
                                <option value="rejected" <?= $application['status'] === 'rejected' ? 'selected' : '' ?>>Refusée</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal d'acceptation -->
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= url('/?page=applications&action=update-status&id=' . $application['id']) ?>" method="post">
                    <input type="hidden" name="status" value="accepted">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acceptModalLabel">Accepter la candidature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vous êtes sur le point d'accepter la candidature de <strong><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></strong> pour l'offre <strong><?= htmlspecialchars($application['internship_title']) ?></strong>.</p>
                        <p>L'étudiant sera informé de votre décision.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Accepter la candidature</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal de refus -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= url('/?page=applications&action=update-status&id=' . $application['id']) ?>" method="post">
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Refuser la candidature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vous êtes sur le point de refuser la candidature de <strong><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></strong> pour l'offre <strong><?= htmlspecialchars($application['internship_title']) ?></strong>.</p>
                        <p>L'étudiant sera informé de votre décision.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Refuser la candidature</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>