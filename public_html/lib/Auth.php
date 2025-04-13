<?php
class Auth {
    public static function login($email, $password) {
        $db = Database::getInstance();
        $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            return true;
        }
        
        return false;
    }
    
    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_name']);
        session_destroy();
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            flash('danger', 'Vous devez être connecté pour accéder à cette page.');
            redirect('/?page=auth&action=login');
            exit;
        }
    }
    
    public static function getUserId() {
        return self::isLoggedIn() ? $_SESSION['user_id'] : null;
    }
    
    public static function getUserRole() {
        return self::isLoggedIn() ? $_SESSION['user_role'] : null;
    }
    
    public static function isAdmin() {
        return self::getUserRole() === 'admin';
    }
    
    public static function isPilot() {
        return self::getUserRole() === 'pilot';
    }
    
    public static function isStudent() {
        return self::getUserRole() === 'student';
    }
    
    public static function requireAdmin() {
        self::requireLogin();
        
        if (!self::isAdmin()) {
            flash('danger', 'Vous n\'avez pas les droits pour accéder à cette page.');
            redirect('/?page=dashboard');
            exit;
        }
    }
    
    public static function requirePilotOrAdmin() {
        self::requireLogin();
        
        if (!self::isAdmin() && !self::isPilot()) {
            flash('danger', 'Vous n\'avez pas les droits pour accéder à cette page.');
            redirect('/?page=dashboard');
            exit;
        }
    }


    public static function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
}
