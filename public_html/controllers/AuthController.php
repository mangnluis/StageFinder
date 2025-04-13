<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger vers le tableau de bord
        if (Auth::isLoggedIn()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        $error = '';
        
        // Traiter le formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Valider les entrées
            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                // Tenter de connecter l'utilisateur
                if (Auth::login($email, $password)) {
                    header('Location: ' . BASE_URL . '?page=dashboard');
                    exit;
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }
        
        // Afficher le formulaire de connexion
        include VIEWS_PATH . '/auth/login.php';
    }
    
    public function logout() {
        Auth::logout();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public function register() {
        // Si l'utilisateur est déjà connecté, rediriger vers le tableau de bord
        if (Auth::isLoggedIn()) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
        
        $error = '';
        
        // Traiter le formulaire d'inscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Valider les entrées
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Ajout de cette ligne
                $error = 'Veuillez entrer une adresse email valide.';
            } elseif ($this->userModel->getByEmail($email)) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                // Créer l'utilisateur
                $userId = $this->userModel->create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'student' // Par défaut, les nouveaux utilisateurs sont des étudiants
                ]);
                
                // Ajouter l'étudiant
                $db = Database::getInstance();
                $db->insert('students', [
                    'user_id' => $userId,
                    'promotion' => $_POST['promotion'] ?? null
                ]);
                
                // Connecter l'utilisateur
                Auth::login($email, $password);
                
                header('Location: ' . BASE_URL . '?page=dashboard');
                exit;
            }
        }
        
        // Afficher le formulaire d'inscription
        include VIEWS_PATH . '/auth/register.php';
    }
}