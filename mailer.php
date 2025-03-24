<?php
require 'vendor/autoload.php'; // Charge Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Charge .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // Utiliser safeLoad() pour éviter les erreurs si le fichier est absent

function sendEmail($smtpConfig, $to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = $smtpConfig['MAILHOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['MAILUSER'];
        $mail->Password = getenv($smtpConfig['MAILPASS']); // Récupère le mot de passe depuis .env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpConfig['MAILPORT'];

        // Expéditeur
        $mail->setFrom($smtpConfig['MAILFROM'], $smtpConfig['FROMNAME']);
        $mail->addAddress($to);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Envoi
        $mail->send();
        echo "Message envoyé à $to";
    } catch (Exception $e) {
        echo "Erreur: {$mail->ErrorInfo}";
    }
}

// Configs SMTP
$smtpConfigs = [
    'eant' => [
        'MAILHOST' => 'mail.eant.tech',
        'MAILPORT' => 587,
        'MAILUSER' => 'soporte@eant.tech',
        'MAILPASS' => 'MAILPASS_EANT', // Clé pour .env
        'MAILFROM' => 'soporte@eant.tech',
        'FROMNAME' => 'SoporteEANT'
    ],
    'mailtrap' => [
        'MAILHOST' => 'smtp.mailtrap.io',
        'MAILPORT' => 2525,
        'MAILUSER' => '92ade88cfc26b9',
        'MAILPASS' => 'MAILPASS_MAILTRAP',
        'MAILFROM' => 'designerescapade@gmail.com',
        'FROMNAME' => 'Escapade Zanzibar'
    ],
    'justhost' => [
        'MAILHOST' => 'just181.justhost.com',
        'MAILPORT' => 465,
        'MAILUSER' => 'no-reply@primucapital.com',
        'MAILPASS' => 'MAILPASS_JUSTHOST',
        'MAILFROM' => 'no-reply@primucapital.com',
        'FROMNAME' => 'PRIMUCAPITAL'
    ]
];

// Test envoi
$smtpConfig = $smtpConfigs['eant']; 
sendEmail($smtpConfig, 'recipient@example.com', 'Test Email', 'Ceci est un test.');
?>
