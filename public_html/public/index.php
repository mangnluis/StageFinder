<?php
// Point d'entrée principal de l'application

// Charger la configuration
require_once '../config/config.php';

// Fonction d'autoload simple
spl_autoload_register(function($class) {
    // Vérifier le type de classe
    if (strpos($class, 'Controller') !== false) {
        $file = ROOT_PATH . '/controllers/' . $class . '.php';
    } elseif (strpos($class, 'Model') !== false) {
        $file = ROOT_PATH . '/models/' . $class . '.php';
    } elseif (strpos($class, 'Service') !== false) {
        $file = ROOT_PATH . '/services/' . $class . '.php';
    } else {
        $file = ROOT_PATH . '/lib/' . $class . '.php';
    }
    
    if (file_exists($file)) {
        require_once $file;
    }

    
});

// Vérifier si la base de données est installée
try {
    $db = Database::getInstance();
    $userCount = $db->count('users');
} catch (PDOException $e) {
    // Rediriger vers l'installation si la base de données n'est pas configurée
    header('Location: ' . BASE_URL . '/install.php');
    exit;
}

// Routage simple
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Mapping des routes
switch ($page) {
    case 'home':
        $controller = new HomeController();

        if ($action === 'about') {
            $controller->about();
        } elseif ($action === 'contact') {
            $controller->contact();
        } else {
            $controller->index();
        }
        break;
        
    case 'auth':
        $controller = new AuthController();
        
        if ($action === 'login') {
            $controller->login();
        } elseif ($action === 'logout') {
            $controller->logout();
        } elseif ($action === 'register') {
            $controller->register();
        } else {
            $controller->login();
        }
        break;
        
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'companies':
        $controller = new CompanyController();
        
        if ($action === 'view' && $id) {
            $controller->view($id);
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } elseif ($action === 'rate' && $id) {
            $controller->rate($id);
        } elseif ($action === 'delete-rating' && $id) {
            $controller->deleteRating($id);
        } else {
            $controller->index();
        }
        break;
        
    case 'internships':
        $controller = new InternshipController();
        
        if ($action === 'view' && $id) {
            $controller->view($id);
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } elseif ($action === 'apply' && $id) {
            $controller->apply($id);
        } elseif ($action === 'add-to-wishlist' && $id) {
            $controller->addToWishlist($id);
        } elseif ($action === 'remove-from-wishlist' && $id) {
            $controller->removeFromWishlist($id);
        } else {
            $controller->index();
        }
        break;
        
    case 'profile':
        $controller = new ProfileController();
        
        if ($action === 'change-password') {
            $controller->changePassword();
        } else {
            $controller->index();
        }
        break;
        
    case 'notifications':
        $controller = new NotificationController();
        
        if ($action === 'markAsRead' && $id) {
            $controller->markAsRead($id);
        } elseif ($action === 'markAllAsRead') {
            $controller->markAllAsRead();
        } else {
            $controller->index();
        }
        break;
        
    case 'wishlist':
        $controller = new WishlistController();
        
        if ($action === 'add' && $id) {
            $controller->add($id);
        } elseif ($action === 'remove' && $id) {
            $controller->remove($id);
        } else {
            $controller->index();
        }
        break;
        
    case 'applications':
        $controller = new ApplicationController();
        
        if ($action === 'view' && $id) {
            $controller->view($id);
        } elseif ($action === 'update-status' && $id) {
            $controller->updateStatus($id);
        } elseif ($action === 'my') {
            $controller->myApplications();
        } else {
            $controller->index();
        }
        break;
        
    case 'admin':
        $controller = new AdminController();
        
        if ($action === 'users') {
            $controller->users();
        } elseif ($action === 'create-user') {
            $controller->createUser();
        } elseif ($action === 'edit-user' && $id) {
            $controller->editUser($id);
        } elseif ($action === 'delete-user' && $id) {
            $controller->deleteUser($id);
        } elseif ($action === 'stats') {
            $controller->stats();
        } else {
            $controller->index();
        }
        break;
       //COMME ADMIN EST PAS VRAIMENT FAIT ON PASSE PAR USER jcrois ca doit degager
    case 'users':
        $controller = new UserController();
        
        if ($action === 'view' && $id) {
            $controller->view($id);
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } elseif ($action == 'create') {
            $controller->create();
        }else {
            $controller->index();
        }
        break;
    default:
        // Page non trouvée
        header("HTTP/1.0 404 Not Found");
        view('errors/404', [
            'pageTitle' => 'Page non trouvée'
        ]);
        break;
}