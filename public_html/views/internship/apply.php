<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Postuler à l'offre : <?= htmlspecialchars($internship['title']) ?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Formulaire de candidature</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>?page=internships&action=apply&id=<?= $internship['id'] ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="motivation_letter" class="form-label">Lettre de motivation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivation_letter" name="motivation_letter" rows="10" required></textarea>
                        <div class="form-text">Expliquez pourquoi vous êtes intéressé(e) par cette offre et quels sont vos atouts.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV (PDF, DOC, DOCX)</label>
                        <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx">
                        <div class="form-text">Taille maximale : 2 Mo</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                        <a href="<?= BASE_URL ?>?page=internships&action=view&id=<?= $internship['id'] ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Détails de l'offre</h5>
            </div>
            <div class="card-body">
                <h6><?= htmlspecialchars($internship['title']) ?></h6>
                <p class="text-muted"><?= htmlspecialchars($internship['company_name']) ?></p>
                
                <div class="mb-3">
                    <?php foreach ($skills as $skill): ?>
                        <span class="badge bg-primary me-1"><?= htmlspecialchars($skill['name']) ?></span>
                    <?php endforeach; ?>
                </div>
                
                <ul class="list-unstyled">
                    <?php if (!empty($internship['compensation'])): ?>
                        <li><strong>Rémunération:</strong> <?= number_format($internship['compensation'], 2, ',', ' ') ?> €</li>
                    <?php endif; ?>
                    
                    <?php if (!empty($internship['start_date'])): ?>
                        <li><strong>Date de début:</strong> <?= date('d/m/Y', strtotime($internship['start_date'])) ?></li>
                    <?php endif; ?>
                    
                    <?php if (!empty($internship['end_date'])): ?>
                        <li><strong>Date de fin:</strong> <?= date('d/m/Y', strtotime($internship['end_date'])) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Conseils pour votre candidature</h5>
            </div>
            <div class="card-body">
                <ul>
                    <li>Personnalisez votre lettre de motivation pour cette offre spécifique</li>
                    <li>Mettez en avant vos compétences et expériences pertinentes</li>
                    <li>Expliquez pourquoi vous êtes intéressé(e) par cette entreprise</li>
                    <li>Relisez votre lettre pour éviter les fautes d'orthographe</li>
                    <li>Format de CV recommandé : PDF</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>