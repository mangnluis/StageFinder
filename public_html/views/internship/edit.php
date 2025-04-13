<?php include VIEWS_PATH . '/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=internships') ?>">Offres de stage</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>"><?= htmlspecialchars($internship['title']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
</nav>

<div class="card">
    <div class="card-header">
        <h1 class="h3 mb-0">Modifier une offre de stage</h1>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('/?page=internships&action=edit&id=' . $internship['id']) ?>" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($internship['title']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="company_id" class="form-label">Entreprise <span class="text-danger">*</span></label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="">Sélectionner une entreprise</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['id'] ?>" <?= $internship['company_id'] == $company['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($company['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($internship['description']) ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="location_id" class="form-label">Lieu <span class="text-danger">*</span></label>
                    <select class="form-select" id="location_id" name="location_id" required>
                        <option value="">Choisir un lieu</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city['id'] ?>" <?= (isset($internship) && $internship['location_id'] == $city['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

                <div class="col-md-4">
                    <label for="compensation" class="form-label">Rémunération (€/mois)</label>
                    <input type="number" class="form-control" id="compensation" name="compensation" value="<?= htmlspecialchars($internship['compensation'] ?? '') ?>" step="10" min="0">
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label">Type de stage</label>
                    <select class="form-select" id="type" name="type">
                        <option value="full-time" <?= ($internship['type'] ?? '') == 'full-time' ? 'selected' : '' ?>>Temps plein</option>
                        <option value="part-time" <?= ($internship['type'] ?? '') == 'part-time' ? 'selected' : '' ?>>Temps partiel</option>
                        <option value="remote" <?= ($internship['type'] ?? '') == 'remote' ? 'selected' : '' ?>>Télétravail</option>
                        <option value="hybrid" <?= ($internship['type'] ?? '') == 'hybrid' ? 'selected' : '' ?>>Hybride</option>
                    </select>
                </div>
            </div>
            <div class="modif-stage">                    
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $internship['start_date'] ? date('Y-m-d', strtotime($internship['start_date'])) : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $internship['end_date'] ? date('Y-m-d', strtotime($internship['end_date'])) : '' ?>">
                    </div>
                </div>
            
            
                <?php if (!empty($internship['responsibilities'])): ?>
                <div class="mb-3">
                    <label for="responsibilities" class="form-label">Responsabilités</label>
                    <textarea class="form-control" id="responsibilities" name="responsibilities" rows="3"><?= htmlspecialchars($internship['responsibilities']) ?></textarea>
                </div>
                <?php endif; ?>

                <?php if (!empty($internship['requirements'])): ?>
                <div class="mb-3">
                    <label for="requirements" class="form-label">Prérequis</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="3"><?= htmlspecialchars($internship['requirements']) ?></textarea>
                </div>
                <?php endif; ?>       
                <label class="form-label">Compétences requises MOLKA REGARDE</label>
            
                <div class="mb-4">
                    <div class="row">
                        <?php foreach ($allSkills as $skill): ?>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skill_<?= $skill['id'] ?>" name="skills[]" value="<?= $skill['id'] ?>" <?= in_array($skill['id'], $selectedSkillIds) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="skill_<?= $skill['id'] ?>">
                                        <?= htmlspecialchars($skill['name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="btn btn-secondary">Annuler</a>
            </div>
        
            </div>
        </form>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>