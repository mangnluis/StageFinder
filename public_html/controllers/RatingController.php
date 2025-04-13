<?php
class RatingController {
    private $db;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        // Seuls les étudiants peuvent évaluer les entreprises
        if (!Auth::isStudent() || Auth::isAdmin()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        $this->db = Database::getInstance();
    }
    public function index() {
        // Récupérer les évaluations de l'étudiant connecté
        $ratings = $this->db->fetchAll(
            "SELECT cr.*, c.name as company_name 
             FROM company_ratings cr 
             JOIN companies c ON cr.company_id = c.id 
             WHERE cr.student_id = ?",
            [Auth::getUserId()]
        );
        
        include VIEWS_PATH . '/rating/company.php';
    }
    
    // Ajouter une évaluation pour une entreprise
    public function rateCompany($companyId) {
        // Vérifier si l'entreprise existe
        $company = $this->db->fetch(
            "SELECT * FROM companies WHERE id = ?",
            [$companyId]
        );
        
        if (!$company) {
            $_SESSION['error'] = "L'entreprise n'existe pas.";
            header('Location: ' . BASE_URL . '?page=companies');
            exit;
        }
        
        // Vérifier si l'étudiant a déjà évalué cette entreprise
        $existingRating = $this->db->fetch(
            "SELECT * FROM company_ratings 
             WHERE student_id = ? AND company_id = ?",
            [Auth::getUserId(), $companyId]
        );
        
        if ($existingRating) {
            $_SESSION['error'] = "Vous avez déjà évalué cette entreprise.";
            header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $companyId);
            exit;
        }
        
        // Traiter le formulaire d'évaluation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $comment = $_POST['comment'] ?? '';
            
            // Valider la note
            if ($rating < 1 || $rating > 5) {
                $_SESSION['error'] = "La note doit être comprise entre 1 et 5.";
                header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $companyId);
                exit;
            }
            
            // Enregistrer l'évaluation
            $this->db->insert('company_ratings', [
                'company_id' => $companyId,
                'student_id' => Auth::getUserId(),
                'rating' => $rating,
                'comment' => $comment
            ]);
            
            $_SESSION['success'] = "Votre évaluation a été enregistrée avec succès.";
            header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $companyId);
            exit;
        }
        
        include VIEWS_PATH . '/rating/company.php';
    }
    
    // Modifier une évaluation existante
    public function editCompanyRating($ratingId) {
        // Récupérer l'évaluation
        $companyId = $_GET['company_id'] ?? null;
        $studentId = $_GET['student_id'] ?? null;

        $rating = $this->db->fetch(
            "SELECT * FROM company_ratings WHERE company_id = ? AND student_id = ?",
            [$companyId, $studentId]
        );
        
        if (!$rating) {
            $_SESSION['error'] = "L'évaluation n'existe pas.";
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        // Vérifier que l'évaluation appartient à l'étudiant connecté
        if ($rating['student_id'] !== Auth::getUserId()) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à modifier cette évaluation.";
            header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $rating['company_id']);
            exit;
        }
        
        // Récupérer les informations de l'entreprise
        $company = $this->db->fetch(
            "SELECT * FROM companies WHERE id = ?",
            [$rating['company_id']]
        );
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newRating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $comment = $_POST['comment'] ?? '';
            
            // Valider la note
            if ($newRating < 1 || $newRating > 5) {
                $error = "La note doit être comprise entre 1 et 5.";
            } else {
                // Mettre à jour l'évaluation
                $this->db->update('company_ratings', [
                    'rating' => $newRating,
                    'comment' => $comment
                ], 'id = ?', [$ratingId]);
                
                $_SESSION['success'] = "Votre évaluation a été mise à jour avec succès.";
                header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $rating['company_id']);
                exit;
            }
        }
        
        include VIEWS_PATH . '/rating/edit.php';
    }
    
    // Supprimer une évaluation
    public function deleteCompanyRating($ratingId) {
        // Récupérer l'évaluation
        $companyId = $_GET['company_id'] ?? null;
        $studentId = $_GET['student_id'] ?? null;

        $rating = $this->db->fetch(
            "SELECT * FROM company_ratings WHERE company_id = ? AND student_id = ?",
            [$companyId, $studentId]
        );
        
        if (!$rating) {
            $_SESSION['error'] = "L'évaluation n'existe pas.";
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        // Vérifier que l'évaluation appartient à l'étudiant connecté
        if ($rating['student_id'] !== Auth::getUserId() && !Auth::isPilot()) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer cette évaluation.";
            header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $rating['company_id']);
            exit;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->db->delete('company_ratings', 'id = ?', [$ratingId]);
            
            $_SESSION['success'] = "L'évaluation a été supprimée avec succès.";
            header('Location: ' . BASE_URL . '?page=companies&action=view&id=' . $rating['company_id']);
            exit;
        }
        
        // Récupérer les informations de l'entreprise
        $company = $this->db->fetch(
            "SELECT * FROM companies WHERE id = ?",
            [$rating['company_id']]
        );
        
        include VIEWS_PATH . '/rating/delete.php';
    }
}