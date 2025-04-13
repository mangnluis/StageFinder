<?php include VIEWS_PATH . '/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=internships') ?>">Offres de stage</a></li>
        <li class="breadcrumb-item active" aria-current="page">Créer une offre</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header">
        <h1 class="h3 mb-0">Créer une offre de stage</h1>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('/?page=internships&action=create') ?>" method="POST" data-validate="true" id="internshipForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="col-md-6">
                    <label for="company_id" class="form-label">Entreprise <span class="text-danger">*</span></label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="">Sélectionner une entreprise</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['id'] ?>" <?= (isset($_GET['company_id']) && $_GET['company_id'] == $company['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($company['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                <div class="form-text">Décrivez les principales missions et objectifs du stage.</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="location" class="form-label">Lieu <span class="text-danger">*</span></label>
                    <select class="form-select" id="location_id" name="location_id" required>
                        <option value="">Sélectionner une entreprise</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= $location['id'] ?>" <?= (isset($_GET['location_id']) && $_GET['company_id'] == $company['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($location['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="compensation" class="form-label">Rémunération (€/mois)</label>
                    <input type="number" class="form-control" id="compensation" name="compensation" step="10" min="660" value="660">
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label">Type de stage</label>
                    <select class="form-select" id="type" name="type">
                        <option value="full-time">Temps plein</option>
                        <option value="part-time">Temps partiel</option>
                        <option value="remote">Télétravail</option>
                        <option value="hybrid">Hybride</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>

            <div class="mb-3">
                <label for="responsibilities" class="form-label">Responsabilités</label>
                <textarea class="form-control" id="responsibilities" name="responsibilities" rows="3"></textarea>
                <div class="form-text">Détaillez les responsabilités spécifiques du stagiaire.</div>
            </div>

            <div class="mb-3">
                <label for="requirements" class="form-label">Prérequis</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="3"></textarea>
                <div class="form-text">Listez les qualifications et expériences recommandées.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Compétences requises</label>
                <div class="row">
                    <?php foreach ($skills as $skill): ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skill_<?= $skill['id'] ?>" name="skills[]" value="<?= $skill['id'] ?>">
                                <label class="form-check-label" for="skill_<?= $skill['id'] ?>">
                                    <?= htmlspecialchars($skill['name']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Créer l'offre
                </button>
                <a href="<?= url('/?page=internships') ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>