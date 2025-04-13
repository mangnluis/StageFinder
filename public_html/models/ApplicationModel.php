<?php
/**
 * Modèle pour gérer les données liées aux candidatures
 */
class ApplicationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Compte le nombre total de candidatures
     */
    public function count() {
        return $this->db->count('applications');
    }
    
    /**
     * Récupère les dernières candidatures avec les détails
     * @param int $limit Nombre de candidatures à récupérer
     * @return array Les candidatures avec leurs détails
     */
    public function getLatestApplicationsWithDetails($limit = 10) {
        $sql = "SELECT a.*, i.title as internship_title, c.name as company_name, 
                    u.first_name, u.last_name, u.id as student_id, i.id as internship_id, c.id as company_id
                FROM applications a
                JOIN internships i ON a.internship_id = i.id
                JOIN companies c ON i.company_id = c.id
                JOIN users u ON a.student_id = u.id
                ORDER BY a.applied_at DESC
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Récupère les candidatures d'un étudiant
     * @param int $studentId ID de l'étudiant
     * @return array Les candidatures de l'étudiant
     */
    public function getStudentApplications($studentId) {
        $sql = "SELECT a.*, i.title, c.name as company_name 
                FROM applications a 
                JOIN internships i ON a.internship_id = i.id 
                JOIN companies c ON i.company_id = c.id 
                WHERE a.student_id = ? 
                ORDER BY a.applied_at DESC";
                
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    /**
     * Vérifie si un étudiant a déjà postulé à une offre
     * @param int $studentId ID de l'étudiant
     * @param int $internshipId ID de l'offre
     * @return bool True si l'étudiant a déjà postulé, false sinon
     */
    public function hasApplied($studentId, $internshipId) {
        $sql = "SELECT COUNT(*) FROM applications 
                WHERE student_id = ? AND internship_id = ?";
                
        return $this->db->fetchColumn($sql, [$studentId, $internshipId]) > 0;
    }
    
    /**
     * Crée une nouvelle candidature
     * @param array $data Données de la candidature
     * @return int ID de la candidature créée
     */
    public function create($data) {
        return $this->db->insert('applications', $data);
    }
    
    /**
     * Met à jour une candidature
     * @param int $id ID de la candidature
     * @param array $data Données à mettre à jour
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function update($id, $data) {
        return $this->db->update('applications', $data, 'id = ?', [$id]);
    }
    
    /**
     * Supprime une candidature
     * @param int $id ID de la candidature
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete($id) {
        return $this->db->delete('applications', 'id = ?', [$id]);
    }
    
    /**
     * Récupère les statistiques des candidatures par statut
     * @return array Les statistiques par statut
     */
    public function getStatusStats() {
        $sql = "SELECT status, COUNT(*) as count 
                FROM applications 
                GROUP BY status";
                
        $result = $this->db->fetchAll($sql);
        
        // Reformater en tableau associatif
        $stats = [];
        foreach ($result as $row) {
            $stats[$row['status']] = $row['count'];
        }
        
        // S'assurer que tous les statuts sont présents, même si aucune candidature
        $allStatuses = ['pending', 'accepted', 'rejected'];
        foreach ($allStatuses as $status) {
            if (!isset($stats[$status])) {
                $stats[$status] = 0;
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère les candidatures pour une offre de stage
     * @param int $internshipId ID de l'offre
     * @return array Les candidatures
     */
    public function getApplicationsByInternship($internshipId) {
        $sql = "SELECT a.*, u.first_name, u.last_name 
                FROM applications a 
                JOIN users u ON a.student_id = u.id 
                WHERE a.internship_id = ? 
                ORDER BY a.applied_at DESC";
                
        return $this->db->fetchAll($sql, [$internshipId]);
    }
    
    /**
     * Récupère une candidature par son ID
     * @param int $id ID de la candidature
     * @return array La candidature
     */
    public function getById($id) {
        $sql = "SELECT a.*, i.title as internship_title, c.name as company_name, 
                    u.first_name, u.last_name 
                FROM applications a
                JOIN internships i ON a.internship_id = i.id
                JOIN companies c ON i.company_id = c.id
                JOIN users u ON a.student_id = u.id
                WHERE a.id = ?";
                
        return $this->db->fetch($sql, [$id]);
    }
}