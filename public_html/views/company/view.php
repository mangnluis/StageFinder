<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=companies') ?>">Entreprises</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($company['name']) ?></li>
        </ol>
    </nav>

    <!-- En-tête de la fiche entreprise -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <?php if (!empty($company['logo'])): ?>
                            <div class="company-logo me-3">
                                <img src="<?= asset('/uploads/logos/' . $company['logo']) ?>" alt="Logo <?= htmlspecialchars($company['name']) ?>" class="img-fluid rounded" style="max-width: 80px; max-height: 80px;">
                            </div>
                        <?php else: ?>
                            <div class="company-avatar rounded me-3 d-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 80px;">
                                <i class="fas fa-building-user fa-2x text-primary"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="h2 mb-1"><?= htmlspecialchars($company['name']) ?></h1>
                            <div class="d-flex flex-wrap align-items-center">
                                <?php if (!empty($company['industry'])): ?>
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-industry text-primary me-1"></i> <?= htmlspecialchars($company['industry']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($company['company_size'])): ?>
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-users text-primary me-1"></i> <?= htmlspecialchars($company['company_size']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($company['location'])): ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i> <?= htmlspecialchars($company['location']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                        <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                            <a href="<?= url('/?page=companies&action=edit&id=' . $company['id']) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i> Modifier
                            </a>
                            <?php if (Auth::isAdmin()): ?>
                                <a href="<?= url('/?page=companies&action=delete&id=' . $company['id']) ?>" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if (Auth::isStudent() && !$userRating): ?>
                            <a href="<?= url('/?page=companies&action=rate&id=' . $company['id']) ?>" class="btn btn-outline-warning">
                                <i class="fas fa-star me-2"></i> Évaluer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Description et informations de l'entreprise -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i> À propos de l'entreprise
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($company['description'])): ?>
                        <div class="company-description mb-4">
                            <?= nl2br(htmlspecialchars($company['description'])) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Aucune description disponible pour cette entreprise.</p>
                    <?php endif; ?>
                    
                    <h5 class="mt-4 mb-3 border-bottom pb-2">Contact et localisation</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <?php if (!empty($company['contact_email'])): ?>
                                    <li class="mb-3">
                                        <div class="d-flex">
                                            <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div>
                                                <span class="text-muted d-block small">Email</span>
                                                <a href="mailto:<?= htmlspecialchars($company['contact_email']) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($company['contact_email']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (!empty($company['contact_phone'])): ?>
                                    <li class="mb-3">
                                        <div class="d-flex">
                                            <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div>
                                                <span class="text-muted d-block small">Téléphone</span>
                                                <a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $company['contact_phone'])) ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($company['contact_phone']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <?php if (!empty($company['website'])): ?>
                                    <li class="mb-3">
                                        <div class="d-flex">
                                            <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                                <i class="fas fa-globe"></i>
                                            </div>
                                            <div>
                                                <span class="text-muted d-block small">Site web</span>
                                                <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank" class="text-decoration-none">
                                                    <?= htmlspecialchars($company['website']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (!empty($company['address'])): ?>
                                    <li class="mb-3">
                                        <div class="d-flex">
                                            <div class="icon-square bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>
                                                <span class="text-muted d-block small">Adresse</span>
                                                <address class="mb-0">
                                                    <?= htmlspecialchars($company['address']) ?><br>
                                                    <?php if (!empty($company['postal_code']) || !empty($company['city'])): ?>
                                                        <?= htmlspecialchars($company['postal_code'] ?? '') ?> <?= htmlspecialchars($company['city'] ?? '') ?>
                                                    <?php endif; ?>
                                                </address>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Offres de stage de l'entreprise -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-laptop text-primary me-2"></i> Offres de stage
                    </h5>
                    <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                        <a href="<?= url('/?page=internships&action=create&company_id=' . $company['id']) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Ajouter une offre
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($internships)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-laptop fa-3x text-muted mb-3"></i>
                            <p class="mb-0">Aucune offre de stage disponible pour cette entreprise.</p>
                            <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                                <a href="<?= url('/?page=internships&action=create&company_id=' . $company['id']) ?>" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus-circle me-2"></i> Ajouter une offre
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($internships as $internship): ?>
                                <div class="col-md-6">
                                    <div class="card h-100 internship-card border">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="text-decoration-none stretched-link">
                                                    <?= htmlspecialchars($internship['title']) ?>
                                                </a>
                                            </h5>
                                            <p class="card-text">
                                                <?= nl2br(htmlspecialchars(substr($internship['description'], 0, 100))) ?>
                                                <?= (strlen($internship['description']) > 100) ? '...' : '' ?>
                                            </p>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                <?php if (!empty($internship['compensation'])): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-euro-sign me-1"></i> <?= number_format($internship['compensation'], 2, ',', ' ') ?> €
                                                    </span>
                                                <?php endif; ?>

                                                <?php if (!empty($internship['location'])): ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($internship['location']) ?>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if (!empty($internship['start_date'])): ?>
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($internship['start_date'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Posté le <?= date('d/m/Y', strtotime($internship['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($internships) > 4): ?>
                            <div class="text-center mt-4">
                                <a href="<?= url('/?page=internships&company_id=' . $company['id']) ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i> Voir toutes les offres
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Évaluations -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i> Évaluations
                    </h5>
                    <?php if (Auth::isStudent() && !$userRating): ?>
                        <a href="<?= url('/?page=companies&action=rate&id=' . $company['id']) ?>" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-star me-1"></i> Évaluer
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($averageRating): ?>
                        <div class="text-center mb-4">
                            <div class="display-4 fw-bold mb-2"><?= number_format($averageRating, 1) ?></div>
                            <div class="rating-stars mb-2">
                                <?php 
                                $rating = round($averageRating);
                                for ($i = 1; $i <= 5; $i++): 
                                ?>
                                    <i class="fas fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?> fa-lg"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="text-muted"><?= count($ratings) ?> évaluation(s)</div>
                        </div>
                        
                        <div class="rating-bars mb-4">
                            <?php
                            $ratingCounts = [0, 0, 0, 0, 0];
                            foreach ($ratings as $r) {
                                $ratingCounts[$r['rating'] - 1]++;
                            }
                            $totalRatings = count($ratings);
                            
                            for ($i = 5; $i >= 1; $i--):
                                $count = $ratingCounts[$i - 1];
                                $percent = $totalRatings > 0 ? round(($count / $totalRatings) * 100) : 0;
                            ?>
                                <div class="rating-bar d-flex align-items-center mb-1">
                                    <div class="rating-label me-2"><?= $i ?> <i class="fas fa-star text-warning"></i></div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="rating-count ms-2"><?= $count ?></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="far fa-star fa-3x text-muted mb-3"></i>
                            <p class="mb-3">Aucune évaluation pour le moment.</p>
                            <?php if (Auth::isStudent()): ?>
                                <a href="<?= url('/?page=companies&action=rate&id=' . $company['id']) ?>" class="btn btn-warning">
                                    <i class="fas fa-star me-2"></i> Soyez le premier à évaluer
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Votre évaluation -->
            <?php if (Auth::isStudent() && $userRating): ?>
                <div class="card shadow-sm border-0 mb-4 border-warning">
                    <div class="card-header bg-warning bg-opacity-10 text-warning">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-comment me-2"></i> Votre évaluation
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="rating-stars mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $userRating['rating'] ? 'text-warning' : 'text-muted' ?> fa-lg"></i>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if (!empty($userRating['comment'])): ?>
                            <div class="mb-3 p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($userRating['comment'])) ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Aucun commentaire.</p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Évalué le <?= date('d/m/Y', strtotime($userRating['rated_at'])) ?></small>
                            <div>
                                <a href="<?= url('/?page=companies&action=rate&id=' . $company['id']) ?>" class="btn btn-sm btn-outline-warning me-2">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                                <a href="<?= url('/?page=companies&action=delete-rating&id=' . $company['id']) ?>" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash me-1"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Liste des avis récents -->
            <?php if (!empty($ratings)): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-comments text-primary me-2"></i> Avis des étudiants
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php 
                            $displayedRatings = 0;
                            foreach ($ratings as $rating): 
                                // Skip user's own rating as it's displayed separately
                                if ($userRating && $rating['student_id'] == Auth::getUserId()) continue;
                                // Limit to 3 reviews
                                if ($displayedRatings >= 3) break;
                                $displayedRatings++;
                            ?>
                                <div class="list-group-item border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                <?= strtoupper(substr($rating['first_name'], 0, 1) . substr($rating['last_name'], 0, 1)) ?>
                                            </div>
                                            <h6 class="mb-0"><?= htmlspecialchars($rating['first_name'] . ' ' . substr($rating['last_name'], 0, 1) . '.') ?></h6>
                                        </div>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($rating['rated_at'])) ?></small>
                                    </div>
                                    <div class="rating-stars mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $rating['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (!empty($rating['comment'])): ?>
                                        <p class="mb-0 small"><?= nl2br(htmlspecialchars($rating['comment'])) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($ratings) > 3): ?>
                            <div class="text-center py-3">
                                <a href="<?= url('/?page=companies&action=reviews&id=' . $company['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    Voir tous les avis (<?= count($ratings) ?>)
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>