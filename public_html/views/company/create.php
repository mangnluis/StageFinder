<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-building-add me-2"></i> Ajouter une entreprise
    </h1>
    <a href="<?= BASE_URL ?>?page=companies" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>?page=companies&action=create" method="post" data-validate="true" enctype="multipart/form-data" id="companyForm">

            <ul class="nav nav-tabs mb-4" id="companyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="true">
                        <i class="bi bi-info-circle me-1"></i> Informations
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">
                        <i class="bi bi-geo-alt me-1"></i> Contact & Localisation
                    </button>
                </li>
                
            </ul>

            <div class="tab-content" id="companyTabContent">
                <!-- Onglet Informations -->
                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                    <div id="company-info" class="mb-4">
                        <h4 class="mb-3 border-bottom pb-2">Informations générales</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Microsoft France" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="industry_id" class="form-label">Secteur d'activité <span class="text-danger">*</span></label>
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
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required
                                placeholder="Décrivez l'activité principale de l'entreprise, son histoire, ses valeurs..."></textarea>
                            <div class="form-text">
                                Une description détaillée permet aux étudiants de mieux comprendre votre entreprise (300-500 caractères recommandés).
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_size" class="form-label">Taille de l'entreprise</label>
                                <select class="form-select" id="company_size" name="company_size">
                                    <option value="">Non précisé</option>
                                    <option value="1-10">1-10 employés</option>
                                    <option value="11-50">11-50 employés</option>
                                    <option value="51-200">51-200 employés</option>
                                    <option value="201-500">201-500 employés</option>
                                    <option value="501+">Plus de 500 employés</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="founded_year" class="form-label">Année de création</label>
                                <input type="number" class="form-control" id="founded_year" name="founded_year" 
                                    min="1800" max="<?= date('Y') ?>" placeholder="Ex: 1995" value ="<?= date('Y') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Onglet Contact & Localisation -->
                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                    <div id="company-contact" class="mb-4">
                        <h4 class="mb-3 border-bottom pb-2">Localisation</h4>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" required
                                placeholder="Ex: 12 rue de la Paix">
                        </div>
                        
                        <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city_id" class="form-label">Ville <span class="text-danger">*</span></label>
                            <select class="form-select" id="city_id" name="city_id" required>
                                <option value="">Sélectionner une ville</option>
                                <?php if (!empty($cities)): ?>
                                    <?php foreach ($cities as $city): ?>
                                        <option value="<?= $city['id'] ?>">
                                            <?= htmlspecialchars($city['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required
                                    placeholder="Ex: 75002">
                            </div>
                            
                            
        
                        </div>
                        
                        <h4 class="mb-3 mt-4 border-bottom pb-2">Coordonnées</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        placeholder="Ex: contact@entreprise.com">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="Ex: 01 23 45 67 89">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="website" class="form-label">Site web</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                <input type="url" class="form-control" id="website" name="website" 
                                    placeholder="https://www.exemple.com">
                            </div>
                        </div>
                    </div>
                </div>
                
        
                
                
            </div>
            
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="<?= BASE_URL ?>?page=companies" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Annuler
                </a>
                
                <div>
                    <button type="reset" class="btn btn-outline-warning me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Ajouter l'entreprise
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>