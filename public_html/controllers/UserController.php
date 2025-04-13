<?php
class UserController {
    private $userModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        // Seuls les administrateurs et les pilotes peuvent accéder à ce contrôleur
        if (!Auth::isAdmin() && !Auth::isPilot()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        $this->userModel = new UserModel();
    }
    
    // Liste des utilisateurs
    public function index() {
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $role = isset($_GET['role']) ? $_GET['role'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        // Récupérer les utilisateurs
        if (!empty($search)) {
            $users = $this->userModel->search($search);
            $totalPages = 1; // Pour simplifier
        } else {
            $users = $this->userModel->getAll($role, $page);
            $totalUsers = $this->userModel->count($role);
            $totalPages = ceil($totalUsers / ITEMS_PER_PAGE);
        }
        
        include VIEWS_PATH . '/user/list.php';
    }
    
    // Afficher un utilisateur
    public function view($id) {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        $applications = $this->userModel->getLatestApplications($user['id']);
        
        
        $wishlists = $this->userModel->getWishlists($user['id']);

        // Si l'utilisateur est un étudiant, récupérer ses informations supplémentaires
        $studentInfo = null;
        if ($user['role'] === 'student') {
            $db = Database::getInstance();
            $studentInfo = $db->fetch(
                "SELECT * FROM students WHERE user_id = ?",
                [$id]
            );
        }

        
        
        include VIEWS_PATH . '/user/view.php';
    }
    
    // Créer un utilisateur
    public function create() {
        // Seuls les administrateurs peuvent créer des pilotes et d'autres administrateurs
        if (!Auth::isAdmin() && $_POST['role'] !== 'student') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour créer ce type d'utilisateur.";
            header('Location: ' . BASE_URL . '?page=users');
            exit;
        }
        
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
                    'role' => $role
                ];
                
                $userId = $this->userModel->create($userData);
                
                // Si c'est un étudiant, créer son profil étudiant
                if ($role === 'student') {
                    $db = Database::getInstance();
                    $db->insert('students', [
                        'user_id' => $userId,
                        'promotion' => $_POST['promotion'] ?? null
                    ]);
                }
                
                $_SESSION['success'] = "L'utilisateur a été créé avec succès.";
                header('Location: ' . BASE_URL . '?page=users&action=view&id=' . $userId);
                exit;
            }
        }
        
        include VIEWS_PATH . '/user/create.php';
    }
    
    // Modifier un utilisateur
    public function edit($id) {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Seuls les administrateurs peuvent modifier des pilotes et d'autres administrateurs
        if (!Auth::isAdmin() && $user['role'] !== 'student') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour modifier ce type d'utilisateur.";
            header('Location: ' . BASE_URL . '?page=users');
            exit;
        }
        
        // Récupérer les informations de l'étudiant si applicable
        $studentInfo = null;
        if ($user['role'] === 'student') {
            $db = Database::getInstance();
            $studentInfo = $db->fetch(
                "SELECT * FROM students WHERE user_id = ?",
                [$id]
            );
        }
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $role = $_POST['role'] ?? $user['role'];
            
            // Valider les entrées
            if (empty($firstName) || empty($lastName) || empty($email)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } elseif (!empty($password) && $password !== $passwordConfirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } else {
                // Préparer les données à mettre à jour
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ];
                
                // Ajouter le mot de passe s'il est fourni
                if (!empty($password)) {
                    $userData['password'] = $password;
                }
                
                // Seuls les administrateurs peuvent changer le rôle
                if (Auth::isAdmin()) {
                    $userData['role'] = $role;
                }
                
                // Mettre à jour l'utilisateur
                $this->userModel->update($id, $userData);
                
                // Mettre à jour les informations de l'étudiant si applicable
                if ($user['role'] === 'student' || $role === 'student') {
                    $db = Database::getInstance();
                    
                    // Si le rôle change de ou vers étudiant
                    if ($user['role'] !== 'student' && $role === 'student') {
                        // Créer un nouvel enregistrement étudiant
                        $db->insert('students', [
                            'user_id' => $id,
                            'promotion' => $_POST['promotion'] ?? null
                        ]);
                    } elseif ($user['role'] === 'student' && $role !== 'student') {
                        // Supprimer l'enregistrement étudiant
                        $db->delete('students', 'user_id = ?', [$id]);
                    } elseif ($user['role'] === 'student' && $role === 'student') {
                        // Mettre à jour les informations de l'étudiant
                        $db->update('students', [
                            'promotion' => $_POST['promotion'] ?? null
                        ], 'user_id = ?', [$id]);
                    }
                }
                
                $_SESSION['success'] = "L'utilisateur a été mis à jour avec succès.";
                header('Location: ' . BASE_URL . '?page=users&action=view&id=' . $id);
                exit;
            }
        }
        
        include VIEWS_PATH . '/user/edit.php';
    }
    
    // Supprimer un utilisateur
    public function delete($id) {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        

        // Seuls les administrateurs peuvent supprimer des utilisateurs
        if (!Auth::isAdmin()) {
            $_SESSION['error'] = "Vous n'avez pas les droits pour supprimer un utilisateur.";
            header('Location: ' . BASE_URL . '?page=users');
            exit;
        }
        
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Empêcher la suppression de son propre compte
        if ($id == Auth::getUserId()) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            header('Location: ' . BASE_URL . '?page=users');
            exit;
        }
        
        // Confirmation de suppression
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $this->userModel->delete($id);
            $_SESSION['success'] = "L'utilisateur a été supprimé avec succès.";
            header('Location: ' . BASE_URL . '?page=users');
            exit;
        }
        
        include VIEWS_PATH . '/user/delete.php';
    }
    
    // Gestion du profil utilisateur
    public function profile() {
        $id = Auth::getUserId();
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Récupérer les informations de l'étudiant si applicable
        $studentInfo = null;
        if ($user['role'] === 'student') {
            $db = Database::getInstance();
            $studentInfo = $db->fetch(
                "SELECT * FROM students WHERE user_id = ?",
                [$id]
            );
        }
        
        $error = '';
        
        // Traiter le formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Valider les entrées
            if (empty($firstName) || empty($lastName) || empty($email)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } elseif (!empty($newPassword) && $newPassword !== $passwordConfirm) {
                $error = 'Les nouveaux mots de passe ne correspondent pas.';
            } elseif (!empty($newPassword) && empty($currentPassword)) {
                $error = 'Veuillez saisir votre mot de passe actuel pour le modifier.';
            } elseif (!empty($currentPassword) && !password_verify($currentPassword, $user['password'])) {
                $error = 'Le mot de passe actuel est incorrect.';
            } else {
                // Préparer les données à mettre à jour
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ];
                
                // Ajouter le nouveau mot de passe s'il est fourni
                if (!empty($newPassword)) {
                    $userData['password'] = $newPassword;
                }
                
                // Mettre à jour l'utilisateur
                $this->userModel->update($id, $userData);
                
                // Mettre à jour les informations de l'étudiant si applicable
                if ($user['role'] === 'student') {
                    $db = Database::getInstance();
                    $db->update('students', [
                        'promotion' => $_POST['promotion'] ?? null
                    ], 'user_id = ?', [$id]);
                }
                
                // Mettre à jour le nom d'utilisateur dans la session
                $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                
                $_SESSION['success'] = "Votre profil a été mis à jour avec succès.";
                header('Location: ' . BASE_URL . '?page=users&action=profile');
                exit;
            }
        }
        
        include VIEWS_PATH . '/user/profile.php';
    }
}