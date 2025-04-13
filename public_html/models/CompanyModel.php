<?php
class CompanyModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM companies WHERE id = ?", [$id]);
    }
    
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM companies ORDER BY name LIMIT ? OFFSET ?";
        return $this->db->fetchAll($sql, [$limit, $offset]);
    }
    
    public function create($data) {
        return $this->db->insert('companies', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('companies', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('companies', 'id = ?', [$id]);
    }
    
    public function search($query) {
        $sql = "SELECT * FROM companies 
                WHERE name LIKE ? OR description LIKE ? OR contact_email LIKE ? 
                ORDER BY name";
        
        $params = ["%$query%", "%$query%", "%$query%"];
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count() {
        return $this->db->count('companies');
    }
    
    public function getInternships($companyId) {
        $sql = "SELECT * FROM internships WHERE company_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$companyId]);
    }
    
    public function getTopRated($limit = 5) {
        $sql = "SELECT c.*, AVG(r.rating) as average_rating, COUNT(r.id) as rating_count 
                FROM companies c 
                JOIN company_ratings r ON c.id = r.company_id 
                GROUP BY c.id 
                HAVING rating_count >= 3 
                ORDER BY average_rating DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }


    public function getAverageRating($companyId) {
        return $this->db->fetchColumn(
            "SELECT AVG(rating) 
            FROM company_ratings 
            WHERE company_id = ?",
            [$companyId]
        );
    }

    public function getRatingCount($companyId) {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) 
            FROM company_ratings 
            WHERE company_id = ?",
            [$companyId]
        );
    }

    public function getCities()
    {
        return $this->db->fetchAll("
            SELECT id, name
            FROM cities
            ORDER BY name ASC
        ");  
    }

    public function getIndustries()
    {
        return $this->db->fetchAll("
            SELECT DISTINCT id, i.name 
            FROM industries i
            ORDER BY id ASC
        ");
    }


    public function getIndustryName($industryId) {
        $result = $this->db->fetch("SELECT name FROM industries WHERE id = ?", [$industryId]);
        return $result ? $result['name'] : '';
    }
    
    /**
     * Récupère le nom d'une ville à partir de son ID
     */
    public function getLocationName($locationId) {
        $result = $this->db->fetch("SELECT name FROM cities WHERE id = ?", [$locationId]);
        return $result ? $result['name'] : '';
    }


    public function getEnrichedCompanies($page = 1, $filters = []) {
        // Configuration de base
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        // Construction de la requête
        $query = "SELECT c.*, 
                   (SELECT AVG(r.rating) FROM company_ratings r WHERE r.company_id = c.id) as avg_rating,
                   (SELECT COUNT(r.id) FROM company_ratings r WHERE r.company_id = c.id) as rating_count,
                   (SELECT COUNT(i.id) FROM internships i WHERE i.company_id = c.id) as offer_count,
                   (SELECT name FROM cities WHERE id = c.city_id) as location,
                   (SELECT name FROM industries WHERE id = c.industry_id) as industry
                   FROM companies c";
        
        $where = [];
        $params = [];
        
        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $where[] = "(c.name LIKE ? OR c.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['industry'])) {
            $where[] = "c.industry_id = ?";
            $params[] = $filters['industry'];
        }
        
        if (!empty($filters['location'])) {
            $where[] = "c.city_id = ?";
            $params[] = $filters['location'];
        }
        
        // Ajouter la clause WHERE si nécessaire
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        // Appliquer le tri
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'name_asc':
                    $query .= " ORDER BY c.name ASC";
                    break;
                case 'name_desc':
                    $query .= " ORDER BY c.name DESC";
                    break;
                case 'rating_desc':
                    $query .= " ORDER BY avg_rating DESC";
                    break;
                case 'recent':
                    $query .= " ORDER BY c.created_at DESC";
                    break;
                case 'offers_desc':
                    $query .= " ORDER BY offer_count DESC";
                    break;
                default:
                    $query .= " ORDER BY c.name ASC";
            }
        } else {
            $query .= " ORDER BY c.name ASC";
        }
        
        // Ajouter la pagination
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($query, $params);
    }
    
    /**
     * Compte le nombre total d'entreprises après filtrage
     */
    public function countFiltered($filters = []) {
        $query = "SELECT COUNT(*) FROM companies c";
        
        $where = [];
        $params = [];
        
        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $where[] = "(c.name LIKE ? OR c.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['industry'])) {
            $where[] = "c.industry_id = ?";
            $params[] = $filters['industry'];
        }
        
        if (!empty($filters['location'])) {
            $where[] = "c.city_id = ?";
            $params[] = $filters['location'];
        }
        
        // Ajouter la clause WHERE si nécessaire
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        return $this->db->fetchColumn($query, $params);
    }


}