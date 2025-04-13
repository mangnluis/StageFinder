<?php
class StatisticsController {
    private $db;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté et a les droits appropriés
        Auth::requireLogin();
        if (!Auth::isAdmin() && !Auth::isPilot()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        $this->db = Database::getInstance();
    }
    
    // Tableau de bord des statistiques générales
    public function index() {
        // Récupérer les statistiques des offres par compétence
        $skillStats = $this->getSkillStatistics();
        
        // Récupérer les statistiques des offres par durée
        $durationStats = $this->getDurationStatistics();
        
        // Récupérer le top des offres en wishlist
        $wishlistTopOffers = $this->getWishlistTopOffers();
        
        // Récupérer les statistiques des candidatures
        $applicationStats = $this->getApplicationStatistics();
        
        include VIEWS_PATH . '/statistics/dashboard.php';
    }
    
    // Statistiques spécifiques aux étudiants
    public function student($id = null) {
        // Si l'ID de l'étudiant n'est pas fourni et si l'utilisateur est un étudiant, utiliser son propre ID
        if (!$id && Auth::isStudent()) {
            $id = Auth::getUserId();
        }
        
        // Vérifier que l'ID est valide
        if (!$id) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Récupérer les informations de l'étudiant
        $student = $this->db->fetch(
            "SELECT u.* FROM users u 
             JOIN students s ON u.id = s.user_id 
             WHERE u.id = ?",
            [$id]
        );
        
        if (!$student) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Statistiques des candidatures de l'étudiant
        $applications = $this->db->fetchAll(
            "SELECT a.*, i.title as internship_title, c.name as company_name 
             FROM applications a 
             JOIN internships i ON a.internship_id = i.id 
             JOIN companies c ON i.company_id = c.id 
             WHERE a.student_id = ? 
             ORDER BY a.applied_at DESC",
            [$id]
        );
        
        // Calcul des statistiques
        $totalApplications = count($applications);
        $pendingApplications = 0;
        $acceptedApplications = 0;
        $rejectedApplications = 0;
        
        foreach ($applications as $app) {
            if ($app['status'] === 'pending') $pendingApplications++;
            elseif ($app['status'] === 'accepted') $acceptedApplications++;
            elseif ($app['status'] === 'rejected') $rejectedApplications++;
        }
        
        // Récupérer les compétences des offres auxquelles l'étudiant a postulé
        $appliedSkills = $this->db->fetchAll(
            "SELECT s.name, COUNT(s.id) as count 
             FROM skills s 
             JOIN internship_skills is2 ON s.id = is2.skill_id 
             JOIN applications a ON is2.internship_id = a.internship_id 
             WHERE a.student_id = ? 
             GROUP BY s.id 
             ORDER BY count DESC",
            [$id]
        );
        
        include VIEWS_PATH . '/statistics/student.php';
    }
    
    // Statistiques des offres par compétence
    private function getSkillStatistics() {
        return $this->db->fetchAll(
            "SELECT s.name, COUNT(is2.internship_id) as count 
             FROM skills s 
             LEFT JOIN internship_skills is2 ON s.id = is2.skill_id 
             GROUP BY s.id 
             ORDER BY count DESC"
        );
    }
    
    // Statistiques des offres par durée
    private function getDurationStatistics() {
        return $this->db->fetchAll(
            "SELECT 
                CASE 
                    WHEN DATEDIFF(end_date, start_date) <= 30 THEN '1 mois ou moins'
                    WHEN DATEDIFF(end_date, start_date) <= 60 THEN '1 à 2 mois'
                    WHEN DATEDIFF(end_date, start_date) <= 90 THEN '2 à 3 mois'
                    WHEN DATEDIFF(end_date, start_date) <= 180 THEN '3 à 6 mois'
                    ELSE 'Plus de 6 mois'
                END as duration,
                COUNT(*) as count
             FROM internships 
             WHERE start_date IS NOT NULL AND end_date IS NOT NULL
             GROUP BY duration
             ORDER BY 
                CASE duration
                    WHEN '1 mois ou moins' THEN 1
                    WHEN '1 à 2 mois' THEN 2
                    WHEN '2 à 3 mois' THEN 3
                    WHEN '3 à 6 mois' THEN 4
                    ELSE 5
                END"
        );
    }
    
    // Top des offres en wishlist
    private function getWishlistTopOffers() {
        return $this->db->fetchAll(
            "SELECT i.id, i.title, c.name as company_name, COUNT(w.student_id) as wishlist_count 
             FROM internships i 
             JOIN companies c ON i.company_id = c.id 
             JOIN wishlist w ON i.id = w.internship_id 
             GROUP BY i.id 
             ORDER BY wishlist_count DESC 
             LIMIT 10"
        );
    }
    
    // Statistiques des candidatures
    private function getApplicationStatistics() {
        return $this->db->fetchAll(
            "SELECT c.name as company_name, COUNT(a.id) as application_count 
             FROM companies c 
             JOIN internships i ON c.id = i.company_id 
             JOIN applications a ON i.id = a.internship_id 
             GROUP BY c.id 
             ORDER BY application_count DESC 
             LIMIT 10"
        );
    }
}