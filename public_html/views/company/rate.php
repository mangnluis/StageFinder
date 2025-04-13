<?php include VIEWS_PATH . '/templates/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=companies') ?>">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>"><?= htmlspecialchars($company['name']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page">Évaluer</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h1 class="h4 mb-0"><i class="fas fa-star me-2"></i> Évaluer l'entreprise : <?= htmlspecialchars($company['name']) ?></h1>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= url('/?page=companies&action=rate&id=' . $company['id']) ?>" method="post">
                    <div class="mb-4">
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        
                        <div class="rating-input mb-2">
                            <div class="d-flex justify-content-between">
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star" data-rating="<?= $i ?>">
                                            <i class="far fa-star fa-2x"></i>
                                        </span>
                                    <?php endfor; ?>
                                </div>
                                <div class="rating-text">
                                    <span id="ratingText" class="badge bg-secondary">Sélectionnez une note</span>
                                </div>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="<?= $userRating ? $userRating['rating'] : '0' ?>" required>
                        </div>
                        
                        <div class="rating-description text-muted small">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-star text-warning"></i> <span>1 - Très insatisfait</span>
                                </div>
                                <div class="col">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> <span>2 - Insatisfait</span>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> <span>3 - Moyen</span>
                                </div>
                                <div class="col">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> <span>4 - Satisfait</span>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col">
                                    <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i> <span>5 - Très satisfait</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="comment" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Partagez votre expérience avec cette entreprise..."><?= $userRating ? htmlspecialchars($userRating['comment']) : '' ?></textarea>
                        <div class="form-text">Conseils : Mentionnez l'environnement de travail, l'accompagnement, les missions, etc.</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <?= $userRating ? 'Mettre à jour' : 'Soumettre' ?> mon évaluation
                        </button>
                        <a href="<?= url('/?page=companies&action=view&id=' . $company['id']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    
    // Textes correspondant aux notes
    const ratingLabels = [
        '',
        'Très insatisfait',
        'Insatisfait',
        'Moyen',
        'Satisfait',
        'Très satisfait'
    ];
    
    // Couleurs correspondant aux notes
    const ratingColors = [
        'bg-secondary',
        'bg-danger',
        'bg-warning',
        'bg-info',
        'bg-primary',
        'bg-success'
    ];
    
    // Initialiser avec la note existante si disponible
    const existingRating = parseInt(ratingInput.value);
    if (existingRating > 0) {
        updateStars(existingRating);
    }
    
    // Gérer les événements de survol et de clic
    stars.forEach(star => {
        // Survol - afficher les étoiles jusqu'à celle survolée
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });
        
        // Sortie du survol - revenir à la sélection
        star.addEventListener('mouseout', function() {
            const selectedRating = parseInt(ratingInput.value);
            updateStars(selectedRating);
        });
        
        // Clic - sélectionner cette note
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            updateStars(rating);
        });
    });
    
    // Mettre en surbrillance les étoiles jusqu'à la position indiquée
    function highlightStars(position) {
        stars.forEach(s => {
            const starRating = parseInt(s.getAttribute('data-rating'));
            const icon = s.querySelector('i');
            
            if (starRating <= position) {
                icon.className = 'fas fa-star fa-2x text-warning';
            } else {
                icon.className = 'far fa-star fa-2x';
            }
        });
    }
    
    // Mettre à jour les étoiles et le texte de la note
    function updateStars(rating) {
        highlightStars(rating);
        
        if (rating > 0) {
            ratingText.innerText = ratingLabels[rating];
            ratingText.className = `badge ${ratingColors[rating]}`;
        } else {
            ratingText.innerText = 'Sélectionnez une note';
            ratingText.className = 'badge bg-secondary';
        }
    }
});
</script>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>