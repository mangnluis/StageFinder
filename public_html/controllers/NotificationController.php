<?php
class NotificationController {
    private $notificationModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        $this->notificationModel = new NotificationModel();
    }
    
    // Liste des notifications
    public function index() {
        $userId = Auth::getUserId();
        $notifications = $this->notificationModel->getByUser($userId);
        
        view('notification/index', [
            'pageTitle' => 'Mes notifications',
            'notifications' => $notifications
        ]);
    }
    
    // Marquer une notification comme lue
    public function markAsRead($id) {
        $userId = Auth::getUserId();
        
        // Vérifier que la notification appartient à l'utilisateur
        $notification = $this->notificationModel->getById($id);
        if (!$notification || $notification['user_id'] != $userId) {
            flash('danger', 'Notification introuvable.');
            redirect('/?page=notifications');
            exit;
        }
        
        $this->notificationModel->markAsRead($id);
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/?page=notifications');
        }
        exit;
    }
    
    // Marquer toutes les notifications comme lues
    public function markAllAsRead() {
        $userId = Auth::getUserId();
        $this->notificationModel->markAllAsRead($userId);
        
        flash('success', 'Toutes les notifications ont été marquées comme lues.');
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/?page=notifications');
        }
        exit;
    }
}