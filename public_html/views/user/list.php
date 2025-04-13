<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des utilisateurs</h1>
        <?php if (Auth::isAdmin()): ?>
            <a href="<?= url('/?page=users&action=create') ?>" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Ajouter un utilisateur
            </a>
        <?php endif; ?>
    </div>

    <!-- Filtres de recherche -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="<?= url('/?page=users') ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="users">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher un utilisateur" value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="role">
                        <option value="">Tous les rôles</option>
                        <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Administrateurs</option>
                        <option value="pilot" <?= (isset($role) && $role === 'pilot') ? 'selected' : '' ?>>Pilotes</option>
                        <option value="student" <?= (isset($role) && $role === 'student') ? 'selected' : '' ?>>Étudiants</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">
            Aucun utilisateur trouvé.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
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
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                            </div>
                                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : ($user['role'] === 'pilot' ? 'bg-primary' : 'bg-success') ?>">
                                            <?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= url('/?page=users&action=view&id=' . $user['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (Auth::isAdmin() || (Auth::isPilot() && $user['role'] === 'student')): ?>
                                            <a href="<?= url('/?page=users&action=edit&id=' . $user['id']) ?>" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <?php if (Auth::isAdmin() && $user['id'] != Auth::getUserId()): ?>
                                                <a href="<?= url('/?page=users&action=delete&id=' . $user['id']) ?>" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === (int)$page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('/?page=users&p=' . $i . (!empty($role) ? '&role=' . urlencode($role) : '') . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>