<?php include VIEWS_PATH . '/templates/header.php'; ?>

<div class="container py-4">
    <h1 class="mb-4">Mes notifications</h1>
    
    <?php if (empty($notifications)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Vous n'avez aucune notification.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Notifications</h5>
                <a href="<?= url('/?page=notifications&action=markAllAsRead') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double me-2"></i> Marquer tout comme lu
                </a>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item list-group-item-action <?= $notification['is_read'] ? '' : 'bg-light' ?> py-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-<?= $notification['type'] === 'application_status' ? 'file-alt' : 'bell' ?> text-primary fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?></small>
                                    <?php if (!$notification['is_read']): ?>
                                        <a href="<?= url('/?page=notifications&action=markAsRead&id=' . $notification['id']) ?>" class="btn btn-sm btn-light">
                                            <i class="fas fa-check me-1"></i> Marquer comme lu
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include VIEWS_PATH . '/templates/footer.php'; ?>