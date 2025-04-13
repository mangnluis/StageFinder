<?php
class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public function getByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }
    
    public function create($userData) {
        // Hasher le mot de passe
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        return $this->db->insert('users', $userData);
    }
    
    public function update($id, $userData) {
        // Si le mot de passe est fourni, le hasher
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        } else {
            unset($userData['password']);
        }
        
        return $this->db->update('users', $userData, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
    
    public function getAll($role = null, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM users";
        $params = [];
        
        if ($role) {
            $sql .= " WHERE role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count($role = null) {
        $sql = "SELECT COUNT(*) FROM users";
        $params = [];
        
        if ($role) {
            $sql .= " WHERE role = ?";
            $params[] = $role;
        }
        
        return $this->db->fetchColumn($sql, $params);
    }
    
    public function search($term, $role = null) {
        $sql = "SELECT * FROM users WHERE 
                (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
        $params = ["%$term%", "%$term%", "%$term%"];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getStudentInfo($userId) {
        return $this->db->fetch(
            "SELECT * FROM students WHERE user_id = ?", 
            [$userId]
        );
    }
    
    public function updateStudentInfo($userId, $data) {
        // Vérifier si l'étudiant existe déjà
        $exists = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM students WHERE user_id = ?", 
            [$userId]
        );
        
        if ($exists) {
            return $this->db->update('students', $data, 'user_id = ?', [$userId]);
        } else {
            $data['user_id'] = $userId;
            return $this->db->insert('students', $data);
        }
    }

    public function getApplicationStats($userId) {
        return $this->db->fetchAll(
            "SELECT status, COUNT(*) as count 
             FROM applications 
             WHERE student_id = ? 
             GROUP BY status",
            [$userId]
        );
    }
    
    public function getWishlistCount($userId) {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) 
             FROM wishlist 
             WHERE student_id = ?",
            [$userId]
        );
    }

    public function getStudentStats($userId) {
        $applications = $this->getApplicationStats($userId);
        $wishlistCount = $this->getWishlistCount($userId);
        
        return [
            'applications' => $applications,
            'wishlist_count' => $wishlistCount
        ];
    }

    // Méthodes à ajouter à la classe UserModel

    /**
     * Récupère les derniers utilisateurs créés
     * @param int $limit Nombre d'utilisateurs à récupérer
     * @return array Les derniers utilisateurs
     */
    public function getLatestUsers($limit = 5) {
        $sql = "SELECT id, first_name, last_name, email, role, created_at 
                FROM users 
                ORDER BY created_at DESC 
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$limit]);
    }



    /**
     * Récupère les statistiques sur les utilisateurs
     * @return array Les statistiques
     */
    public function getUserStats() {
        $stats = [
            'total' => $this->count(),
            'students' => $this->count('student'),
            'pilots' => $this->count('pilot'),
            'admins' => $this->count('admin'),
            'recentUsers' => $this->db->fetchColumn(
                "SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
            )
        ];
        
        return $stats;
    }

    /**
     * Récupère les étudiants supervisés par un pilote
     * @param int $pilotId ID du pilote
     * @return array Les étudiants
     */
    public function getStudentsByPilot($pilotId) {
        $sql = "SELECT u.* 
                FROM users u
                WHERE u.role = 'student' 
                AND u.pilot_id = ?
                ORDER BY u.last_name, u.first_name";
                
        return $this->db->fetchAll($sql, [$pilotId]);
    }

    public function getLatestApplications($studentId, $limit = 5) {
        $sql = "SELECT a.*, i.title as internship_title, c.id as company_id, c.name as company_name 
                FROM applications a 
                JOIN internships i ON a.internship_id = i.id 
                JOIN companies c ON i.company_id = c.id 
                WHERE a.student_id = ? 
                ORDER BY a.applied_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$studentId, $limit]);
    }

    public function getWishlists($studentId) {
        $sql = "SELECT w.internship_id as internship_id, i.title AS internship_title,c.id AS company_id, c.name AS company_name
                FROM wishlist w
                JOIN internships i ON w.internship_id = i.id
                JOIN companies c ON i.company_id = c.id
                WHERE w.student_id = ?";
        return $this->db->fetchAll($sql, [$studentId]);
    }


    public function getNotifications($userId, $onlyUnread = false, $limit = 10) {
        $notificationModel = new NotificationModel();
        return $notificationModel->getByUser($userId, $onlyUnread, $limit);
    }
    
    /**
     * Compte les notifications non lues d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de notifications non lues
     */
    public function countUnreadNotifications($userId) {
        $notificationModel = new NotificationModel();
        return $notificationModel->countUnread($userId);
    }
}