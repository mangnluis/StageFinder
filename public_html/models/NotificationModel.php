<?php
class NotificationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crée une nouvelle notification
     * @param int $userId ID de l'utilisateur
     * @param string $type Type de notification
     * @param string $message Message de la notification
     * @param int|null $relatedId ID de l'élément concerné (optionnel)
     * @return int ID de la notification créée
     */
    public function create($userId, $type, $message, $relatedId = null) {
        $data = [
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'related_id' => $relatedId
        ];
        
        return $this->db->insert('notifications', $data);
    }
    
    /**
     * Récupère les notifications d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @param bool $onlyUnread Récupérer uniquement les non lues
     * @param int $limit Limite de résultats
     * @return array Les notifications
     */
    public function getByUser($userId, $onlyUnread = false, $limit = 10) {
        $sql = "SELECT * FROM notifications WHERE user_id = ?";
        $params = [$userId];
        
        if ($onlyUnread) {
            $sql .= " AND is_read = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Marque une notification comme lue
     * @param int $id ID de la notification
     * @return bool Succès de l'opération
     */
    public function markAsRead($id) {
        return $this->db->update('notifications', ['is_read' => 1], 'id = ?', [$id]);
    }
    
    /**
     * Marque toutes les notifications d'un utilisateur comme lues
     * @param int $userId ID de l'utilisateur
     * @return bool Succès de l'opération
     */
    public function markAllAsRead($userId) {
        return $this->db->update('notifications', ['is_read' => 1], 'user_id = ?', [$userId]);
    }
    
    /**
     * Compte les notifications non lues d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de notifications non lues
     */
    public function countUnread($userId) {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        );
    }
    
    /**
     * Supprime les anciennes notifications
     * @param int $days Nombre de jours à conserver
     * @return bool Succès de l'opération
     */
    public function deleteOld($days = 30) {
        return $this->db->delete(
            'notifications', 
            'created_at < DATE_SUB(NOW(), INTERVAL ? DAY)',
            [$days]
        );
    }


    /**
     * Récupère une notification par son ID
     * @param int $id ID de la notification
     * @return array|null La notification ou null si non trouvée
     */
    public function getById($id) {
        return $this->db->fetchRow("SELECT * FROM notifications WHERE id = ?", [$id]);
    }


}