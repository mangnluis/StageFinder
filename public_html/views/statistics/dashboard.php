<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Statistiques</h1>
</div>

<div class="row">
    <!-- Statistiques des offres par compétence -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Répartition des offres par compétence</h5>
            </div>
            <div class="card-body">
                <canvas id="skillChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Statistiques des offres par durée -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Répartition des offres par durée</h5>
            </div>
            <div class="card-body">
                <canvas id="durationChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top des offres en wishlist -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Top des offres en wishlist</h5>
            </div>
            <div class="card-body">
                <?php if (empty($wishlistTopOffers)): ?>
                    <p>Aucune donnée disponible.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Entreprise</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wishlistTopOffers as $offer): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL ?>?page=internships&action=view&id=<?= $offer['id'] ?>">
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
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Statistiques des candidatures par entreprise -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Entreprises avec le plus de candidatures</h5>
            </div>
            <div class="card-body">
                <?php if (empty($applicationStats)): ?>
                    <p>Aucune donnée disponible.</p>
                <?php else: ?>
                    <canvas id="applicationChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des compétences
    <?php if (!empty($skillStats)): ?>
        var skillCtx = document.getElementById('skillChart').getContext('2d');
        var skillChart = new Chart(skillCtx, {
            type: 'bar',
            data: {
                labels: [<?= implode(', ', array_map(function($skill) { return "'" . addslashes($skill['name']) . "'"; }, $skillStats)) ?>],
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: [<?= implode(', ', array_map(function($skill) { return $skill['count']; }, $skillStats)) ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    <?php endif; ?>
    
    // Graphique des durées
    <?php if (!empty($durationStats)): ?>
        var durationCtx = document.getElementById('durationChart').getContext('2d');
        var durationChart = new Chart(durationCtx, {
            type: 'pie',
            data: {
                labels: [<?= implode(', ', array_map(function($duration) { return "'" . addslashes($duration['duration']) . "'"; }, $durationStats)) ?>],
                datasets: [{
                    data: [<?= implode(', ', array_map(function($duration) { return $duration['count']; }, $durationStats)) ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
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
    <?php endif; ?>
    
    // Graphique des candidatures par entreprise
    <?php if (!empty($applicationStats)): ?>
        var applicationCtx = document.getElementById('applicationChart').getContext('2d');
        var applicationChart = new Chart(applicationCtx, {
            type: 'horizontalBar',
            data: {
                labels: [<?= implode(', ', array_map(function($app) { return "'" . addslashes($app['company_name']) . "'"; }, $applicationStats)) ?>],
                datasets: [{
                    label: 'Nombre de candidatures',
                    data: [<?= implode(', ', array_map(function($app) { return $app['application_count']; }, $applicationStats)) ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    <?php endif; ?>
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>