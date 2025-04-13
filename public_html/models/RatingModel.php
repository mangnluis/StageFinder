<?php
class RatingModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getCompanyRatings($companyId) {
        $sql = "SELECT r.*, u.first_name, u.last_name 
                FROM company_ratings r 
                JOIN users u ON r.student_id = u.id 
                WHERE r.company_id = ? 
                ORDER BY r.rated_at DESC";
        return $this->db->fetchAll($sql, [$companyId]);
    }
    
    public function getStudentRating($studentId, $companyId) {
        $sql = "SELECT * FROM company_ratings 
                WHERE student_id = ? AND company_id = ?";
        return $this->db->fetch($sql, [$studentId, $companyId]);
    }
    
    public function getCompanyAverageRating($companyId) {
        $sql = "SELECT AVG(rating) FROM company_ratings WHERE company_id = ?";
        return $this->db->fetchColumn($sql, [$companyId]);
    }
    
    public function rateCompany($data) {
        // Vérifier si l'étudiant a déjà évalué cette entreprise
        $existing = $this->getStudentRating($data['student_id'], $data['company_id']);
        
        if ($existing) {
            // Mettre à jour l'évaluation existante
            return $this->db->update('company_ratings', 
                ['rating' => $data['rating'], 'comment' => $data['comment']], 
                'student_id = ? AND company_id = ?', 
                [$data['student_id'], $data['company_id']]
            );
        } else {
            // Créer une nouvelle évaluation
            return $this->db->insert('company_ratings', $data);
        }
    }
    
    public function deleteRating($studentId, $companyId) {
        return $this->db->delete('company_ratings', 
            'student_id = ? AND company_id = ?', 
            [$studentId, $companyId]
        );
    }
    
    public function getStudentRatings($studentId) {
        $sql = "SELECT r.*, c.name as company_name 
                FROM company_ratings r 
                JOIN companies c ON r.company_id = c.id 
                WHERE r.student_id = ? 
                ORDER BY r.rated_at DESC";
        return $this->db->fetchAll($sql, [$studentId]);
    }
}