<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="text-center mt-5">
    <h1 class="display-1 fw-bold"><?= $code ?? 500 ?></h1>
    <p class="fs-3">Erreur</p>
    <p class="lead"><?= $message ?? "Une erreur s'est produite lors du traitement de votre demande." ?></p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">Retour à l'accueil</a>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>