<?php include VIEWS_PATH . '/templates/header.php'; ?>

<h1>Modifier une évaluation</h1>

<form method="POST">
    <div class="mb-3">
        <label for="rating" class="form-label">Note (1 à 5)</label>
        <select id="rating" name="rating" class="form-select" required>
            <option value="1" <?= $rating['rating'] == 1 ? 'selected' : '' ?>>1 - Très mauvais</option>
            <option value="2" <?= $rating['rating'] == 2 ? 'selected' : '' ?>>2 - Mauvais</option>
            <option value="3" <?= $rating['rating'] == 3 ? 'selected' : '' ?>>3 - Moyen</option>
            <option value="4" <?= $rating['rating'] == 4 ? 'selected' : '' ?>>4 - Bon</option>
            <option value="5" <?= $rating['rating'] == 5 ? 'selected' : '' ?>>5 - Excellent</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Commentaire</label>
        <textarea id="comment" name="comment" class="form-control" rows="3"><?= htmlspecialchars($rating['comment']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>