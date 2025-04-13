<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold">Connexion</h1>
                    <p class="text-muted">Accédez à votre compte StageFinder</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="<?= url('/?page=auth&action=login') ?>" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="exemple@email.com" required>
                        </div>
                        <div class="invalid-feedback">
                            Veuillez entrer votre adresse email.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Veuillez entrer votre mot de passe.
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                        </button>
                    </div>
                </form>
                
                <div class="text-center">
                    <p class="mb-0">Vous n'avez pas de compte ? <a href="<?= url('/?page=auth&action=register') ?>" class="text-primary">Créer un compte</a></p>
                </div>
            </div>
        </div>
        
        <!-- Admin Demo Account Card -->
        <div class="card mt-4 border-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle text-primary me-2"></i> Compte de démonstration</h5>
                <p class="card-text">Utilisez ces identifiants pour tester l'application :</p>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Email :</span>
                        <code>admin@admin.fr</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Mot de passe :</span>
                        <code>admin123</code>
                    </li>
                </ul>
                <p class="text-muted mb-0">
                    <small>Note : Ce compte dispose de tous les droits d'administration.</small>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    
    // Password toggle visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>