<?php
require 'vendor/autoload.php'; // Charge Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Charge .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Fonction d'affichage pour interactivité
function prompt($message) {
    echo $message . " : ";
    return trim(fgets(STDIN));
}

// Configs SMTP
$smtpConfigs = [
    'eant' => [
        'MAILHOST' => 'mail.eant.tech',
        'MAILPORT' => 587,
        'MAILUSER' => 'soporte@eant.tech',
        'MAILPASS' => 'MAILPASS_EANT',
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

// Sélection du SMTP
echo "\nSélectionnez un SMTP : \n";
foreach (array_keys($smtpConfigs) as $key) {
    echo "- $key\n";
}
$smtpChoice = prompt("Entrez le nom du SMTP (ex: eant, mailtrap, justhost)");

// Vérification du choix
if (!isset($smtpConfigs[$smtpChoice])) {
    die("Erreur : SMTP non valide.\n");
}

$smtpConfig = $smtpConfigs[$smtpChoice];

// Demander les infos
$recipient = prompt("Entrez l'adresse e-mail du destinataire");
$subject = prompt("Entrez le sujet du mail");
$message = prompt("Entrez le message");

// Fonction d'envoi
function sendEmail($smtpConfig, $to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $smtpConfig['MAILHOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['MAILUSER'];
        $mail->Password = getenv($smtpConfig['MAILPASS']);
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpConfig['MAILPORT'];

        $mail->setFrom($smtpConfig['MAILFROM'], $smtpConfig['FROMNAME']);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "✅ Message envoyé à $to via {$smtpConfig['MAILHOST']}\n";
    } catch (Exception $e) {
        echo "❌ Erreur : {$mail->ErrorInfo}\n";
    }
}

// Envoi du mail
sendEmail($smtpConfig, $recipient, $subject, $message);
?>
