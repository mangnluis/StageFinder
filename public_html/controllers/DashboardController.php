<?php
class DashboardController {
    private $userModel;
    private $internshipModel;
    private $companyModel;
    private $applicationModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        $this->userModel = new UserModel();
        $this->internshipModel = new InternshipModel();
        $this->companyModel = new CompanyModel();
        $this->applicationModel = new ApplicationModel(); // Nouveau modèle pour les candidatures
    }
    
    public function index() {
        $role = Auth::getUserRole();
        $userId = Auth::getUserId();
        
        // Données communes pour tous les tableaux de bord
        $dashboardData = [
            'pageTitle' => 'Tableau de bord',
            'user' => $this->userModel->getById($userId)
        ];
        
        // Rediriger vers le tableau de bord approprié selon le rôle
        if ($role === 'admin') {
            $this->adminDashboard($dashboardData);
        } elseif ($role === 'pilot') {
            $this->pilotDashboard($dashboardData);
        } else {
            $this->studentDashboard($dashboardData, $userId);
        }
    }
    
    /**
     * Prépare et affiche le tableau de bord administrateur
     */
    private function adminDashboard($data) {
        // Statistiques globales
        $data['stats'] = [
            'companies' => $this->companyModel->count(),
            'internships' => $this->internshipModel->count(),
            'students' => $this->userModel->count('student'),
            'pilots' => $this->userModel->count('pilot'),
            'applications' => $this->applicationModel->count()
        ];
        
        // Dernières candidatures avec toutes les informations nécessaires
        $data['latestApplications'] = $this->applicationModel->getLatestApplicationsWithDetails(10);
        
        // Derniers utilisateurs créés
        $data['latestUsers'] = $this->userModel->getLatestUsers(5);
        
        view('dashboard/admin', $data);
    }
    
    /**
     * Prépare et affiche le tableau de bord pilote
     */
    private function pilotDashboard($data) {
        // Statistiques pour le pilote
        $data['stats'] = [
            'internships' => $this->internshipModel->count(),
            'applications' => $this->applicationModel->count(),
            'statusStats' => $this->applicationModel->getStatusStats()
        ];
        
        // Dernières candidatures
        $data['latestApplications'] = $this->applicationModel->getLatestApplicationsWithDetails(5);
        
        // Dernières offres créées par ce pilote
        $pilotId = Auth::getUserId();
        $data['latestInternships'] = $this->internshipModel->getInternshipsByUser($pilotId, 5);
        
        view('dashboard/pilot', $data);
    }
    
    /**
     * Prépare et affiche le tableau de bord étudiant
     */
    private function studentDashboard($data, $studentId) {
        // Récupérer les candidatures de l'étudiant
        $data['applications'] = $this->applicationModel->getStudentApplications($studentId);
        
        // Récupérer la wishlist
        $data['wishlist'] = $this->internshipModel->getWishlist($studentId);
        
        // Dernières offres
        $data['latestInternships'] = $this->internshipModel->getLatestInternships(5);
        
        view('dashboard/student', $data);
    }
}