<?php include VIEWS_PATH . '/admin/templates/header.php'; ?>

<div class="users-management">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Gestion des utilisateurs</h1>
            <p class="text-muted">Administration des comptes utilisateurs de la plateforme.</p>
        </div>
        
        <div>
            <?php if (Auth::isAdmin()): ?>
                <a href="<?= url('/?page=admin&action=create-user') ?>" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Créer un utilisateur
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= url('/?page=admin&action=users') ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="action" value="users">
                
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, email..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select class="form-select" name="role">
                        <option value="">Tous les rôles</option>
                        <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>
                            Administrateurs
                        </option>
                        <option value="pilot" <?= (isset($role) && $role === 'pilot') ? 'selected' : '' ?>>
                            Pilotes
                        </option>
                        <option value="student" <?= (isset($role) && $role === 'student') ? 'selected' : '' ?>>
                            Étudiants
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select class="form-select" name="sort">
                        <option value="created_at_desc">Les plus récents</option>
                        <option value="created_at_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'created_at_asc') ? 'selected' : '' ?>>Les plus anciens</option>
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') ? 'selected' : '' ?>>Nom (A-Z)</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') ? 'selected' : '' ?>>Nom (Z-A)</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
                
                <?php if (!empty($search) || isset($role)): ?>
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <?php if (!empty($search)): ?>
                                <div class="filter-tag">
                                    Recherche: <?= htmlspecialchars($search) ?>
                                    <a href="<?= url('/?page=admin&action=users' . (isset($role) && $role ? '&role=' . $role : '') . (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($role) && $role): ?>
                                <div class="filter-tag">
                                    Rôle: <?= $role === 'admin' ? 'Administrateur' : ($role === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                    <a href="<?= url('/?page=admin&action=users' . (!empty($search) ? '&search=' . urlencode($search) : '') . (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <?php if (empty($users)): ?>
        <div class="card text-center p-5">
            <div class="card-body">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h3>Aucun utilisateur trouvé</h3>
                <p class="text-muted mb-4">Aucun utilisateur ne correspond à vos critères de recherche.</p>
                <a href="<?= url('/?page=admin&action=users') ?>" class="btn btn-primary">
                    <i class="fas fa-sync me-2"></i> Réinitialiser les filtres
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                </div>
                            </th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date de création</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input user-checkbox" type="checkbox" value="<?= $user['id'] ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                        </div>
                                        <a href="<?= url('/?page=admin&action=edit-user&id=' . $user['id']) ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'pilot' ? 'warning' : 'success') ?>">
                                        <?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= url('/?page=admin&action=edit-user&id=' . $user['id']) ?>" class="btn btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if (Auth::isAdmin() && $user['id'] != Auth::getUserId()): ?>
                                            <a href="<?= url('/?page=admin&action=delete-user&id=' . $user['id']) ?>" class="btn btn-outline-danger delete-user" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination and Bulk Actions -->
            <div class="card-footer d-flex justify-content-between align-items-center">
                <!-- Bulk Actions -->
                <div class="bulk-actions">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                            Actions groupées <span class="selected-count">(0 sélectionné)</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                            <?php if (Auth::isAdmin()): ?>
                                <li>
                                    <button class="dropdown-item text-danger" id="bulkDeleteBtn" disabled>
                                        <i class="fas fa-trash fa-fw me-2"></i>Supprimer les sélectionnés
                                    </button>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Pagination -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Pagination">
                        <ul class="pagination mb-0">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/?page=admin&action=users&p=' . ($currentPage - 1) . 
                                        (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                        (isset($role) && $role ? '&role=' . $role : '') .
                                        (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === (int)$currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/?page=admin&action=users&p=' . $i . 
                                        (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                        (isset($role) && $role ? '&role=' . $role : '') .
                                        (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/?page=admin&action=users&p=' . ($currentPage + 1) . 
                                        (!empty($search) ? '&search=' . urlencode($search) : '') . 
                                        (isset($role) && $role ? '&role=' . $role : '') .
                                        (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '')) ?>">
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
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.bulk-actions button:disabled {
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all users checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAllUsers');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActionsDropdown = document.getElementById('bulkActionsDropdown');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.querySelector('.selected-count');
    
    // Update bulk actions state
    function updateBulkActionsState() {
        const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCountSpan.textContent = `(${selectedCount} sélectionné${selectedCount > 1 ? 's' : ''})`;
        
        if (selectedCount > 0) {
            bulkActionsDropdown.removeAttribute('disabled');
            if (bulkDeleteBtn) bulkDeleteBtn.removeAttribute('disabled');
        } else {
            bulkActionsDropdown.setAttribute('disabled', 'disabled');
            if (bulkDeleteBtn) bulkDeleteBtn.setAttribute('disabled', 'disabled');
        }
    }
    
    // Select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkActionsState();
        });
    }
    
    // Individual checkboxes
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.user-checkbox:checked').length === userCheckboxes.length;
            if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
            updateBulkActionsState();
        });
    });
    
    // Bulk delete functionality
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer ${selectedIds.length} utilisateurs ? Cette action est irréversible.`)) {
                // Submit form with selected IDs
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= url('/?page=admin&action=bulk-delete-users') ?>';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids';
                input.value = selectedIds.join(',');
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Delete user confirmation
    const deleteButtons = document.querySelectorAll('.delete-user');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?php include VIEWS_PATH . 'templates/footer.php'; ?>