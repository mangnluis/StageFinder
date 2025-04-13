<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Évaluer l'entreprise <?= htmlspecialchars($company['name']) ?></h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Votre évaluation</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>?page=rating&action=rateCompany&id=<?= $company['id'] ?>" method="post">
                    <div class="mb-4">
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                <label class="form-check-label" for="rating1">
                                    1 - Très insatisfait
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                <label class="form-check-label" for="rating2">
                                    2 - Insatisfait
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                <label class="form-check-label" for="rating3">
                                    3 - Neutre
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                <label class="form-check-label" for="rating4">
                                    4 - Satisfait
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                <label class="form-check-label" for="rating5">
                                    5 - Très satisfait
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="comment" class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Partagez votre expérience avec cette entreprise..."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Soumettre mon évaluation</button>
                        <a href="<?= BASE_URL ?>?page=companies&action=view&id=<?= $company['id'] ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>