<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=internships') ?>">Offres de stage</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>"><?= htmlspecialchars($internship['title']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Supprimer</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i> Confirmation de suppression
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <h4 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Attention !</h4>
                <p>Vous êtes sur le point de supprimer l'offre de stage <strong><?= htmlspecialchars($internship['title']) ?></strong> 
                   de l'entreprise <strong><?= htmlspecialchars($internship['company_name']) ?></strong>.</p>
                <p>Cette action supprimera également :</p>
                <ul>
                    <li>Toutes les candidatures associées à cette offre</li>
                    <li>Toutes les entrées en wishlist des étudiants pour cette offre</li>
                    <li>Toutes les compétences associées à cette offre</li>
                </ul>
                <p class="mb-0">Cette action est <strong>irréversible</strong> et ne pourra pas être annulée.</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-laptop text-primary me-2"></i> 
                                <?= htmlspecialchars($internship['title']) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong><i class="fas fa-building-user text-muted me-2"></i> Entreprise :</strong> 
                                <?= htmlspecialchars($internship['company_name']) ?>
                            </div>
                            
                            <?php if (!empty($internship['compensation'])): ?>
                            <div class="mb-3">
                                <strong><i class="fas fa-euro-sign text-muted me-2"></i> Rémunération :</strong> 
                                <?= number_format($internship['compensation'], 2, ',', ' ') ?> €
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($internship['start_date'])): ?>
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-alt text-muted me-2"></i> Période :</strong> 
                                Du <?= date('d/m/Y', strtotime($internship['start_date'])) ?>
                                <?= !empty($internship['end_date']) ? ' au ' . date('d/m/Y', strtotime($internship['end_date'])) : '' ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-clock text-muted me-2"></i> Publiée le :</strong> 
                                <?= date('d/m/Y', strtotime($internship['created_at'])) ?>
                            </div>
                            
                            <div>
                                <strong><i class="fas fa-align-left text-muted me-2"></i> Description :</strong>
                                <p class="mt-2">
                                    <?= nl2br(htmlspecialchars(substr($internship['description'], 0, 200))) ?>
                                    <?= (strlen($internship['description']) > 200) ? '...' : '' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center py-3">
                <h5 class="mb-4">Êtes-vous absolument sûr de vouloir supprimer cette offre de stage ?</h5>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?= url('/?page=internships&action=delete&id=' . $internship['id'] . '&confirm=yes') ?>" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash-alt me-2"></i> Oui, supprimer définitivement
                    </a>
                    <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-2"></i> Non, annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>