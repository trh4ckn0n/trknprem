<?php
// Envoi du lien premium par email
$to = $_POST['email']; // L'email de l'utilisateur
$subject = "Votre accès Premium à Dallog";
$message = "Merci pour votre paiement ! Voici le lien pour accéder à l'outil premium :\n\n";
$message .= "https://votre-site.com/outil-premium\n\n";
$message .= "Profitez pleinement de l'outil !";

$headers = "From: no-reply@example.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Un email vous a été envoyé avec le lien vers l'outil premium.";
} else {
    echo "Une erreur s'est produite lors de l'envoi de l'email.";
}
?>
