<?php
class WishlistModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupérer la wishlist d'un étudiant
     * @param int $studentId ID de l'étudiant
     * @return array Liste des stages dans la wishlist
     */
    public function getWishlist($studentId) {
        $sql = "SELECT i.*, c.name as company_name, l.name as location_name, i.id as internship_id, w.added_at as added_at 
                FROM wishlist w
                JOIN internships i ON w.internship_id = i.id
                JOIN companies c ON i.company_id = c.id
                LEFT JOIN cities l ON i.location_id = l.id
                WHERE w.student_id = ?
                ORDER BY w.added_at DESC";
                
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    /**
     * Vérifier si une offre est dans la wishlist d'un étudiant
     * @param int $studentId ID de l'étudiant
     * @param int $internshipId ID de l'offre de stage
     * @return bool
     */
    public function isInWishlist($studentId, $internshipId) {
        $sql = "SELECT COUNT(*) FROM wishlist WHERE student_id = ? AND internship_id = ?";
        $count = $this->db->fetchColumn($sql, [$studentId, $internshipId]);
        return $count > 0;
    }
    
    /**
     * Ajouter une offre à la wishlist
     * @param int $studentId ID de l'étudiant
     * @param int $internshipId ID de l'offre de stage
     * @return bool
     */
    public function add($studentId, $internshipId) {
        return $this->db->insert('wishlist', [
            'student_id' => $studentId,
            'internship_id' => $internshipId,
            'added_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Retirer une offre de la wishlist
     * @param int $studentId ID de l'étudiant
     * @param int $internshipId ID de l'offre de stage
     * @return bool
     */
    public function remove($studentId, $internshipId) {
        return $this->db->delete('wishlist', 'student_id = ? AND internship_id = ?', [$studentId, $internshipId]);
    }
    
    /**
     * Compter le nombre d'offres dans la wishlist d'un étudiant
     * @param int $studentId ID de l'étudiant
     * @return int
     */
    public function countItems($studentId) {
        $sql = "SELECT COUNT(*) FROM wishlist WHERE student_id = ?";
        return $this->db->fetchColumn($sql, [$studentId]);
    }

    

}