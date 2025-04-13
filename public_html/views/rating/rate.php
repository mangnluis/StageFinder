<?php include VIEWS_PATH . '/templates/header.php'; ?>

<h1>Évaluer une entreprise</h1>

<form method="POST">
    <div class="mb-3">
        <label for="rating" class="form-label">Note (1 à 5)</label>
        <select id="rating" name="rating" class="form-select" required>
            <option value="1">1 - Très mauvais</option>
            <option value="2">2 - Mauvais</option>
            <option value="3">3 - Moyen</option>
            <option value="4">4 - Bon</option>
            <option value="5">5 - Excellent</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Commentaire</label>
        <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>