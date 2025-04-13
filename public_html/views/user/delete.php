<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid py-4">
    <!-- En-tête de page avec breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=users') ?>">Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/?page=users&action=view&id=' . $user['id']) ?>"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Supprimer</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i> Confirmation de suppression
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <h4 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Attention !</h4>
                <p>Vous êtes sur le point de supprimer l'utilisateur <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>.</p>
                <p>Cette action supprimera également :</p>
                <ul>
                    <li>Toutes les candidatures associées à cet utilisateur</li>
                    <li>Toutes les informations personnelles liées à ce compte</li>
                    <li>Toutes les évaluations ou commentaires postés par cet utilisateur</li>
                </ul>
                <p class="mb-0">Cette action est <strong>irréversible</strong> et ne pourra pas être annulée.</p>
            </div>

            <div class="text-center p-4">
                <h5>Êtes-vous absolument sûr de vouloir continuer ?</h5>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <!-- URL corrigée pour la suppression -->
                <a href="<?= url('/?page=users&action=delete&id=' . $user['id'] . '&confirm=yes') ?>" class="btn btn-danger btn-lg">
                    <i class="fas fa-trash-alt me-2"></i> Oui, supprimer définitivement
                </a>
                
                <a href="<?= url('/?page=users&action=view&id=' . $user['id']) ?>" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times me-2"></i> Non, annuler
                </a>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>