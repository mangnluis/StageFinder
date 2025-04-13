<?php
class ProfileController {
    private $userModel;
    
    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        Auth::requireLogin();
        
        $this->userModel = new UserModel();
    }
    
    // Afficher et gérer le profil de l'utilisateur
    public function index() {
        $id = Auth::getUserId();
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
        $success = '';
        
        // Traiter le formulaire de modification du profil
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
                // Vérifier si l'email est déjà utilisé par un autre utilisateur
                $existingUser = $this->userModel->getByEmail($email);
                if ($existingUser && $existingUser['id'] != $id) {
                    $error = 'Cet email est déjà utilisé par un autre compte.';
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
                        $this->userModel->updateStudentInfo($id, [
                            'promotion' => $_POST['promotion'] ?? null
                        ]);
                    }
                    
                    // Mettre à jour le nom d'utilisateur dans la session
                    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                    
                    $success = 'Votre profil a été mis à jour avec succès.';
                    
                    // Rafraîchir les informations de l'utilisateur
                    $user = $this->userModel->getById($id);
                    if ($user['role'] === 'student') {
                        $studentInfo = $this->userModel->getStudentInfo($id);
                    }
                }
            }
        }
        
        view('profile/index', [
            'pageTitle' => 'Mon profil',
            'user' => $user,
            'studentInfo' => $studentInfo,
            'error' => $error,
            'success' => $success
        ]);
    }
    
    // Afficher la page de changement de mot de passe
    public function changePassword() {
        $error = '';
        $success = '';
        
        // Traiter le formulaire de changement de mot de passe
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Valider les entrées
            if (empty($currentPassword) || empty($newPassword) || empty($passwordConfirm)) {
                $error = 'Veuillez remplir tous les champs.';
            } elseif ($newPassword !== $passwordConfirm) {
                $error = 'Les nouveaux mots de passe ne correspondent pas.';
            } else {
                // Vérifier le mot de passe actuel
                $id = Auth::getUserId();
                $user = $this->userModel->getById($id);
                
                if (!password_verify($currentPassword, $user['password'])) {
                    $error = 'Le mot de passe actuel est incorrect.';
                } else {
                    // Mettre à jour le mot de passe
                    $this->userModel->update($id, [
                        'password' => $newPassword
                    ]);
                    
                    $success = 'Votre mot de passe a été modifié avec succès.';
                }
            }
        }
        
        view('profile/change_password', [
            'pageTitle' => 'Changer de mot de passe',
            'error' => $error,
            'success' => $success
        ]);
    }
}