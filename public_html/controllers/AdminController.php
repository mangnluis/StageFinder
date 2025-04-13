<?php
/**
 * AdminController.php - Contrôleur pour les fonctionnalités d'administration
 */
class AdminController {
    private $userModel;
    private $companyModel;
    private $internshipModel;
    private $applicationModel;
    
    /**
     * Constructeur du contrôleur
     */
    public function __construct() {
        // Vérifier que l'utilisateur est un administrateur
        Auth::requireAdmin();
        
        // Initialiser les modèles
        $this->userModel = new UserModel();
        $this->companyModel = new CompanyModel();
        $this->internshipModel = new InternshipModel();
        
        // Nous allons créer ce modèle pour gérer les candidatures
        // $this->applicationModel = new ApplicationModel();
    }
    
    /**
     * Affiche le tableau de bord d'administration
     */
    public function index() {
        // Récupérer les statistiques de base
        $stats = [
            'companies' => $this->companyModel->count(),
            'internships' => $this->internshipModel->count(),
            'students' => $this->userModel->count('student'),
            'pilots' => $this->userModel->count('pilot'),
            'applications' => $this->getApplicationsCount() // À implémenter dans un modèle dédié
        ];
        
        // Récupérer les dernières candidatures
        $latestApplications = $this->getLatestApplications(10);
        
        // Récupérer les derniers utilisateurs
        $latestUsers = $this->userModel->getAll(null, 1, 5);
        
        // Messages flash à afficher
        $flash = getFlash();
        
        // Afficher la vue
        view('admin/dashboard', [
            'pageTitle' => 'Administration',
            'stats' => $stats,
            'latestApplications' => $latestApplications,
            'latestUsers' => $latestUsers,
            'flash' => $flash
        ]);
    }
    
    /**
     * Affiche la liste des utilisateurs
     */
    public function users() {
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $role = $_GET['role'] ?? null;
        $search = $_GET['search'] ?? '';
        
        // Récupérer les utilisateurs
        if (!empty($search)) {
            $users = $this->userModel->search($search, $role);
            $totalPages = 1; // Pour simplifier
        } else {
            $users = $this->userModel->getAll($role, $page);
            $totalUsers = $this->userModel->count($role);
            $totalPages = ceil($totalUsers / ITEMS_PER_PAGE);
        }
        
        // Messages flash à afficher
        $flash = getFlash();
        
        view('admin/users', [
            'pageTitle' => 'Gestion des utilisateurs',
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'role' => $role,
            'search' => $search,
            'flash' => $flash
        ]);
    }
    
    /**
     * Page de création d'un nouvel utilisateur
     */
    public function createUser() {
        $error = '';
        
        // Traiter le formulaire de création
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $role = $_POST['role'] ?? 'student';
            
            // Valider les entrées
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif ($this->userModel->getByEmail($email)) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                // Créer l'utilisateur
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $userId = $this->userModel->create($userData);
                
                // Si c'est un étudiant, créer son profil étudiant
                if ($role === 'student') {
                    $this->userModel->updateStudentInfo($userId, [
                        'promotion' => $_POST['promotion'] ?? null
                    ]);
                }
                
                flash('success', 'L\'utilisateur a été créé avec succès.');
                redirect(BASE_URL . '?page=admin&action=users'); // Redirige vers la liste des utilisateurs
                exit;
            }
        }
        
        view('admin/create_user', [
            'pageTitle' => 'Créer un utilisateur',
            'error' => $error
        ]);
    }
    
    /**
     * Page d'édition d'un utilisateur
     */
    public function editUser($id) {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Récupérer les informations de l'étudiant si applicable
        $studentInfo = null;
        if ($user['role'] === 'student') {
            $studentInfo = $this->userModel->getStudentInfo($id);
        }
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? $user['role'];
            
            // Valider les entrées
            if (empty($firstName) || empty($lastName) || empty($email)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } else {
                // Vérifier si l'email est déjà utilisé par un autre utilisateur
                $existingUser = $this->userModel->getByEmail($email);
                if ($existingUser && $existingUser['id'] != $id) {
                    $error = 'Cet email est déjà utilisé par un autre compte.';
                } else {
                    // Préparer les données à mettre à jour
                    $userData = [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'role' => $role
                    ];
                    
                    // Ajouter le mot de passe s'il est fourni
                    if (!empty($password)) {
                        $userData['password'] = $password;
                    }
                    
                    // Mettre à jour l'utilisateur
                    $this->userModel->update($id, $userData);
                    
                    // Mettre à jour les informations de l'étudiant si applicable
                    if ($role === 'student') {
                        $this->userModel->updateStudentInfo($id, [
                            'promotion' => $_POST['promotion'] ?? null
                        ]);
                    }
                    
                    flash('success', 'L\'utilisateur a été mis à jour avec succès.');
                    redirect(BASE_URL . '?page=admin&action=users'); // Redirige vers la liste des utilisateurs
                    exit;
                }
            }
        }
        
        view('admin/edit_user', [
            'pageTitle' => 'Modifier l\'utilisateur',
            'user' => $user,
            'studentInfo' => $studentInfo,
            'error' => $error
        ]);
    }
    
    /**
     * Suppression d'un utilisateur
     */
    public function deleteUser($id) {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            view('errors/404', [
                'pageTitle' => 'Page non trouvée'
            ]);
            return;
        }
        
        // Empêcher la suppression de son propre compte
        if ($id == Auth::getUserId()) {
            flash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
            redirect(BASE_URL . '?page=admin&action=users'); // Redirige vers la liste des utilisateurs
            exit;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->userModel->delete($id);
            
            flash('success', 'L\'utilisateur a été supprimé avec succès.');
            redirect(BASE_URL . '?page=admin&action=users'); // Redirige vers la liste des utilisateurs
            exit;
        }
        
        view('admin/delete_user', [
            'pageTitle' => 'Supprimer l\'utilisateur',
            'user' => $user
        ]);
    }
    
    /**
     * Page de statistiques
     */
    public function stats() {
        // Récupérer les statistiques via les modèles appropriés
        $skillStats = $this->internshipModel->getSkillStats();
        $durationStats = $this->internshipModel->getDurationStats();
        $wishlistTopOffers = $this->internshipModel->getTopWishlistOffers(10);
        $applicationStats = $this->getApplicationStatsByCompany(10);
        
        view('admin/stats', [
            'pageTitle' => 'Statistiques',
            'skillStats' => $skillStats,
            'durationStats' => $durationStats,
            'wishlistTopOffers' => $wishlistTopOffers,
            'applicationStats' => $applicationStats
        ]);
    }
    
    /**
     * Méthode privée pour récupérer le nombre total de candidatures
     * Idéalement, cela devrait être dans un modèle dédié aux candidatures
     */
    private function getApplicationsCount() {
        $db = Database::getInstance();
        return $db->count('applications');
    }
    
    /**
     * Méthode privée pour récupérer les dernières candidatures
     * Idéalement, cela devrait être dans un modèle dédié aux candidatures
     */
    private function getLatestApplications($limit = 10) {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT a.*, i.title as internship_title, c.name as company_name, 
                    u.first_name, u.last_name
             FROM applications a
             JOIN internships i ON a.internship_id = i.id
             JOIN companies c ON i.company_id = c.id
             JOIN users u ON a.student_id = u.id
             ORDER BY a.applied_at DESC
             LIMIT ?",
            [$limit]
        );
    }
    
    /**
     * Méthode privée pour récupérer les statistiques de candidatures par entreprise
     * Idéalement, cela devrait être dans un modèle dédié aux candidatures
     */
    private function getApplicationStatsByCompany($limit = 10) {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT c.name as company_name, COUNT(a.id) as application_count 
             FROM companies c 
             JOIN internships i ON c.id = i.company_id 
             JOIN applications a ON i.id = a.internship_id 
             GROUP BY c.id 
             ORDER BY application_count DESC 
             LIMIT ?",
            [$limit]
        );
    }
}