<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">
                <i class="fas fa-file-alt text-primary me-2"></i> Mes candidatures
            </h1>
            
            <?php if (empty($applications)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    Vous n'avez pas encore postulé à des offres de stage.
                    <a href="<?= url('/?page=internships') ?>" class="alert-link">Découvrez nos offres de stage</a>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vos candidatures (<?= count($applications) ?>)</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" id="gridViewBtn">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="listViewBtn">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Grid View -->
                    <div class="card-body" id="gridView">
                        <div class="row g-4">
                            <?php foreach ($applications as $application): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <?= htmlspecialchars($application['title']) ?>
                                            </h5>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($application['company_name']) ?>
                                            </small>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="badge 
                                                    <?= $application['status'] === 'pending' ? 'bg-warning' : 
                                                        ($application['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                    <?= $application['status'] === 'pending' ? 'En attente' : 
                                                        ($application['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($application['applied_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="<?= url('/?page=applications&action=view&id=' . $application['id']) ?>" 
                                               class="btn btn-sm btn-primary w-100">
                                                Voir les détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- List View -->
                    <div class="table-responsive" id="listView" style="display: none;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Entreprise</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $application): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/?page=internships&action=view&id=' . $application['internship_id']) ?>">
                                                <?= htmlspecialchars($application['title']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=companies&action=view&id=' . $application['company_id']) ?>">
                                                <?= htmlspecialchars($application['company_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($application['applied_at'])) ?></td>
                                        <td>
                                            <span class="badge 
                                                <?= $application['status'] === 'pending' ? 'bg-warning' : 
                                                    ($application['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                <?= $application['status'] === 'pending' ? 'En attente' : 
                                                    ($application['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=applications&action=view&id=' . $application['id']) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    // Load view preference from localStorage
    const viewPreference = localStorage.getItem('applicationsViewPreference');
    if (viewPreference === 'list') {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
    }
    
    // Event handlers for view toggle buttons
    gridViewBtn.addEventListener('click', function() {
        gridView.style.display = 'block';
        listView.style.display = 'none';
        listViewBtn.classList.remove('active');
        gridViewBtn.classList.add('active');
        localStorage.setItem('applicationsViewPreference', 'grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
        localStorage.setItem('applicationsViewPreference', 'list');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>