<?php include VIEWS_PATH . '/templates/header.php'; ?>

<h1>Mes évaluations</h1>

<?php if (!empty($ratings)): ?>
    <ul class="list-group">
        <?php foreach ($ratings as $rating): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($rating['company_name']) ?></strong>
                <p>Note : <?= $rating['rating'] ?>/5</p>
                <p><?= htmlspecialchars($rating['comment']) ?></p>
                <a href="<?= BASE_URL ?>?page=ratings&action=edit&id=<?= $rating['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                <a href="<?= BASE_URL ?>?page=ratings&action=delete&id=<?= $rating['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune évaluation trouvée.</p>
<?php endif; ?>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>