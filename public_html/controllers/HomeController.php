<?php
class HomeController {
    public function index() {
        // Récupérer quelques statistiques pour la page d'accueil
        $db = Database::getInstance();
        
        $stats = [
            'companies' => $db->count('companies'),
            'internships' => $db->count('internships'),
            'students' => $db->count('users', 'role = ?', ['student'])
        ];
        
        // Récupérer les dernières offres
        $internshipModel = new InternshipModel();
        $latestInternships = $internshipModel->getAll(1, 6);
        
        // Récupérer les entreprises les mieux notées
        $companyModel = new CompanyModel();
        $topCompanies = $companyModel->getTopRated(3);
        
        // Afficher la page d'accueil
        view('home/index', [
            'stats' => $stats,
            'latestInternships' => $latestInternships,
            'topCompanies' => $topCompanies,
            'pageTitle' => 'Accueil'
        ]);
    }
    
    public function about() {
        view('home/about', [
            'pageTitle' => 'À propos'
        ]);
    }
    
    public function contact() {
        $message = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement du formulaire de contact
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $content = $_POST['message'] ?? '';
            
            if (empty($name) || empty($email) || empty($subject) || empty($content)) {
                $error = 'Tous les champs sont obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'L\'adresse email n\'est pas valide.';
            } else {
                // Envoyer l'email (à implémenter avec PHPMailer ou autre)
                $message = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
            }
        }
        
        view('home/contact', [
            'pageTitle' => 'Contact',
            'message' => $message,
            'error' => $error
        ]);
    }
}