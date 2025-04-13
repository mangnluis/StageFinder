<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-heart text-danger me-2"></i> Ma wishlist</h1>
    <a href="<?= url('/?page=internships') ?>" class="btn btn-primary">
        <i class="fas fa-search me-2"></i> Explorer plus d'offres
    </a>
</div>

<?php if (empty($wishlist)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="far fa-heart fa-4x text-muted mb-3"></i>
            <h3 class="mb-3">Votre wishlist est vide</h3>
            <p class="text-muted mb-4">Vous n'avez pas encore ajouté d'offres de stage à votre wishlist.</p>
            <a href="<?= url('/?page=internships') ?>" class="btn btn-primary">
                <i class="fas fa-search me-2"></i> Découvrir les offres
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Offres sauvegardées (<?= count($wishlist) ?>)</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="gridViewBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="btn btn-outline-primary" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Vue grille -->
            <div class="row g-4" id="gridView">
                <?php foreach ($wishlist as $item): ?>
                    <div class="col-md-6">
                        <div class="card h-100 internship-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($item['title']) ?></h5>
                                <a href="<?= url('/?page=wishlist&action=remove&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-outline-danger" title="Retirer de la wishlist">
                                    <i class="fas fa-heart-broken"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($item['company_name']) ?></h6>
                                <p class="card-text">
                                    <?= nl2br(htmlspecialchars(substr($item['description'], 0, 150))) ?>
                                    <?= (strlen($item['description']) > 150) ? '...' : '' ?>
                                </p>
                                
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="mb-2">
                                        <?php if (!empty($item['compensation'])): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-euro-sign me-1"></i> <?= number_format($item['compensation'], 2, ',', ' ') ?> €
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-end mb-2">
                                        <?php if (!empty($item['start_date'])): ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($item['start_date'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <small class="text-muted">Ajouté le <?= date('d/m/Y', strtotime($item['added_at'])) ?></small>
                                
                                <div>

                                    <?php if ($hasApplied): ?>
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-check me-1"></i> Déjà postulé
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= url('/?page=internships&action=apply&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i> Postuler
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= url('/?page=internships&action=view&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i> Détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Vue liste -->
            <div class="table-responsive" id="listView" style="display: none;">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Offre</th>
                            <th>Entreprise</th>
                            <th>Rémunération</th>
                            <th>Date de début</th>
                            <th>Ajouté le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wishlist as $item): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('/?page=internships&action=view&id=' . $item['internship_id']) ?>" class="fw-bold text-decoration-none">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= url('/?page=companies&action=view&id=' . $item['company_id']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($item['company_name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?= !empty($item['compensation']) ? number_format($item['compensation'], 2, ',', ' ') . ' €' : '-' ?>
                                </td>
                                <td>
                                    <?= !empty($item['start_date']) ? date('d/m/Y', strtotime($item['start_date'])) : '-' ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($item['added_at'])) ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php
                                        // Vérifier si l'étudiant a déjà postulé
                                        $db = Database::getInstance();
                                        $hasApplied = $db->fetchColumn(
                                            "SELECT COUNT(*) FROM applications WHERE student_id = ? AND internship_id = ?",
                                            [Auth::getUserId(), $item['internship_id']]
                                        ) > 0;
                                        ?>
                                        
                                        <?php if ($hasApplied): ?>
                                            <button class="btn btn-sm btn-success" disabled>
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= url('/?page=internships&action=apply&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-primary" title="Postuler">
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?= url('/?page=internships&action=view&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-info" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="<?= url('/?page=wishlist&action=remove&id=' . $item['internship_id']) ?>" class="btn btn-sm btn-danger" title="Retirer de la wishlist">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
// JavaScript pour le toggle entre vue grille et liste
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    // Charger la préférence de vue depuis localStorage
    const viewPreference = localStorage.getItem('wishlistViewPreference');
    if (viewPreference === 'list') {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
    }
    
    // Gestionnaires d'événements pour les boutons
    gridViewBtn.addEventListener('click', function() {
        gridView.style.display = 'flex';
        listView.style.display = 'none';
        listViewBtn.classList.remove('active');
        gridViewBtn.classList.add('active');
        localStorage.setItem('wishlistViewPreference', 'grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
        localStorage.setItem('wishlistViewPreference', 'list');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>