</div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-3">StageFinder</h5>
                    <p class="text-muted">La plateforme qui connecte les étudiants avec les meilleures opportunités de stage.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/') ?>" class="text-muted">Accueil</a></li>
                        <li><a href="<?= url('/?page=internships') ?>" class="text-muted">Offres de stage</a></li>
                        <li><a href="<?= url('/?page=companies') ?>" class="text-muted">Entreprises</a></li>
                        <?php if (!Auth::isLoggedIn()): ?>
                            <li><a href="<?= url('/?page=auth&action=register') ?>" class="text-muted">Créer un compte</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt fa-fw me-1"></i> CESI, 32 Rue Jean Rostand, 31670 Labège</li>
                        <li><i class="fas fa-phone fa-fw me-1"></i> 05 61 00 70 00</li>
                        <li><i class="fas fa-envelope fa-fw me-1"></i> contact@stagefinder.fr</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; <?= date('Y') ?> StageFinder. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Chart.js (For statistics) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('/js/main2.js') ?>"></script>
    <script src="<?= asset('/js/validation2.js') ?>"></script>
        


</body>
</html>