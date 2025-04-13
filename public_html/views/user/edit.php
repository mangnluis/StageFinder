<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier l'utilisateur</h1>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>?page=users&action=edit&id=<?= $user['id'] ?>" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
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
                <?php endif; ?>
            </div>
            
            <div id="studentFields" style="<?= $user['role'] !== 'student' ? 'display: none;' : '' ?>">
                <div class="mb-3">
                    <label for="promotion" class="form-label">Promotion</label>
                    <input type="text" class="form-control" id="promotion" name="promotion" value="<?= htmlspecialchars($studentInfo['promotion'] ?? '') ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="form-text">Laissez vide pour ne pas modifier le mot de passe.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Mettre à jour l'utilisateur</button>
                <a href="<?= BASE_URL ?>?page=admin&action=view&id=<?= $user['id'] ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Afficher/masquer les champs spécifiques aux étudiants en fonction du rôle sélectionné
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>