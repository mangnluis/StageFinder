<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Supprimer l'entreprise</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Attention !</h4>
            <p>Vous êtes sur le point de supprimer l'entreprise <strong><?= htmlspecialchars($company['name']) ?></strong>.</p>
            <p>Cette action supprimera également toutes les offres de stage associées à cette entreprise et ne pourra pas être annulée.</p>
        </div>
        
        <p>Êtes-vous sûr de vouloir continuer ?</p>
        
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?page=companies&action=delete&id=<?= $company['id'] ?>&confirm=yes" class="btn btn-danger">Oui, supprimer</a>
            <a href="<?= BASE_URL ?>?page=companies&action=view&id=<?= $company['id'] ?>" class="btn btn-outline-secondary">Non, annuler</a>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>