<?php
// Téléchargez ces fichiers PHPMailer à partir de GitHub
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MailService {
    /**
     * Envoie un email via SMTP
     * @param string $to Adresse du destinataire
     * @param string $subject Sujet de l'email
     * @param string $body Corps de l'email (HTML)
     * @param string $fromName Nom de l'expéditeur
     * @return bool Succès de l'envoi
     */
    public static function send($to, $subject, $body, $fromName = 'ErgonoWeb') {
        $mail = new PHPMailer(true);
        
        try {
            // Configuration du serveur
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contact@ergonoweb.com'; // Votre adresse email SMTP
            $mail->Password   = 'votre-mot-de-passe-smtp'; // Votre mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // Expéditeur et destinataire
            $mail->setFrom('contact@ergonoweb.com', $fromName);
            $mail->addAddress($to);
            
            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            // Encodage
            $mail->CharSet = 'UTF-8';
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false;
        }
    }
}