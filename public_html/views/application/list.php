<?php 
// Vérification des permissions

include VIEWS_PATH . '/templates/header.php'; 
?>

<div class="container-fluid applications-management">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6">
                        <i class="fas fa-file-alt text-primary me-3"></i>Gestion des Candidatures
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Candidatures</li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex">
                    <!-- Filtres et recherche -->
                    <div class="input-group me-2" style="width: 250px;">
                        <input type="text" class="form-control" id="searchApplications" 
                               placeholder="Rechercher..." 
                               aria-label="Rechercher candidatures">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>Filtres
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#" data-status="pending">En attente</a></li>
                            <li><a class="dropdown-item" href="#" data-status="accepted">Acceptées</a></li>
                            <li><a class="dropdown-item" href="#" data-status="rejected">Refusées</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (empty($applications)): ?>
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h2 class="card-title">Aucune candidature trouvée</h2>
                        <p class="text-muted mb-4">Il n'y a pas de candidatures à afficher pour le moment.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover applications-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input" id="selectAllApplications">
                                    </th>
                                    <th>Étudiant</th>
                                    <th>Offre de stage</th>
                                    <th>Entreprise</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                    <tr data-status="<?= $app['status'] ?>">
                                        <td>
                                            <input type="checkbox" class="form-check-input application-checkbox" 
                                                   value="<?= $app['id'] ?>">
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=users&action=view&id=' . $app['student_id']) ?>" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=internships&action=view&id=' . $app['internship_id']) ?>" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($app['internship_title']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= url('/?page=companies&action=view&id=' . $app['company_id']) ?>" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($app['company_name']) ?>
                                            </a>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($app['applied_at'])) ?></td>
                                        <td>
                                            <span class="badge 
                                                <?= $app['status'] === 'pending' ? 'bg-warning' : 
                                                    ($app['status'] === 'accepted' ? 'bg-success' : 'bg-danger') ?>">
                                                <?= $app['status'] === 'pending' ? 'En attente' : 
                                                    ($app['status'] === 'accepted' ? 'Acceptée' : 'Refusée') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= url('/?page=applications&action=view&id=' . $app['id']) ?>" 
                                                   class="btn btn-primary" title="Détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($app['status'] === 'pending'): ?>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-success dropdown-toggle" 
                                                                data-bs-toggle="dropdown" 
                                                                aria-expanded="false">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item text-success" 
                                                                   href="<?= url('/?page=applications&action=accept&id=' . $app['id']) ?>">
                                                                    <i class="fas fa-check me-2"></i>Accepter
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" 
                                                                   href="<?= url('/?page=applications&action=reject&id=' . $app['id']) ?>">
                                                                    <i class="fas fa-times me-2"></i>Refuser
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
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
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="me-3">
                                    Page <?= $currentPage ?> sur <?= $totalPages ?>
                                </span>
                                <nav aria-label="Applications pagination">
                                    <ul class="pagination mb-0">
                                        <?php if ($currentPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= url('/?page=applications&p=' . ($currentPage - 1)) ?>">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= url('/?page=applications&p=' . $i) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($currentPage < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= url('/?page=applications&p=' . ($currentPage + 1)) ?>">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            
                            <div class="bulk-actions">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                            id="bulkActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions groupées
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                                        <li>
                                            <a class="dropdown-item" href="#" id="acceptSelected">
                                                <i class="fas fa-check me-2 text-success"></i>
                                                Accepter les sélectionnés
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" id="rejectSelected">
                                                <i class="fas fa-times me-2 text-danger"></i>Refuser les sélectionnés
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.applications-management .table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.applications-management .table tbody tr:hover {
    background-color: rgba(0,0,0,0.05);
    transition: background-color 0.3s ease;
}

.bulk-actions .dropdown-menu {
    min-width: 200px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection de toutes les candidatures
    const selectAllCheckbox = document.getElementById('selectAllApplications');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    // Filtrage des candidatures
    const filterDropdownItems = document.querySelectorAll('.dropdown-menu[aria-labelledby="filterDropdown"] .dropdown-item');
    const searchInput = document.getElementById('searchApplications');

    filterDropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const status = this.getAttribute('data-status');
            filterApplications(status, searchInput.value);
        });
    });

    searchInput.addEventListener('input', function() {
        const currentStatus = document.querySelector('.dropdown-menu[aria-labelledby="filterDropdown"] .dropdown-item.active')?.getAttribute('data-status') || 'all';
        filterApplications(currentStatus, this.value);
    });

    function filterApplications(status, searchTerm) {
        const rows = document.querySelectorAll('.applications-table tbody tr');
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const studentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const internshipTitle = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            const statusMatch = status === 'all' || rowStatus === status;
            const searchMatch = searchTerm === '' || 
                studentName.includes(searchTerm.toLowerCase()) || 
                internshipTitle.includes(searchTerm.toLowerCase());

            row.style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    // Actions groupées
    const acceptSelectedBtn = document.getElementById('acceptSelected');
    const rejectSelectedBtn = document.getElementById('rejectSelected');

    function handleBulkAction(action) {
        const selectedIds = Array.from(document.querySelectorAll('.application-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert('Veuillez sélectionner au moins une candidature.');
            return;
        }

        const confirmMessage = action === 'accept' 
            ? 'Voulez-vous vraiment accepter les candidatures sélectionnées ?'
            : 'Voulez-vous vraiment refuser les candidatures sélectionnées ?';

        if (confirm(confirmMessage)) {
            // Envoyer une requête AJAX pour l'action groupée
            fetch('<?= url('/?page=applications&action=bulk_update') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ids: selectedIds,
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'interface utilisateur
                    selectedIds.forEach(id => {
                        const row = document.querySelector(`tr input[value="${id}"]`).closest('tr');
                        const statusBadge = row.querySelector('.badge');
                        
                        statusBadge.classList.remove('bg-warning', 'bg-danger', 'bg-success');
                        statusBadge.classList.add(action === 'accept' ? 'bg-success' : 'bg-danger');
                        statusBadge.textContent = action === 'accept' ? 'Acceptée' : 'Refusée';
                        
                        row.setAttribute('data-status', action === 'accept' ? 'accepted' : 'rejected');
                    });

                    // Décocher toutes les cases
                    selectAllCheckbox.checked = false;
                    applicationCheckboxes.forEach(cb => cb.checked = false);

                    alert(data.message);
                } else {
                    alert('Une erreur est survenue : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors du traitement de votre requête.');
            });
        }
    }

    acceptSelectedBtn.addEventListener('click', () => handleBulkAction('accept'));
    rejectSelectedBtn.addEventListener('click', () => handleBulkAction('reject'));
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>