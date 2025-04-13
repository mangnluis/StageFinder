<?php include VIEWS_PATH . '/templates/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section text-red text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInUp" style="color:rgb(12, 2, 68)" >Trouvez le stage parfait pour votre avenir</h1>
                <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                    StageFinder vous connecte avec des entreprises proposant des stages qui correspondent à vos compétences et à vos aspirations.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center animate__animated animate__fadeInUp animate__delay-2s">
                    <?php if (!Auth::isLoggedIn()): ?>
                        <a href="<?= url('/?page=auth&action=login') ?>" class="btn btn-primary btn-lg px-4 me-md-2">Se connecter</a>
                        <a href="<?= url('/?page=internships') ?>" class="btn btn-primary-light btn-lg px-4">Voir les offres</a>
                    <?php else: ?>
                        <a href="<?= url('/?page=internships') ?>" class="btn btn-primary btn-lg px-4 me-md-2">Explorer les offres</a>
                        <a href="<?= url('/?page=dashboard') ?>" class="btn btn-outline-dark btn-lg px-4">Mon tableau de bord</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4" >
                <a href="<?= url('/?page=companies') ?>" class="text-decoration-none" style = "color:rgb(12, 2, 68)">
                    <div class="counter-card p-4 bg-white rounded shadow-sm hover-effect">
                        <div class="counter-icon mb-3">
                            <i class="fas fa-building-user fa-3x text-primary"></i>
                        </div>
                        <h3 class="counter-number"><?= $stats['companies'] ?></h3>
                        <p class="counter-text mb-0">Entreprises partenaires</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= url('/?page=internships') ?>" class="text-decoration-none" style = "color:rgb(12, 2, 68)">
                    <div class="counter-card p-4 bg-white rounded shadow-sm hover-effect">
                        <div class="counter-icon mb-3">
                            <i class="fas fa-laptop fa-3x text-success"></i>
                        </div>
                        <h3 class="counter-number"><?= $stats['internships'] ?></h3>
                        <p class="counter-text mb-0">Offres de stage</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= url('/?page=users&action=list') ?>" class="text-decoration-none" style = "color:rgb(12, 2, 68)">
                    <div class="counter-card p-4 bg-white rounded shadow-sm hover-effect">
                        <div class="counter-icon mb-3">
                            <i class="fas fa-user-graduate fa-3x text-danger"></i>
                        </div>
                        <h3 class="counter-number"><?= $stats['students'] ?></h3>
                        <p class="counter-text mb-0">Étudiants inscrits</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Internships -->
<section class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">Dernières offres de stage</h2>
            <p class="text-muted">Découvrez les opportunités qui viennent d'être publiées</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($latestInternships as $internship): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 internship-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($internship['title']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($internship['company_name']) ?></h6>
                            <p class="card-text">
                                <?= (strlen($internship['description']) > 100) 
                                    ? htmlspecialchars(substr($internship['description'], 0, 100)) . '...' 
                                    : htmlspecialchars($internship['description']) ?>
                            </p>
                            <?php if (!empty($internship['compensation'])): ?>
                                <div class="badge bg-success mb-2">
                                    <i class="fas fa-euro-sign"></i> <?= number_format($internship['compensation'], 2, ',', ' ') ?> €
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> 
                                <?= !empty($internship['start_date']) ? date('d/m/Y', strtotime($internship['start_date'])) : 'Date non spécifiée' ?>
                            </small>
                            <a href="<?= url('/?page=internships&action=view&id=' . $internship['id']) ?>" class="btn btn-sm btn-outline-primary">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= url('/?page=internships') ?>" class="btn btn-primary">
                Voir toutes les offres <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Top Companies -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">Entreprises les mieux notées</h2>
            <p class="text-muted">Des entreprises reconnues pour la qualité de leurs stages</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($topCompanies as $company): ?>
                <div class="col-md-4">
                    <div class="card company-card">
                        <div class="card-body text-center">
                            <div class="company-icon mb-3">
                                <i class="fas fa-building-user fa-3x"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($company['name']) ?></h5>
                            <div class="company-rating mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($company['average_rating'])): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ms-2">(<?= $company['rating_count'] ?> avis)</span>
                            </div>
                            <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-outline-primary">
                                Voir le profil
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">Comment ça marche ?</h2>
            <p class="text-muted">Un processus simple pour trouver votre stage idéal</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="how-it-works-card text-center p-4">
                    <div class="step-icon mb-3">

                
                        <i class="fas fa-user-plus fa-3x text-primary"></i>
                    </div>
                    <h4>Créez votre compte</h4>
                    <p class="text-muted">Inscrivez-vous gratuitement et complétez votre profil avec vos compétences et expériences.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-card text-center p-4">
                    <div class="step-icon mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h4>Recherchez des stages</h4>
                    <p class="text-muted">Explorez les offres de stage disponibles et filtrez selon vos critères.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-card text-center p-4">
                    <div class="step-icon mb-3">
                        <i class="fas fa-paper-plane fa-3x text-primary"></i>
                    </div>
                    <h4>Postulez en quelques clics</h4>
                    <p class="text-muted">Envoyez votre candidature rapidement et suivez son statut en temps réel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Prêt à trouver votre stage idéal ?</h2>
                <p class="lead mb-4">
                    Rejoignez StageFinder dès aujourd'hui et commencez votre recherche de stage.
                </p>
                <?php if (!Auth::isLoggedIn()): ?>
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <?php /*<a href="<?= url('/?page=auth&action=register') ?>" class="btn btn-light btn-lg px-4 gap-3">
                            Créer un compte <i class="fas fa-arrow-right ms-2"></i>
                        </a>*/
                        ?>
                        <a href="<?= url('/?page=auth&action=login') ?>" class="btn btn-light btn-lg px-4 gap-3">
                            Se connecter <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/?page=internships') ?>" class="btn btn-light btn-lg px-4">
                        Explorer les offres <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>