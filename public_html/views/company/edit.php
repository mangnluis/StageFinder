<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-2">
                <i class="fas fa-edit text-primary me-2"></i> Modifier l'entreprise
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/?page=companies') ?>">Entreprises</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>"><?= htmlspecialchars($company['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Formulaire de modification -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building-user text-primary me-2"></i> Informations de l'entreprise
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= url('/?page=companies&action=edit&id=' . $company['id']) ?>" method="post" data-validate="true">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($company['name']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="industry" class="form-label">Secteur d'activité</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                    <select class="form-select" id="industry_id" name="industry_name" required>
                                    <option value="">Sélectionner un secteur</option>
                                    <?php if(!empty($industries)): ?>
                                        <?php foreach ($industries as $industry): ?>
                                            <option value="<?= $industry['id'] ?>">
                                                <?= htmlspecialchars($industry['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($company['description'] ?? '') ?></textarea>
                                <div class="form-text">
                                    Une description détaillée permet aux étudiants de mieux comprendre l'activité de l'entreprise.
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="contact_email" class="form-label">Email de contact</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= htmlspecialchars($company['contact_email'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label">Téléphone de contact</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($company['contact_phone'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="website" class="form-label">Site web</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($company['website'] ?? '') ?>" placeholder="https://...">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="address" class="form-label">Adresse</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($company['address'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" <?= (!isset($company['active']) || $company['active']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="active">Entreprise active</label>
                                    <div class="form-text">Une entreprise inactive ne sera pas visible pour les étudiants.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Mettre à jour l'entreprise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Carte d'informations rapides -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i> Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="company-avatar me-3 bg-light rounded p-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-building-user fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($company['name']) ?></h5>
                            <?php if (!empty($company['industry'])): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-industry me-1"></i> <?= htmlspecialchars($company['industry']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush mb-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Date de création</span>
                            <span><?= date('d/m/Y', strtotime($company['created_at'] ?? 'now')) ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Offres actives</span>
                            <?php
                            $db = Database::getInstance();
                            $offerCount = $db->fetchColumn("SELECT COUNT(*) FROM internships WHERE company_id = ?", [$company['id']]);
                            ?>
                            <span class="badge bg-primary rounded-pill"><?= $offerCount ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Dernière modification</span>
                            <span><?= !empty($company['updated_at']) ? date('d/m/Y', strtotime($company['updated_at'])) : 'Jamais' ?></span>
                        </div>
                    </div>
                    
                    <!-- Actions supplémentaires -->
                    <div class="d-grid gap-2">
                        <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i> Voir la fiche complète
                        </a>
                        <a href="<?= url('/?page=internships&action=create&company_id=' . $company['id']) ?>" class="btn btn-outline-success">
                            <i class="fas fa-plus-circle me-2"></i> Ajouter une offre
                        </a>
                        <?php if (Auth::isAdmin()): ?>
                            <a href="<?= url('/?page=companies&action=delete&id=' . $company['id']) ?>" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i> Supprimer l'entreprise
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>