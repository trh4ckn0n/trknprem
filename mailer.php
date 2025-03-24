<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Charger .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Configs SMTP
$smtpConfigs = [
    'eant' => [
        'MAILHOST' => 'mail.eant.tech',
        'MAILPORT' => 587,
        'MAILUSER' => 'soporte@eant.tech',
        'MAILPASS' => getenv('MAILPASS_EANT'),
        'MAILFROM' => 'soporte@eant.tech',
        'FROMNAME' => 'SoporteEANT'
    ],
    'mailtrap' => [
        'MAILHOST' => 'smtp.mailtrap.io',
        'MAILPORT' => 2525,
        'MAILUSER' => '92ade88cfc26b9',
        'MAILPASS' => getenv('MAILPASS_MAILTRAP'),
        'MAILFROM' => 'designerescapade@gmail.com',
        'FROMNAME' => 'Escapade Zanzibar'
    ],
    'justhost' => [
        'MAILHOST' => 'just181.justhost.com',
        'MAILPORT' => 465,
        'MAILUSER' => 'no-reply@primucapital.com',
        'MAILPASS' => getenv('MAILPASS_JUSTHOST'),
        'MAILFROM' => 'no-reply@primucapital.com',
        'FROMNAME' => 'PRIMUCAPITAL'
    ]
];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $smtpChoice = $_POST['smtp'] ?? null;
    $recipient = $_POST['to'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $message = $_POST['message'] ?? null;

    // Vérifier si le SMTP est valide
    if (!isset($smtpConfigs[$smtpChoice])) {
        die("Erreur : SMTP non valide.");
    }

    $smtpConfig = $smtpConfigs[$smtpChoice];

    // Envoyer l'email
    sendEmail($smtpConfig, $recipient, $subject, $message);
}

// Fonction d'envoi d'email
function sendEmail($smtpConfig, $to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $smtpConfig['MAILHOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['MAILUSER'];
        $mail->Password = $smtpConfig['MAILPASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpConfig['MAILPORT'];

        $mail->setFrom($smtpConfig['MAILFROM'], $smtpConfig['FROMNAME']);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "✅ Message envoyé à $to via {$smtpConfig['MAILHOST']}";
    } catch (Exception $e) {
        echo "❌ Erreur : {$mail->ErrorInfo}";
    }
}
?>
