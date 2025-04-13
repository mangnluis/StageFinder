<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container-fluid statistics-page">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6">
                        <i class="fas fa-chart-bar text-primary me-3"></i>Statistiques de StageFinder
                    </h1>
                    <p class="text-muted">Vue d'ensemble des données de la plateforme</p>
                </div>
                <div class="export-options">
                    <button class="btn btn-outline-primary me-2" id="exportPDF">
                        <i class="fas fa-file-pdf me-2"></i>Exporter PDF
                    </button>
                    <button class="btn btn-outline-success" id="exportCSV">
                        <i class="fas fa-file-csv me-2"></i>Exporter CSV
                    </button>
                </div>
            </div>

            <!-- Statistiques des offres par compétence -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-code me-2 text-primary"></i>Offres par compétence
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="skillsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Statistiques des offres par durée -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>Durée des stages
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="durationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top des offres en wishlist et candidatures -->
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-heart me-2 text-primary"></i>Top offres en wishlist
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Dans wishlist</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wishlistTopOffers as $offer): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= url('/?page=internships&action=view&id=' . $offer['id']) ?>">
                                                    <?= htmlspecialchars($offer['title']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($offer['company_name']) ?></td>
                                            <td><?= $offer['wishlist_count'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2 text-primary"></i>Entreprises avec le plus de candidatures
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="applicationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js configurations
    // (Utiliser $skillStats, $durationStats, $applicationStats pour générer les graphiques)
    
    // Graphique des compétences
    const skillsCtx = document.getElementById('skillsChart').getContext('2d');
    new Chart(skillsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($skillStats, 'name')) ?>,
            datasets: [{
                label: 'Nombre d\'offres',
                data: <?= json_encode(array_column($skillStats, 'count')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des durées
    const durationCtx = document.getElementById('durationChart').getContext('2d');
    new Chart(durationCtx, {
        type: 'pie',
        data: {
                labels: <?= json_encode(array_column($durationStats, 'duration')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($durationStats, 'count')) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

    // Graphique des candidatures par entreprise
    const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
    new Chart(applicationsCtx, {
        type: 'horizontalBar',
        data: {
            labels: <?= json_encode(array_column($applicationStats, 'company_name')) ?>,
            datasets: [{
                label: 'Nombre de candidatures',
                data: <?= json_encode(array_column($applicationStats, 'application_count')) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Exportation PDF et CSV
    document.getElementById('exportPDF').addEventListener('click', function() {
        alert('Fonctionnalité d\'exportation PDF en développement');
    });

    document.getElementById('exportCSV').addEventListener('click', function() {
        alert('Fonctionnalité d\'exportation CSV en développement');
    });
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>
