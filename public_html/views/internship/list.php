<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Offres de stage</li>
        </ol>
    </nav>

    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">
            <i class="fas fa-laptop text-primary me-2"></i> Offres de stage
        </h1>
        <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
            <a href="<?= url('/?page=internships&action=create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Ajouter une offre
            </a>
        <?php endif; ?>
    </div>

    <!-- Filtres de recherche -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="<?= url('/?page=internships') ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="internships">
                
                <!-- Recherche par mots-clés -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Mots-clés</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Titre, description, entreprise..." 
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                
                <!-- Filtre par compétence -->
                <div class="col-md-2">
                    <label for="skill" class="form-label">Compétence</label>
                    <select class="form-select" id="skill" name="skill">
                        <option value="">Toutes les compétences</option>
                        <?php foreach ($skills as $skill): ?>
                            <option value="<?= $skill['id'] ?>" <?= (isset($skillId) && $skillId == $skill['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($skill['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtre par ville/localisation -->
                <div class="col-md-2">
                    <label for="location" class="form-label">Ville</label>
                    <select class="form-select" id="location" name="location">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= $location['id'] ?>" <?= (isset($filterLocation) && $filterLocation == $location['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($location['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtre par date de début -->
                <div class="col-md-2">
                    <label for="date_start" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="date_start" name="date_start"
                           value="<?= htmlspecialchars($filterDateStart ?? '') ?>">
                </div>
                
                <!-- Options de tri -->
                <div class="col-md-2">
                    <label for="sort" class="form-label">Trier par</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="date_desc" <?= (isset($sortOption) && $sortOption == 'date_desc') ? 'selected' : '' ?>>Date (récentes)</option>
                        <option value="date_asc" <?= (isset($sortOption) && $sortOption == 'date_asc') ? 'selected' : '' ?>>Date (anciennes)</option>
                        <option value="comp_desc" <?= (isset($sortOption) && $sortOption == 'comp_desc') ? 'selected' : '' ?>>Rémunération (↓)</option>
                        <option value="comp_asc" <?= (isset($sortOption) && $sortOption == 'comp_asc') ? 'selected' : '' ?>>Rémunération (↑)</option>
                    </select>
                </div>
                
                <!-- Boutons d'action -->
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i> Appliquer les filtres
                    </button>
                    <a href="<?= url('/?page=internships') ?>" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Réinitialiser
                    </a>
                </div>
                
                <!-- Tags de filtres actifs -->
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <?php if (!empty($search)): ?>
                            <div class="filter-tag">
                                Recherche: <?= htmlspecialchars($search) ?>
                                <a href="<?= url('/?page=internships' . 
                                    (isset($skillId) && $skillId ? '&skill=' . $skillId : '') . 
                                    (isset($filterLocation) ? '&location=' . $filterLocation : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($skillId) && $skillId): ?>
                            <div class="filter-tag">
                                Compétence: <?= htmlspecialchars($skillName ?? '') ?>
                                <a href="<?= url('/?page=internships' . 
                                    (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                    (isset($filterLocation) ? '&location=' . $filterLocation : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($filterLocation) && $filterLocation): ?>
                            <div class="filter-tag">
                                Localisation: <?= htmlspecialchars($locationName ?? '') ?>
                                <a href="<?= url('/?page=internships' . 
                                    (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                    (isset($skillId) && $skillId ? '&skill=' . $skillId : '')) ?>" class="ms-2">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Options d'affichage -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="text-muted"><?= count($internships) ?> offre(s) trouvée(s)</span>
        </div>
        <div class="btn-group" role="group" aria-label="Mode d'affichage">
            <button type="button" class="btn btn-outline-primary active" id="gridViewBtn">
                <i class="fas fa-th-large"></i> Grille
            </button>
            <button type="button" class="btn btn-outline-primary" id="listViewBtn">
                <i class="fas fa-list"></i> Liste
            </button>
        </div>
    </div>

    <?php if (empty($internships)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aucune offre de stage ne correspond à votre recherche.
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= url('/?page=internships') ?>" class="btn btn-primary">
                <i class="fas fa-sync me-2"></i> Réinitialiser les filtres
            </a>
        </div>
    <?php else: ?>
        <!-- Vue en grille des offres -->
        <div class="row g-4" id="gridView">
            <?php foreach ($internships as $internship): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-card border-0 internship-card">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">
                                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>">
                                    <?= htmlspecialchars($internship['title']) ?>
                                </a>
                            </h5>
                            <h6 class="card-subtitle text-muted">
                                <a href="<?= url('/?page=companies&action=view&id=' . $internship['company_id']) ?>">
                                    <?= htmlspecialchars($internship['company_name']) ?>
                                </a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted">
                                <?= nl2br(htmlspecialchars(substr($internship['description'], 0, 150))) ?>
                                <?= (strlen($internship['description']) > 150) ? '...' : '' ?>
                            </p>
                            
                            <!-- Compétences requises -->
                            <?php if (!empty($internshipSkills)): ?>
                                <div class="mb-3">
                                    <?php foreach ($internshipSkills as $skill): ?>
                                        <span class="badge bg-primary me-1 mb-1"><?= htmlspecialchars($skill['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Infos principales -->
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="mb-2">
                                    <?php if (!empty($internship['compensation'])): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-euro-sign me-1"></i> <?= number_format($internship['compensation'], 2, ',', ' ') ?> €
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($internship['location'])): ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($internship['location'] ?? 'Non défini') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="text-end mb-2">
                                    <?php if (!empty($internship['start_date'])): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/y', strtotime($internship['start_date'])) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-user me-1"></i> <?= $internship['applications_count'] ?? 0 ?> candidat(s)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <small class="text-muted">Posté le <?= date('d/m/Y', strtotime($internship['created_at'])) ?></small>
                            
                            <div class="d-flex">
                                <?php if (Auth::isStudent() || Auth::isAdmin()): ?>
                                    <?php if (!empty($internship['is_in_wishlist'])): ?>
                                        <a href="<?= url('/?page=wishlist&action=remove&id=' . $internship['id']) ?>" class="btn btn-sm btn-outline-warning me-2">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('/?page=wishlist&action=add&id=' . $internship['id']) ?>" class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= url('/?page=internships&action=apply&id=' . $internship['id']) ?>" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-paper-plane"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="btn btn-sm btn-primary">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Vue en liste des offres -->
        <div class="table-responsive" id="listView" style="display: none;">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Entreprise</th>
                        <th>Localisation</th>
                        <th>Rémunération</th>
                        <th>Date de début</th>
                        <th>Publié le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($internships as $internship): ?>
                        <tr>
                            <td>
                                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>">
                                    <?= htmlspecialchars($internship['title']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= url('/?page=companies&action=view&id=' . $internship['company_id']) ?>">
                                    <?= htmlspecialchars($internship['company_name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($internship['location_name'] ?? 'Non spécifié') ?></td>
                            <td>
                                <?php if (!empty($internship['compensation'])): ?>
                                    <?= number_format($internship['compensation'], 2, ',', ' ') ?> €
                                <?php else: ?>
                                    <span class="text-muted">Non spécifié</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($internship['start_date'])): ?>
                                    <?= date('d/m/Y', strtotime($internship['start_date'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">Non spécifié</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($internship['created_at'])) ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <?php if (Auth::isStudent()): ?>
                                        <?php if ($inWishlist ?? false): ?>
                                            <a href="<?= url('/?page=wishlist&action=remove&id=' . $internship['id']) ?>" class="btn btn-outline-warning">
                                                <i class="fas fa-heart"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= url('/?page=wishlist&action=add&id=' . $internship['id']) ?>" class="btn btn-outline-secondary">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?= url('/?page=internships&action=apply&id=' . $internship['id']) ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-paper-plane"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if (Auth::isAdmin() || Auth::isPilot()): ?>
                                        <a href="<?= url('/?page=internships&action=edit&id=' . $internship['id']) ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <?php if (Auth::isAdmin()): ?>
                                            <a href="<?= url('/?page=internships&action=delete&id=' . $internship['id']) ?>" class="btn btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
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
            <nav aria-label="Pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page -->
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/?page=internships&p=' . ($currentPage - 1) . 
                                (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                (isset($skillId) && $skillId ? '&skill=' . $skillId : '') .
                                (isset($filterLocation) ? '&location=' . $filterLocation : '')) ?>">
                                <i class="fas fa-chevron-left me-1"></i> Précédent
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fas fa-chevron-left me-1"></i> Précédent</span>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === (int)$currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/?page=internships&p=' . $i . 
                                (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                (isset($skillId) && $skillId ? '&skill=' . $skillId : '') .
                                (isset($filterLocation) ? '&location=' . $filterLocation : '')) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Next Page -->
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/?page=internships&p=' . ($currentPage + 1) . 
                                (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                (isset($skillId) && $skillId ? '&skill=' . $skillId : '') .
                                (isset($filterLocation) ? '&location=' . $filterLocation : '')) ?>">
                                Suivant <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Suivant <i class="fas fa-chevron-right ms-1"></i></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
// JavaScript pour le toggle entre vue grille et liste
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    // Charger la préférence de vue depuis localStorage
    const viewPreference = localStorage.getItem('internshipsViewPreference');
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
        localStorage.setItem('internshipsViewPreference', 'grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
        localStorage.setItem('internshipsViewPreference', 'list');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>