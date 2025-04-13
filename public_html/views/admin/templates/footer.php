</div><!-- End of .main-content -->
    
    <!-- Admin Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span>&copy; <?= date('Y') ?> StageFinder - Administration</span>
                </div>
                <div>
                    <a href="<?= url('home') ?>" class="text-white me-3">
                        <i class="fas fa-home"></i> Retour au site
                    </a>
                    <a href="<?= url('auth', 'logout') ?>" class="text-white">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js (Pour les statistiques) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- AdminLTE JS -->
    <script src="<?= asset('/js/admin.js') ?>"></script>
</body>
</html>