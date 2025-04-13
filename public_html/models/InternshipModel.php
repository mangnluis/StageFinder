<?php
class InternshipModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getById($id) {
        $sql = "SELECT i.*, c.name as company_name 
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                WHERE i.id = ?";
                
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT i.*, c.name as company_name 
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                ORDER BY i.created_at DESC 
                LIMIT ? OFFSET ?";
                
        return $this->db->fetchAll($sql, [$limit, $offset]);
    }
    
    public function create($data) {
        $skills = $data['skills'] ?? [];
        unset($data['skills']);
        
        $this->db->beginTransaction();
        
        try {
            // Créer l'offre
            $internshipId = $this->db->insert('internships', $data);
            
            // Ajouter les compétences
            if (!empty($skills)) {
                foreach ($skills as $skillId) {
                    $this->db->insert('internship_skills', [
                        'internship_id' => $internshipId,
                        'skill_id' => $skillId
                    ]);
                }
            }
            
            $this->db->commit();
            return $internshipId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $skills = isset($data['skills']) ? $data['skills'] : null;
        unset($data['skills']);
        
        $this->db->beginTransaction();
        
        try {
            // Mettre à jour l'offre
            $this->db->update('internships', $data, 'id = ?', [$id]);
            
            // Si des compétences sont fournies, les mettre à jour
            if ($skills !== null) {
                // Supprimer les anciennes relations
                $this->db->delete('internship_skills', 'internship_id = ?', [$id]);
                
                // Ajouter les nouvelles
                foreach ($skills as $skillId) {
                    $this->db->insert('internship_skills', [
                        'internship_id' => $id,
                        'skill_id' => $skillId
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function delete($id) {
        return $this->db->delete('internships', 'id = ?', [$id]);
    }
    
    public function getSkills($internshipId) {
        $sql = "SELECT s.* 
                FROM skills s 
                JOIN internship_skills is2 ON s.id = is2.skill_id 
                WHERE is2.internship_id = ? 
                ORDER BY s.name";
                
        return $this->db->fetchAll($sql, [$internshipId]);
    }
    
    public function getAllSkills() {
        return $this->db->fetchAll("SELECT * FROM skills ORDER BY name");
    }
    
    public function search($query) {
        $sql = "SELECT i.*, c.name as company_name 
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                WHERE i.title LIKE ? OR i.description LIKE ? OR c.name LIKE ? 
                ORDER BY i.created_at DESC";
                
        $params = ["%$query%", "%$query%", "%$query%"];
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getFilteredInternships($search = '', $skillId = 0, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $query = "SELECT i.*, i.location_id, c.name as company_name 
              FROM internships i 
              JOIN companies c ON i.company_id = c.id";
        $params = [];

        if ($skillId > 0) {
            $query .= " INNER JOIN internship_skills iskill ON i.id = iskill.internship_id WHERE iskill.skill_id = ?";
            $params[] = $skillId;
        }

        if (!empty($search)) {
            $query .= $skillId > 0 ? " AND" : " WHERE";
            $query .= " (i.title LIKE ? OR i.description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        $query .= " ORDER BY i.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->fetchAll($query, $params);
    }
    
    public function countFiltered($search = '', $skillId = 0) {
        $query = "SELECT COUNT(*) FROM internships i";
        $params = [];

        if ($skillId > 0) {
            $query .= " INNER JOIN internship_skills iskill ON i.id = iskill.internship_id WHERE iskill.skill_id = ?";
            $params[] = $skillId;
        }

        if (!empty($search)) {
            $query .= $skillId > 0 ? " AND" : " WHERE";
            $query .= " (i.title LIKE ? OR i.description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        return $this->db->fetchColumn($query, $params);
    }
    
    public function count() {
        return $this->db->count('internships');
    }
    
    public function countApplications($internshipId) {
        $sql = "SELECT COUNT(*) FROM applications WHERE internship_id = ?";
        return $this->db->fetchColumn($sql, [$internshipId]);
    }
    
    public function hasApplied($studentId, $internshipId) {
        $sql = "SELECT COUNT(*) FROM applications 
                WHERE student_id = ? AND internship_id = ?";
        return $this->db->fetchColumn($sql, [$studentId, $internshipId]) > 0;
    }
    
    public function apply($data) {
        return $this->db->insert('applications', $data);
    }
    
    public function getApplications($internshipId) {
        $sql = "SELECT a.*, u.first_name, u.last_name 
                FROM applications a 
                JOIN users u ON a.student_id = u.id 
                WHERE a.internship_id = ? 
                ORDER BY a.applied_at DESC";
        return $this->db->fetchAll($sql, [$internshipId]);
    }
    
    public function getStudentApplications($studentId) {
        $sql = "SELECT a.*, i.title, c.name as company_name 
                FROM applications a 
                JOIN internships i ON a.internship_id = i.id 
                JOIN companies c ON i.company_id = c.id 
                WHERE a.student_id = ? 
                ORDER BY a.applied_at DESC";
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    public function isInWishlist($studentId, $internshipId) {
        $sql = "SELECT COUNT(*) FROM wishlist 
                WHERE student_id = ? AND internship_id = ?";
        return $this->db->fetchColumn($sql, [$studentId, $internshipId]) > 0;
    }
    
    public function addToWishlist($studentId, $internshipId) {
        return $this->db->insert('wishlist', [
            'student_id' => $studentId,
            'internship_id' => $internshipId
        ]);
    }
    
    public function removeFromWishlist($studentId, $internshipId) {
        return $this->db->delete('wishlist', 
            'student_id = ? AND internship_id = ?', 
            [$studentId, $internshipId]
        );
    }
    
    public function getWishlist($studentId) {
        $sql = "SELECT w.*, i.title, i.description, i.compensation, i.start_date, 
                i.end_date, c.name as company_name, c.id as company_id
                FROM wishlist w 
                JOIN internships i ON w.internship_id = i.id 
                JOIN companies c ON i.company_id = c.id 
                WHERE w.student_id = ? 
                ORDER BY w.added_at DESC";
        return $this->db->fetchAll($sql, [$studentId]);
    }
    

    public function getSimilarInternships($internshipId, $companyId) {
        return $this->db->fetchAll(
            "SELECT * 
            FROM internships 
            WHERE company_id = ? AND id != ? 
            LIMIT 5",
            [$companyId, $internshipId]
        );
    }


    public function getInternshipStats() {
        $sql = "SELECT i.*, COUNT(a.id) as applications_count 
                FROM internships i 
                LEFT JOIN applications a ON i.id = a.internship_id 
                GROUP BY i.id 
                ORDER BY i.created_at DESC";
                
        return $this->db->fetchAll($sql);
    }
    // Ajoutez ces méthodes à votre classe InternshipModel

    /**
     * Récupère les statistiques des offres par compétence
     * @return array Les compétences avec leur nombre d'offres
     */
    public function getSkillStats() {
        $sql = "SELECT s.name, COUNT(is2.internship_id) as count 
                FROM skills s 
                LEFT JOIN internship_skills is2 ON s.id = is2.skill_id 
                GROUP BY s.id 
                ORDER BY count DESC";
                
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les statistiques des offres par durée
     * @return array Les durées avec leur nombre d'offres
     */
    public function getDurationStats() {
        $sql = "SELECT 
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
                END";
                
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les offres les plus ajoutées aux wishlists
     * @param int $limit Limite du nombre d'offres à récupérer
     * @return array Les offres avec leur nombre d'ajouts en wishlist
     */
    public function getTopWishlistOffers($limit = 10) {
        $sql = "SELECT i.id, i.title, c.name as company_name, COUNT(w.student_id) as wishlist_count 
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                JOIN wishlist w ON i.id = w.internship_id 
                GROUP BY i.id 
                ORDER BY wishlist_count DESC 
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Récupère les statistiques des compétences les plus demandées dans les offres
     * @return array Les compétences avec leur fréquence
     */
    public function getMostRequestedSkills() {
        $sql = "SELECT s.name, COUNT(is2.internship_id) as count 
                FROM skills s 
                JOIN internship_skills is2 ON s.id = is2.skill_id 
                GROUP BY s.id 
                ORDER BY count DESC 
                LIMIT 10";
                
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère la répartition des offres par mois
     * @return array Les mois avec leur nombre d'offres
     */
    public function getInternshipsByMonth() {
        $sql = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                DATE_FORMAT(created_at, '%b %Y') as month_label,
                COUNT(*) as count
                FROM internships
                GROUP BY month
                ORDER BY month ASC";
                
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère le nombre d'offres par entreprise
     * @param int $limit Limite du nombre d'entreprises
     * @return array Les entreprises avec leur nombre d'offres
     */
    public function getInternshipsByCompany($limit = 10) {
        $sql = "SELECT c.name as company_name, COUNT(i.id) as count 
                FROM companies c 
                JOIN internships i ON c.id = i.company_id 
                GROUP BY c.id 
                ORDER BY count DESC 
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Récupère la répartition des rémunérations
     * @return array Les tranches de rémunération avec leur nombre d'offres
     */
    public function getCompensationStats() {
        $sql = "SELECT 
                CASE 
                    WHEN compensation = 0 THEN 'Non rémunéré'
                    WHEN compensation < 500 THEN 'Moins de 500€'
                    WHEN compensation < 700 THEN '500€ - 700€'
                    WHEN compensation < 900 THEN '700€ - 900€'
                    WHEN compensation < 1200 THEN '900€ - 1200€'
                    ELSE 'Plus de 1200€'
                END as range,
                COUNT(*) as count
                FROM internships
                GROUP BY range
                ORDER BY 
                CASE range
                    WHEN 'Non rémunéré' THEN 1
                    WHEN 'Moins de 500€' THEN 2
                    WHEN '500€ - 700€' THEN 3
                    WHEN '700€ - 900€' THEN 4
                    WHEN '900€ - 1200€' THEN 5
                    ELSE 6
                END";
                
        return $this->db->fetchAll($sql);
    }
        
    // Fonctions utilitaires pour cette vue
    function removeParam($param, $params) {
        $newParams = $params;
        unset($newParams[$param]);
        unset($newParams['page']);
        
        $queryString = '';
        if (!empty($newParams)) {
            $queryString = '&' . http_build_query($newParams);
        }
        
        return '?page=internships' . $queryString;
    }

    function buildPaginationUrl($page, $params) {
        $newParams = $params;
        $newParams['p'] = $page;
        unset($newParams['page']);
        
        return url('/?page=internships&' . http_build_query($newParams));
    }

    function getSkillName($skillId, $skills) {
        foreach ($skills as $skill) {
            if ($skill['id'] == $skillId) {
                return $skill['name'];
            }
        }
        return '';
    }

    function getCompanyName($companyId, $companies) {
        foreach ($companies as $company) {
            if ($company['id'] == $companyId) {
                return $company['name'];
            }
        }
        return '';
    }

    function getLocationName($locationId, $locations) {
        foreach ($locations as $location) {
            if ($location['id'] == $locationId) {
                return $location['name'];
            }
        }
        return '';
    }

    function getInternshipSkills($internshipId) {
        $db = Database::getInstance();
        $sql = "SELECT s.* 
                FROM skills s 
                JOIN internship_skills is2 ON s.id = is2.skill_id 
                WHERE is2.internship_id = ? 
                ORDER BY s.name";
                
        return $db->fetchAll($sql, [$internshipId]);
    }


        /**
     * Récupère les dernières offres de stage
     * @param int $limit Nombre d'offres à récupérer
     * @return array Les dernières offres
     */
    public function getLatestInternships($limit = 5) {
        $sql = "SELECT i.*, c.name as company_name 
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                ORDER BY i.created_at DESC 
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Récupère les offres de stage créées par un utilisateur
     * @param int $userId ID de l'utilisateur (pilote/admin)
     * @param int $limit Nombre d'offres à récupérer
     * @return array Les offres
     */
    public function getInternshipsByUser($userId, $limit = 5) {
        $sql = "SELECT i.*, c.name as company_name, 
                (SELECT COUNT(*) FROM applications WHERE internship_id = i.id) as application_count
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                WHERE i.created_by = ? 
                ORDER BY i.created_at DESC 
                LIMIT ?";
                
        return $this->db->fetchAll($sql, [$userId, $limit]);
    }

    /**
     * Récupère les statistiques des offres récentes
     * @return array Les statistiques
     */
    public function getRecentStats() {
        $stats = [
            'last24h' => $this->db->fetchColumn(
                "SELECT COUNT(*) FROM internships WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)"
            ),
            'lastWeek' => $this->db->fetchColumn(
                "SELECT COUNT(*) FROM internships WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)"
            ),
            'lastMonth' => $this->db->fetchColumn(
                "SELECT COUNT(*) FROM internships WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
            )
        ];
        
        return $stats;
    }


    public function getByIdWithLocation($id) {
        $sql = "SELECT i.*, c.name as company_name, ct.name as location_name, ct.id as location_id
                FROM internships i 
                JOIN companies c ON i.company_id = c.id 
                LEFT JOIN cities ct ON i.location_id = ct.id
                WHERE i.id = ?";
                
        return $this->db->fetch($sql, [$id]);
    }

    public function getLocationInternshipById($internshipId) {
        $sql = "SELECT c.name 
            FROM internships i 
            LEFT JOIN cities c ON i.location_id = c.id 
            WHERE i.id = ?";
        return $this->db->fetchColumn($sql, [$internshipId]);
    
    }

}

