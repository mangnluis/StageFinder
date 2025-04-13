<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="user-create">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Créer un utilisateur</h1>
            <p class="text-muted">Ajouter un nouveau compte utilisateur à la plateforme.</p>
        </div>
        
        <div>
            <a href="<?= url('/?page=admin&action=users') ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Create User Form -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Informations utilisateur</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>?page=admin&action=create-user" method="post" id="createUserForm">                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="student">Étudiant</option>
                                <?php if (Auth::isAdmin()): ?>
                                    <option value="pilot">Pilote</option>
                                    <option value="admin">Administrateur</option>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Les différents rôles déterminent les permissions de l'utilisateur.</div>
                        </div>
                    </div>
                    
                    <!-- Account and Student Information -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        
                        <div id="studentFields">
                            <div class="mb-3">
                                <label for="promotion" class="form-label">Promotion</label>
                                <input type="text" class="form-control" id="promotion" name="promotion" placeholder="Ex: CESI Informatique 2023">
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
                        <i class="fas fa-user-plus me-2"></i> Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    toggleStudentFields(); // Initialize on page load
    
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
    const form = document.getElementById('createUserForm');
    const passwordConfirm = document.getElementById('password_confirm');
    
    form.addEventListener('submit', function(e) {
        if (passwordInput.value !== passwordConfirm.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            passwordConfirm.focus();
        }
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>