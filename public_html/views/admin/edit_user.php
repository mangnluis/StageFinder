<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="user-edit">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Modifier l'utilisateur</h1>
            <p class="text-muted">Éditer les informations du compte de <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>.</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="<?= url('/?page=admin&action=users') ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Retour à la liste
            </a>
            
            <?php if (Auth::isAdmin() && $user['id'] != Auth::getUserId()): ?>
                <a href="<?= url('/?page=admin&action=delete-user&id=' . $user['id']) ?>" class="btn btn-outline-danger delete-user">
                    <i class="fas fa-trash me-2"></i> Supprimer
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Edit User Form -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations utilisateur</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>?page=users&action=edit&id=<?= $user['id'] ?>" method="post" id="editUserForm">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" <?= Auth::isAdmin() ? '' : 'disabled' ?>>
                                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Étudiant</option>
                                        <option value="pilot" <?= $user['role'] === 'pilot' ? 'selected' : '' ?>>Pilote</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                    </select>
                                    <?php if (!Auth::isAdmin()): ?>
                                        <input type="hidden" name="role" value="<?= $user['role'] ?>">
                                        <div class="form-text">Seuls les administrateurs peuvent modifier les rôles des utilisateurs.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Account and Student Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Laissez vide pour ne pas changer le mot de passe.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                                </div>
                                
                                <div id="studentFields" style="<?= $user['role'] !== 'student' ? 'display: none;' : '' ?>">
                                    <div class="mb-3">
                                        <label for="promotion" class="form-label">Promotion</label>
                                        <input type="text" class="form-control" id="promotion" name="promotion" value="<?= htmlspecialchars($studentInfo['promotion'] ?? '') ?>" placeholder="Ex: CESI Informatique 2023">
                                        <div class="form-text">Promotion ou filière d'études (uniquement pour les étudiants).</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?= url('/?page=admin&action=users') ?>'">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- User Info and Stats -->
        <div class="col-md-4">
            <!-- User Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3">
                        <div class="avatar-circle mx-auto bg-primary text-white">
                            <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                        </div>
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                    <p class="text-muted mb-3"><?= htmlspecialchars($user['email']) ?></p>
                    
                    <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'pilot' ? 'warning' : 'success') ?> mb-3">
                        <?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                    </span>
                    
                    <p class="mb-0"><small class="text-muted">Inscrit le <?= date('d/m/Y', strtotime($user['created_at'])) ?></small></p>
                </div>
            </div>
            
            <!-- Student Stats -->
            <?php if ($user['role'] === 'student' && !empty($stats)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Statistiques</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6>Candidatures</h6>
                <div class="d-flex flex-wrap mb-2">
                    <div class="stat-item me-3">
                        <span class="text-muted">Total</span>
                        <div class="badge badge-light"><?= $stats['applications']['total'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item me-3">
                        <span class="text-muted">En attente</span>
                        <div class="badge badge-warning"><?= $stats['applications']['pending'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item me-3">
                        <span class="text-muted">Acceptées</span>
                        <div class="badge badge-success"><?= $stats['applications']['accepted'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item">
                        <span class="text-muted">Refusées</span>
                        <div class="badge badge-danger"><?= $stats['applications']['rejected'] ?? 0 ?></div>
                    </div>
                </div>
            </div>

            <div>
                <h6>Wishlist</h6>
                <div class="stat-item">
                    <span class="text-muted">Offres sauvegardées</span>
                    <div class="badge badge-primary"><?= $stats['wishlistCount'] ?? 0 ?></div>
                </div>
            </div>

            <hr class="my-3">

            <div class="d-grid">
                <a href="<?= url('/?page=admin&action=stats&id=' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-chart-bar me-2"></i> Voir les statistiques détaillées
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 32px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle student fields based on selected role
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('studentFields');
    
    function toggleStudentFields() {
        if (roleSelect.value === 'student') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    }
    
    roleSelect.addEventListener('change', toggleStudentFields);
    
    // Toggle password visibility
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Form validation
    const form = document.getElementById('editUserForm');
    const passwordConfirm = document.getElementById('password_confirm');
    
    form.addEventListener('submit', function(e) {
        if (passwordInput.value && passwordInput.value !== passwordConfirm.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            passwordConfirm.focus();
        }
    });
    
    // Delete user confirmation
    const deleteUserBtn = document.querySelector('.delete-user');
    if (deleteUserBtn) {
        deleteUserBtn.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    }
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>