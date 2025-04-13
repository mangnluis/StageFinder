<?php
class ApplicationController {
    private $db;
    private $internshipModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        $this->db = Database::getInstance();
        $this->internshipModel = new InternshipModel();
    }
    
    // Liste des candidatures (pour admin et pilote)
    public function index() {
        // Seuls les administrateurs et les pilotes peuvent voir toutes les candidatures
        if (!Auth::isAdmin() && !Auth::isPilot()) {
            flash('danger', 'Accès non autorisé.');
            redirect('/?page=dashboard');
            exit;
        }
        
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        // Récupérer les candidatures
        $applications = $this->db->fetchAll(
            "SELECT a.*, i.title as internship_title, c.name as company_name, c.id as company_id,
                    u.first_name, u.last_name 
             FROM applications a 
             JOIN internships i ON a.internship_id = i.id 
             JOIN companies c ON i.company_id = c.id 
             JOIN users u ON a.student_id = u.id 
             ORDER BY a.applied_at DESC 
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
        
        // Compter le nombre total de candidatures
        $totalApplications = $this->db->fetchColumn("SELECT COUNT(*) FROM applications");
        $totalPages = ceil($totalApplications / $limit);
        
        view('application/list', [
            'pageTitle' => 'Toutes les candidatures',
            'applications' => $applications,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }
    
    // Liste des candidatures de l'étudiant connecté
    public function myApplications() {
        // Vérifier que l'utilisateur est un étudiant
        if (!Auth::isStudent()) {
            flash('danger', 'Accès non autorisé.');
            redirect('/?page=dashboard');
            exit;
        }
        
        // Récupérer les candidatures de l'étudiant
        $applications = $this->internshipModel->getStudentApplications(Auth::getUserId());
        
        view('application/my_applications', [
            'pageTitle' => 'Mes candidatures',
            'applications' => $applications
        ]);
    }
    
    // Voir les détails d'une candidature
    public function view($id) {
        // Récupérer la candidature
        $application = $this->db->fetch(
            "SELECT a.*, i.title as internship_title, c.name as company_name, c.id as company_id, 
                    u.first_name, u.last_name, u.email 
             FROM applications a 
             JOIN internships i ON a.internship_id = i.id 
             JOIN companies c ON i.company_id = c.id 
             JOIN users u ON a.student_id = u.id 
             WHERE a.id = ?",
            [$id]
        );
        
        if (!$application) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Vérifier les permissions
        $isOwner = $application['student_id'] == Auth::getUserId();
        
        if (!Auth::isAdmin() && !Auth::isPilot() && !$isOwner) {
            flash('danger', 'Accès non autorisé.');
            redirect('/?page=dashboard');
            exit;
        }
        
        view('application/view', [
            'pageTitle' => 'Détails de la candidature',
            'application' => $application,
            'isOwner' => $isOwner
        ]);
    }
    
    // Mettre à jour le statut d'une candidature
    // Dans la méthode updateStatus de ApplicationController

    // Mettre à jour le statut d'une candidature
    public function updateStatus($id) {
        // Seuls les administrateurs et les pilotes peuvent modifier le statut
        if (!Auth::isAdmin() && !Auth::isPilot()) {
            flash('danger', 'Accès non autorisé.');
            redirect('/?page=dashboard');
            exit;
        }
        
        // Vérifier si la méthode est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?page=applications&action=view&id=' . $id);
            exit;
        }
        
        // Récupérer le nouveau statut
        $status = $_POST['status'] ?? '';
        
        // Valider le statut
        if (!in_array($status, ['pending', 'accepted', 'rejected'])) {
            flash('danger', 'Statut invalide.');
            redirect('/?page=applications&action=view&id=' . $id);
            exit;
        }
        
        // Récupérer les informations de la candidature pour la notification
        $application = $this->db->fetch(
            "SELECT a.* FROM applications a WHERE a.id = ?",
            [$id]
        );
        
        if (!$application) {
            flash('danger', 'Candidature introuvable.');
            redirect('/?page=applications');
            exit;
        }
        
        // Mettre à jour le statut
        if ($this->db->update('applications', ['status' => $status], 'id = ?', [$id])) {
            flash('success', 'Le statut de la candidature a été mis à jour avec succès.');
        } else {
            flash('danger', 'Erreur lors de la mise à jour du statut de la candidature.');
            redirect('/?page=applications&action=view&id=' . $id);
            exit;
        }
           
        
        // Envoyer une notification à l'étudiant
        $notificationService = new NotificationService();
        if ($notificationService->notifyApplicationStatus($application['student_id'], $id, $status)) {
            // Si la notification a été envoyée avec succès
            flash('success', 'Le statut de la candidature a été mis à jour et une notification a été envoyée à l\'étudiant.');
        } else {
            // Si la notification a échoué
            flash('warning', 'Le statut de la candidature a été mis à jour, mais l\'envoi de la notification a échoué.');
        }
        
        flash('success', 'Le statut de la candidature a été mis à jour et une notification a été envoyée à l\'étudiant.');
        redirect('/?page=applications&action=view&id=' . $id);
        exit;
    }
}