<?php
class NotificationService {
    private $notificationModel;
    private $userModel;
    
    public function __construct() {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Envoie une notification à un utilisateur
     * @param int $userId ID de l'utilisateur
     * @param string $type Type de notification
     * @param string $message Message de la notification
     * @param int|null $relatedId ID de l'élément concerné
     * @param bool $sendEmail Envoyer également un email
     * @return int ID de la notification créée
     */
    public function notify($userId, $type, $message, $relatedId = null, $sendEmail = true) {
        // Créer la notification en base de données
        $notificationId = null;
        // Vérifier que l'utilisateur existe
        if (!$this->userModel->exists($userId)) {
            return false;
        }
        // Vérifier que le type de notification est valide
        $validTypes = ['application_status', 'new_application', 'message', 'system'];
        if (!in_array($type, $validTypes)) {
            return false;
        }
        // Créer la notification
        $notificationId = $this->notificationModel->create($userId, $type, $message, $relatedId);
        
        // Envoyer un email si demandé
        if ($sendEmail) {
            $this->sendEmail($userId, $type, $message);
        }
        
        return $notificationId;
    }
    
    /**
     * Envoie un email de notification
     * @param int $userId ID de l'utilisateur
     * @param string $type Type de notification
     * @param string $message Message de la notification
     * @return bool Succès de l'envoi
     */
    private function sendEmail($userId, $type, $message) {
        $user = $this->userModel->getById($userId);
        
        if (!$user || empty($user['email'])) {
            return false;
        }
        
        $subject = 'Notification - ErgonoWeb';
        
        // Personnaliser le sujet selon le type de notification
        switch ($type) {
            case 'application_status':
                $subject = 'Mise à jour de votre candidature - ErgonoWeb';
                break;
            case 'new_application':
                $subject = 'Nouvelle candidature - ErgonoWeb';
                break;
            case 'message':
                $subject = 'Nouveau message - ErgonoWeb';
                break;
            case 'system':
                $subject = 'Information importante - ErgonoWeb';
                break;
        }
        
        // En-têtes de l'email
        $headers = [
            'From' => 'contact@ergonoweb.com',
            'Reply-To' => 'contact@ergonoweb.com',
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=UTF-8'
        ];
        
        // Convertir les en-têtes en chaîne
        $headerString = '';
        foreach ($headers as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }
        
        // Corps de l'email en HTML
        $emailBody = $this->getEmailTemplate($user, $message, $type);
        
        // Envoyer l'email
        return mail($user['email'], $subject, $emailBody, $headerString);
    }
    
    /**
     * Génère le template HTML pour l'email
     * @param array $user Informations sur l'utilisateur
     * @param string $message Message de la notification
     * @param string $type Type de notification
     * @return string Corps de l'email en HTML
     */
    private function getEmailTemplate($user, $message, $type) {
        $firstName = htmlspecialchars($user['first_name']);
        $message = htmlspecialchars($message);
        $loginUrl = BASE_URL . '/?page=auth&action=login';
        
        // Déterminer la couleur et l'icône selon le type
        $color = '#3498db'; // Bleu par défaut
        $icon = 'bell';
        
        switch ($type) {
            case 'application_status':
                $color = '#2ecc71'; // Vert
                $icon = 'file-alt';
                break;
            case 'new_application':
                $color = '#f39c12'; // Orange
                $icon = 'user-plus';
                break;
            case 'message':
                $color = '#9b59b6'; // Violet
                $icon = 'envelope';
                break;
            case 'system':
                $color = '#e74c3c'; // Rouge
                $icon = 'cog';
                break;
        }
        
        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Notification - ErgonoWeb</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    background-color: '.$color.';
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                    background-color: #f9f9f9;
                }
                .footer {
                    font-size: 12px;
                    text-align: center;
                    color: #777;
                    padding: 20px;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: '.$color.';
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ErgonoWeb</h1>
                </div>
                <div class="content">
                    <p>Bonjour '.$firstName.',</p>
                    <p>'.$message.'</p>
                    <p>Connectez-vous à votre compte pour plus de détails :</p>
                    <p style="text-align: center;">
                        <a href="'.$loginUrl.'" class="button">Se connecter</a>
                    </p>
                </div>
                <div class="footer">
                    <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                    <p>&copy; '.date('Y').' ErgonoWeb - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Notifie un étudiant du changement de statut de sa candidature
     * @param int $studentId ID de l'étudiant
     * @param int $applicationId ID de la candidature
     * @param string $status Nouveau statut
     * @return int ID de la notification créée
     */
    public function notifyApplicationStatus($studentId, $applicationId, $status) {
        // Récupérer les informations sur la candidature
        $db = Database::getInstance();
        $application = $db->fetch(
            "SELECT a.*, i.title as internship_title, c.name as company_name
             FROM applications a 
             JOIN internships i ON a.internship_id = i.id 
             JOIN companies c ON i.company_id = c.id 
             WHERE a.id = ?",
            [$applicationId]
        );
        
        if (!$application) {
            return false;
        }
        
        // Créer le message en fonction du statut
        $statusText = '';
        switch ($status) {
            case 'accepted':
                $statusText = 'acceptée';
                break;
            case 'rejected':
                $statusText = 'refusée';
                break;
            default:
                $statusText = 'mise à jour';
        }
        
        $message = "Votre candidature pour l'offre \"{$application['internship_title']}\" chez {$application['company_name']} a été {$statusText}.";
        
        // Envoyer la notification avec email
        return $this->notify($studentId, 'application_status', $message, $applicationId, true);
    }
    
    /**
     * Notifie un pilote d'une nouvelle candidature
     * @param int $pilotId ID du pilote
     * @param int $studentId ID de l'étudiant
     * @param int $applicationId ID de la candidature
     * @return int ID de la notification créée
     */
    public function notifyNewApplication($pilotId, $studentId, $applicationId) {
        // Récupérer les informations sur l'étudiant et la candidature
        $db = Database::getInstance();
        $student = $this->userModel->getById($studentId);
        
        $application = $db->fetch(
            "SELECT a.*, i.title as internship_title, c.name as company_name
             FROM applications a 
             JOIN internships i ON a.internship_id = i.id 
             JOIN companies c ON i.company_id = c.id 
             WHERE a.id = ?",
            [$applicationId]
        );
        
        if (!$student || !$application) {
            return false;
        }
        
        $message = "L'étudiant {$student['first_name']} {$student['last_name']} a postulé à l'offre \"{$application['internship_title']}\" chez {$application['company_name']}.";
        
        // Envoyer la notification avec email
        return $this->notify($pilotId, 'new_application', $message, $applicationId, true);
    }
}