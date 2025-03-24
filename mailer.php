<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php'; // Chargez PHPMailer via Composer, ou incluez le fichier PHPMailer.php si vous l'avez téléchargé manuellement.

$dotenv = Dotenv::createImmutable(__DIR__); // Charge le fichier .env
$dotenv->load(); // Charge les variables depuis le fichier .env

function sendEmail($smtpConfig, $to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = $smtpConfig['MAILHOST']; // Serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['MAILUSER']; // Utilisateur SMTP
        $mail->Password = $smtpConfig['MAILPASS']; // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpConfig['MAILPORT']; // Port SMTP (587, 2525, 465)

        // Expéditeur
        $mail->setFrom($smtpConfig['MAILFROM'], $smtpConfig['FROMNAME']); // Adresse et nom de l'expéditeur
        $mail->addAddress($to); // Destinataire

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Envoi de l'email
        $mail->send();
        echo 'Message envoyé avec succès à ' . $to;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}";
    }
}

// Configuration des serveurs SMTP avec récupération des mots de passe depuis le fichier .env
$smtpConfigs = [
    'eant' => [
        'MAILHOST' => 'mail.eant.tech',
        'MAILPORT' => 587,
        'MAILUSER' => 'soporte@eant.tech',
        'MAILPASS' => $_ENV['SMTP_EANT_PASS'], // Utilisation du mot de passe dans .env
        'MAILFROM' => 'soporte@eant.tech',
        'FROMNAME' => 'SoporteEANT'
    ],
    'mailtrap' => [
        'MAILHOST' => 'smtp.mailtrap.io',
        'MAILPORT' => 2525,
        'MAILUSER' => '92ade88cfc26b9',
        'MAILPASS' => $_ENV['SMTP_MAILTRAP_PASS'], // Utilisation du mot de passe dans .env
        'MAILFROM' => 'designerescapade@gmail.com',
        'FROMNAME' => 'Escapade Zanzibar'
    ],
    'justhost' => [
        'MAILHOST' => 'just181.justhost.com',
        'MAILPORT' => 465,
        'MAILUSER' => 'no-reply@primucapital.com',
        'MAILPASS' => $_ENV['SMTP_JUSTHOST_PASS'], // Utilisation du mot de passe dans .env
        'MAILFROM' => 'no-reply@primucapital.com',
        'FROMNAME' => 'PRIMUCAPITAL'
    ]
];

// Utilisation de la configuration SMTP choisie
$smtpConfig = $smtpConfigs['eant']; // Vous pouvez choisir eant, mailtrap, ou justhost ici

// Exemple d'envoi d'email
$to = 'recipient@example.com'; // L'email du destinataire
$subject = 'Test Email';
$message = 'Ceci est un test d\'envoi d\'email via SMTP.';

sendEmail($smtpConfig, $to, $subject, $message);
?>
