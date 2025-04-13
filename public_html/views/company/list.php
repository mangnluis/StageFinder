<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-2">
                <i class="fas fa-building-user text-primary me-2"></i> Entreprises
            </h1>
            <p class="text-muted mb-0">Explorez les entreprises partenaires proposant des stages</p>
        </div>
        <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
            <div>
                <a href="<?= url('/?page=companies&action=create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une entreprise
                </a>
            </div>
        <?php endif; ?>
    </div>

        <!-- Filtres de recherche -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="<?= url('/?page=companies') ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="companies">
                
                <!-- Recherche par mots-clés -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Mots-clés</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                            placeholder="Nom, secteur, ville..." 
                            value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                
                <!-- Filtre par secteur d'activité -->
                <div class="col-md-3">
                    <label for="industry" class="form-label">Secteur d'activité</label>
                    <select class="form-select" id="industry" name="industry">
                        <option value="">Tous les secteurs</option>
                        <?php 
                        $industries = isset($industries) ? $industries : []; 
                        foreach ($industries as $industry): 
                        ?>
                            <option value="<?= $industry['id'] ?>" <?= (isset($_GET['industry']) && $_GET['industry'] == $industry['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($industry['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtre par ville/localisation (si disponible) -->
                <div class="col-md-3">
                    <label for="location" class="form-label">Localisation</label>
                    <select class="form-select" id="location" name="location">
                        <option value="">Toutes les localisations</option>
                        <?php 
                        $locations = isset($locations) ? $locations : []; 
                        foreach ($locations as $location): 
                        ?>
                            <option value="<?= $location['id'] ?>" <?= (isset($_GET['location']) && $_GET['location'] == $location['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($location['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Options de tri -->
                <div class="col-md-2">
                    <label for="sort" class="form-label">Trier par</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Nom (A-Z)</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Nom (Z-A)</option>
                        <option value="rating_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'rating_desc') ? 'selected' : '' ?>>Mieux notées</option>
                        <option value="recent" <?= (isset($_GET['sort']) && $_GET['sort'] == 'recent') ? 'selected' : '' ?>>Plus récentes</option>
                        <option value="offers_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'offers_desc') ? 'selected' : '' ?>>Nombre d'offres</option>
                    </select>
                </div>
                
                <!-- Boutons d'action -->
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i> Appliquer les filtres
                    </button>
                    <a href="<?= url('/?page=companies') ?>" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Réinitialiser
                    </a>
                </div>
                
                <!-- Tags de filtres actifs -->
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <?php if (!empty($search)): ?>
                            <div class="filter-tag">
                                <i class="fas fa-search me-1"></i> Recherche: <?= htmlspecialchars($search) ?>
                                <a href="<?= url('/?page=companies' . 
                                    (isset($_GET['industry']) ? '&industry=' . $_GET['industry'] : '') . 
                                    (isset($_GET['location']) ? '&location=' . $_GET['location'] : '') .
                                    (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['industry']) && $_GET['industry']): ?>
                            <div class="filter-tag">
                                <i class="fas fa-industry me-1"></i> Secteur: <?= htmlspecialchars($locationName) ?>
                                <a href="<?= url('/?page=companies' . 
                                    (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                    (isset($_GET['location']) ? '&location=' . $_GET['location'] : '') .
                                    (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['location']) && $_GET['location']): ?>
                            <div class="filter-tag">
                                <i class="fas fa-map-marker-alt me-1"></i> Localisation: <?= htmlspecialchars($locationName) ?>
                                <a href="<?= url('/?page=companies' . 
                                    (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                    (isset($_GET['industry']) ? '&industry=' . $_GET['industry'] : '') .
                                    (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <?php if (empty($companies)): ?>
        <!-- Message si aucune entreprise -->
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="display-icon mb-4">
                    <i class="fas fa-building-user fa-4x text-muted"></i>
                </div>
                <h3>Aucune entreprise trouvée</h3>
                <p class="text-muted mb-4">Aucune entreprise ne correspond à vos critères de recherche.</p>
                <a href="<?= url('/?page=companies') ?>" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-2"></i> Réinitialiser les filtres
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Contrôles de vue -->
        <div class="d-flex justify-content-end mb-3">
            <div class="btn-group" role="group" aria-label="Vue">
                <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Vue Grille -->
        <div id="gridView" class="row g-4">
            <?php foreach ($companies as $company): ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card company-card h-100 border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom-0 pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <?= htmlspecialchars($company['name']) ?>
                                </h5>
                                <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="<?= url('/?page=companies&action=edit&id=' . $company['id']) ?>">
                                                    <i class="fas fa-edit fa-fw me-2"></i> Modifier
                                                </a>
                                            </li>
                                            <?php if (Auth::isAdmin()): ?>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="<?= url('/?page=companies&action=delete&id=' . $company['id']) ?>">
                                                        <i class="fas fa-trash fa-fw me-2"></i> Supprimer
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($company['industry'])): ?>
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-industry text-primary me-1"></i> <?= htmlspecialchars($company['industry']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            // Affichage des étoiles si des évaluations existent
                            if (!empty($company['avg_rating'])): 
                                $rating = round($company['avg_rating']); 
                            ?>
                                <div class="rating-display mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $rating ? 'fas' : 'far' ?> fa-star <?= $i <= $rating ? 'text-warning' : '' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="ms-2 small text-muted">(<?= $company['rating_count'] ?? 0 ?>)</span>
                                </div>
                            <?php endif; ?>
                            
                            <p class="card-text mb-4">
                                <?= !empty($company['description']) ? nl2br(htmlspecialchars(substr($company['description'], 0, 120))) . (strlen($company['description']) > 120 ? '...' : '') : '<span class="text-muted fst-italic">Aucune description disponible.</span>' ?>
                            </p>
                            
                            <?php
                            // Nombre d'offres de stage
                            $db = Database::getInstance();
                            $offerCount = $db->fetchColumn("SELECT COUNT(*) FROM internships WHERE company_id = ?", [$company['id']]);
                            ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-laptop me-1"></i> <?= $offerCount ?> offre<?= $offerCount > 1 ? 's' : '' ?>
                                </span>
                                
                                <?php if (!empty($company['location'])): ?>
                                    <span class="small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($company['location']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i> Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

<!-- Vue en liste des entreprises -->
<div class="table-responsive" id="listView" style="display: none;">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nom</th>
                <th>Secteur</th>
                <th>Localisation</th>
                <th>Évaluation</th>
                <th>Offres</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td>
                        <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="company-icon bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-building-user text-primary"></i>
                                </div>
                                <span><?= htmlspecialchars($company['name']) ?></span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($company['industry'])): ?>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-industry text-primary me-1"></i> <?= htmlspecialchars($company['industry']) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Non spécifié</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($company['location'])): ?>
                            <i class="fas fa-map-marker-alt text-secondary me-1"></i> <?= htmlspecialchars($company['location']) ?>
                        <?php else: ?>
                            <span class="text-muted">Non spécifié</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($company['avg_rating'])): 
                            $rating = round($company['avg_rating']); 
                        ?>
                            <div class="rating-display">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?= $i <= $rating ? 'fas' : 'far' ?> fa-star <?= $i <= $rating ? 'text-warning' : '' ?>"></i>
                                <?php endfor; ?>
                                <span class="ms-2 small text-muted">(<?= $company['rating_count'] ?? 0 ?>)</span>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small fst-italic">Aucune évaluation</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        // Nombre d'offres de stage
                        $db = Database::getInstance();
                        $offerCount = $db->fetchColumn("SELECT COUNT(*) FROM internships WHERE company_id = ?", [$company['id']]);
                        ?>
                        <span class="badge bg-primary rounded-pill">
                            <i class="fas fa-laptop me-1"></i> <?= $offerCount ?> offre<?= $offerCount > 1 ? 's' : '' ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <?php if (Auth::isAdmin()): ?>
                                <a href="<?= url('/?page=companies&action=edit&id=' . $company['id']) ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="<?= url('/?page=companies&action=delete&id=' . $company['id']) ?>" class="btn btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <!-- Bouton Précédent -->
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('/?page=companies&p=' . ($page - 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($_GET['industry']) ? '&industry=' . $_GET['industry'] : '') . (!empty($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>" aria-label="Précédent">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Numéros de page -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === (int)$page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('/?page=companies&p=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($_GET['industry']) ? '&industry=' . $_GET['industry'] : '') . (!empty($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Bouton Suivant -->
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('/?page=companies&p=' . ($page + 1) . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($_GET['industry']) ? '&industry=' . $_GET['industry'] : '') . (!empty($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>" aria-label="Suivant">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php 
// Fonction utilitaire pour obtenir le nom d'un secteur à partir de son ID
function getIndustryName($id, $industries) {
    foreach ($industries as $industry) {
        if ($industry['id'] == $id) {
            return $industry['name'];
        }
    }
    return '';
}
?>


<script>
// JavaScript pour le toggle entre vue grille et liste
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    // Charger la préférence de vue depuis localStorage
    const viewPreference = localStorage.getItem('companiesViewPreference');
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
        localStorage.setItem('companiesViewPreference', 'grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
        localStorage.setItem('comapniesViewPreference', 'list');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>