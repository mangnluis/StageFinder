<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Mon profil</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Modifier mes informations</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?page=profile" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    
                    <?php if ($user['role'] === 'student' && $studentInfo): ?>
                        <div class="mb-3">
                            <label for="promotion" class="form-label">Promotion</label>
                            <input type="text" class="form-control" id="promotion" name="promotion" value="<?= htmlspecialchars($studentInfo['promotion'] ?? '') ?>">
                        </div>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <h6>Changer de mot de passe</h6>
                    <p class="text-muted small">Laissez les champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Nom complet:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Rôle:</strong>
                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : ($user['role'] === 'pilot' ? 'bg-primary' : 'bg-success') ?>">
                            <?= $user['role'] === 'admin' ? 'Administrateur' : ($user['role'] === 'pilot' ? 'Pilote' : 'Étudiant') ?>
                        </span>
                    </li>
                    <?php if ($user['role'] === 'student' && $studentInfo): ?>
                        <li class="list-group-item">
                            <strong>Promotion:</strong> <?= htmlspecialchars($studentInfo['promotion'] ?? 'Non spécifiée') ?>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <strong>Date d'inscription:</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php if ($user['role'] === 'student'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Mes statistiques</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Récupérer les statistiques de candidature
                    $db = Database::getInstance();
                    $stats = [
                        'total' => $db->fetchColumn(
                            "SELECT COUNT(*) FROM applications WHERE student_id = ?",
                            [$user['id']]
                        ),
                        'pending' => $db->fetchColumn(
                            "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'pending'",
                            [$user['id']]
                        ),
                        'accepted' => $db->fetchColumn(
                            "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'accepted'",
                            [$user['id']]
                        ),
                        'rejected' => $db->fetchColumn(
                            "SELECT COUNT(*) FROM applications WHERE student_id = ? AND status = 'rejected'",
                            [$user['id']]
                        ),
                        'wishlist' => $db->fetchColumn(
                            "SELECT COUNT(*) FROM wishlist WHERE student_id = ?",
                            [$user['id']]
                        )
                    ];
                    ?>
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4><?= $stats['total'] ?></h4>
                                <small>Candidatures</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4><?= $stats['wishlist'] ?></h4>
                                <small>Wishlist</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="<?= BASE_URL ?>?page=applications&action=my" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-check"></i> Mes candidatures
                        </a>
                        <a href="<?= BASE_URL ?>?page=wishlist" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-heart"></i> Ma wishlist
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>