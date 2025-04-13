<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Bienvenue sur StageFinder</h1>
    <p class="lead">La plateforme qui vous aide à trouver le stage idéal pour votre formation.</p>
    <hr class="my-4">
    <p>Connectez-vous pour accéder à toutes les fonctionnalités ou inscrivez-vous si vous n'avez pas encore de compte.</p>
    <div class="mt-4">
        <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>?page=auth&action=login" role="button">Connexion</a>
        <a class="btn btn-outline-primary btn-lg ms-2" href="<?= BASE_URL ?>?page=auth&action=register" role="button">Inscription</a>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Étudiants</h5>
                <p class="card-text">Trouvez le stage qui correspond à vos compétences et à vos aspirations professionnelles.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Pilotes</h5>
                <p class="card-text">Suivez les recherches de stage de vos étudiants et aidez-les dans leur parcours.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Entreprises</h5>
                <p class="card-text">Publiez vos offres de stage et trouvez les meilleurs talents pour vos projets.</p>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>