<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid users-management">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6">
                        <i class="fas fa-users text-primary me-3"></i>Gestion des utilisateurs
                    </h1>
                    <p class="text-muted">
                        Liste et gestion des comptes utilisateurs
                    </p>
                </div>
                <div class="actions">
                    <?php if (Auth::isAdmin()): ?>
                        <a href="<?= url('/?page=users&action=create') ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Créer un utilisateur
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?= url('/?page=users') ?>" method="get" class="row g-3">
                        <input type="hidden" name="page" value="users">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Rechercher un utilisateur" 
                                   value="<?= htmlspecialchars($search ?? '') ?>">
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
                        <div class="col-md-2">
                            <select class="form-select" name="sort">
                                <option value="created_at_desc">Les plus récents</option>
                                <option value="created_at_asc">Les plus anciens</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($users)): ?>
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-users-slash fa-4x text-muted mb-4"></i>
                        <h2>Aucun utilisateur trouvé</h2>
                        <p class="text-muted mb-4">
                            Votre recherche n'a retourné aucun résultat.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
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
                                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($user['email']) ?>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?= $user['role'] === 'admin' ? 'bg-danger' : 
                                                    ($user['role'] === 'pilot' ? 'bg-warning' : 'bg-success') ?>">
                                                <?= $user['role'] === 'admin' ? 'Administrateur' : 
                                                    ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= url('/?page=users&action=view&id=' . $user['id']) ?>" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir le profil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (Auth::isAdmin() || 
                                                        (Auth::isPilot() && $user['role'] === 'student')): ?>
                                                    <a href="<?= url('/?page=users&action=edit&id=' . $user['id']) ?>" 
                                                       class="btn btn-outline-secondary" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::isAdmin() && $user['id'] != Auth::getUserId()): ?>
                                                    <a href="<?= url('/?page=users&action=delete&id=' . $user['id']) ?>" 
                                                       class="btn btn-outline-danger delete-user" 
                                                       title="Supprimer">
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

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <div class="card-footer d-flex justify-content-center">
                            <nav aria-label="User pagination">
                                <ul class="pagination mb-0">
                                    <?php if ($currentPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('/?page=users&p=' . ($currentPage - 1)) ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('/?page=users&p=' . $i) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('/?page=users&p=' . ($currentPage + 1)) ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation de suppression
    const deleteUserButtons = document.querySelectorAll('.delete-user');
    
    deleteUserButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Voulez-vous vraiment supprimer cet utilisateur ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>