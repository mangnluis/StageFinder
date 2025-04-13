<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=users') ?>">Utilisateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajouter un utilisateur</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-plus text-primary me-2"></i> Ajouter un utilisateur
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="<?= url('/?page=users&action=create') ?>" method="post" class="needs-validation" novalidate>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                        <div class="invalid-feedback">Le prénom est requis.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                        <div class="invalid-feedback">Le nom est requis.</div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Veuillez fournir une adresse email valide.</div>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="student">Étudiant</option>
                        <?php if (Auth::isAdmin()): ?>
                            <option value="pilot">Pilote</option>
                            <option value="admin">Administrateur</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div id="studentFields" class="mb-4">
                    <div class="card border border-light bg-light">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations étudiant</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="promotion" class="form-label">Promotion</label>
                                <input type="text" class="form-control" id="promotion" name="promotion">
                                <div class="form-text">Par exemple: "2025", "Master 2", etc.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Le mot de passe est requis.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        <div class="invalid-feedback">Veuillez confirmer le mot de passe.</div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= url('/?page=users') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i> Ajouter l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>
