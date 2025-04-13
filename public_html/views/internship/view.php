<?php include VIEWS_PATH . '/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=internships') ?>">Offres de stage</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($internship['title']) ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0"><?= htmlspecialchars($internship['title']) ?></h1>
                
                <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-1"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminActionsDropdown">
                            <li>
                                <a class="dropdown-item" href="<?= url('/?page=internships&action=edit&id=' . $internship['id']) ?>">
                                    <i class="fas fa-edit fa-fw me-2"></i> Modifier l'offre
                                </a>
                            </li>
                            <?php if (Auth::isAdmin()): ?>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= url('/?page=internships&action=delete&id=' . $internship['id']) ?>">
                                        <i class="fas fa-trash fa-fw me-2"></i> Supprimer l'offre
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="company-info mb-4">
                    <div class="d-flex align-items-center">
                        <div class="company-icon bg-light rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-building-user fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">
                                <a href="<?= url('/?page=companies&action=view&id=' . $internship['company_id']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($internship['company_name']) ?>
                                </a>
                            </h5>
                            <?php if (!empty($internship['location'])): ?>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= htmlspecialchars($internship['location']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5 class="mb-3">Description</h5>
                    <div class="internship-description">
                        <?= nl2br(htmlspecialchars($internship['description'])) ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5 class="mb-3">Compétences requises</h5>
                    <?php if (empty($skills)): ?>
                        <p class="text-muted">Aucune compétence spécifique mentionnée.</p>
                    <?php else: ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($skills as $skill): ?>
                                <span class="badge bg-primary"><?= htmlspecialchars($skill['name']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations complémentaires</h5>
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($internship['compensation'])): ?>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-euro-sign me-2"></i> Rémunération</span>
                                        <strong><?= number_format($internship['compensation'], 2, ',', ' ') ?> €</strong>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (!empty($internship['start_date'])): ?>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-calendar-alt me-2"></i> Date de début</span>
                                        <strong><?= date('d/m/Y', strtotime($internship['start_date'])) ?></strong>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (!empty($internship['end_date'])): ?>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-calendar-check me-2"></i> Date de fin</span>
                                        <strong><?= date('d/m/Y', strtotime($internship['end_date'])) ?></strong>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span><i class="fas fa-clock me-2"></i> Date de publication</span>
                                    <strong><?= date('d/m/Y', strtotime($internship['created_at'])) ?></strong>
                                </div>
                            </li>
                            
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span><i class="fas fa-users me-2"></i> Candidatures</span>
                                    <strong><?= $applicationsCount ?></strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <?php if (!empty($internship['responsibilities'])): ?>
                            <h5 class="mb-3">Responsabilités</h5>
                            <div class="internship-responsibilities mb-3">
                                <?= nl2br(htmlspecialchars($internship['responsibilities'])) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($internship['requirements'])): ?>
                            <h5 class="mb-3">Prérequis</h5>
                            <div class="internship-requirements">
                                <?= nl2br(htmlspecialchars($internship['requirements'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="internship-actions mt-4">
                    <?php if (Auth::isStudent() || Auth::isAdmin() || Auth::isPilot()): ?>
                        <div class="d-flex gap-3">
                            <?php if (!$hasApplied): ?>
                                <a href="<?= url('/?page=internships&action=apply&id=' . $internship['id']) ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Postuler à cette offre
                                </a>
                            <?php else: ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Vous avez déjà postulé
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($isInWishlist): ?>
                                <a href="<?= url('/?page=wishlist&action=remove&id=' . $internship['id']) ?>" class="btn btn-outline-danger">
                                    <i class="fas fa-heart-broken me-2"></i> Retirer de ma wishlist
                                </a>
                            <?php else: ?>
                                <a href="<?= url('/?page=wishlist&action=add&id=' . $internship['id']) ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-heart me-2"></i> Ajouter à ma wishlist
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Candidatures (Pour Admin/Pilote) -->
        <?php if ((Auth::isAdmin() || Auth::isPilot()) && !empty($applications)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Candidatures (<?= count($applications) ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/?page=admin&action=edit-user&id=' . $app['student_id']) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($app['applied_at'])) ?></td>
                                        <td>
                                            <span class="badge <?= $app['status'] === 'pending' ? 'bg-warning' : ($app['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                <?= $app['status'] === 'pending' ? 'En attente' : ($app['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=applications&action=view&id=' . $app['id']) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <!-- Entreprise -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building-user me-2"></i> À propos de l'entreprise</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($company['name']) ?></h5>
                
                <?php if ($companyRatingCount > 0): ?>
                    <div class="rating-stars mb-2">
                        <?php 
                        $rating = round($companyRating);
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <i class="fas fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                        <span class="ms-2 text-muted">(<?= $companyRatingCount ?> avis)</span>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-2">Aucune évaluation</p>
                <?php endif; ?>
                
                <?php if (!empty($company['description'])): ?>
                    <p class="card-text">
                        <?= strlen($company['description']) > 200 
                            ? htmlspecialchars(substr($company['description'], 0, 200)) . '...' 
                            : htmlspecialchars($company['description']) ?>
                    </p>
                <?php else: ?>
                    <p class="text-muted">Aucune description disponible.</p>
                <?php endif; ?>
                
                <div class="company-contact mt-3">
                    <?php if (!empty($company['contact_email'])): ?>
                        <p class="mb-2">
                            <i class="fas fa-envelope me-2 text-muted"></i>
                            <a href="mailto:<?= htmlspecialchars($company['contact_email']) ?>"><?= htmlspecialchars($company['contact_email']) ?></a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['contact_phone'])): ?>
                        <p class="mb-2">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            <?= htmlspecialchars($company['contact_phone']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['website'])): ?>
                        <p class="mb-0">
                            <i class="fas fa-globe me-2 text-muted"></i>
                            <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank"><?= htmlspecialchars($company['website']) ?></a>
                        </p>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3">
                    <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-info-circle me-2"></i> Voir le profil complet
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Offres similaires -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-laptop me-2"></i> Offres similaires</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($similarInternships)): ?>
                    <div class="p-3 text-center text-muted">
                        Aucune offre similaire disponible.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($similarInternships as $similar): ?>
                            <a href="<?= url('/?page=internships&action=view&id=' . $similar['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($similar['title']) ?></h6>
                                </div>
                                <p class="mb-1 text-muted small"><?= htmlspecialchars($similar['company_name']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Partager l'offre -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i> Partager cette offre</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-around social-share">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('/?page=internships&action=view&id=' . $internship['id'])) ?>" target="_blank" class="text-decoration-none">
                        <i class="fab fa-facebook fa-2x text-primary"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(url('/?page=internships&action=view&id=' . $internship['id'])) ?>&text=<?= urlencode('Découvrez cette offre de stage : ' . $internship['title']) ?>" target="_blank" class="text-decoration-none">
                        <i class="fab fa-twitter fa-2x text-info"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(url('/?page=internships&action=view&id=' . $internship['id'])) ?>" target="_blank" class="text-decoration-none">
                        <i class="fab fa-linkedin fa-2x text-primary"></i>
                    </a>
                    <a href="mailto:?subject=<?= urlencode('Offre de stage : ' . $internship['title']) ?>&body=<?= urlencode('Découvrez cette offre de stage : ' . $internship['title'] . ' chez ' . $internship['company_name'] . "\n\n" . url('/?page=internships&action=view&id=' . $internship['id'])) ?>" class="text-decoration-none">
                        <i class="fas fa-envelope fa-2x text-danger"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>