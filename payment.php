<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les informations envoyées par le formulaire
    $email = $_POST['email'];
    $screenshot = $_FILES['screenshot'];

    // Vérification du fichier de capture d'écran
    $allowedExts = array("jpg", "jpeg", "png", "gif");
    $extension = pathinfo($screenshot['name'], PATHINFO_EXTENSION);
    if (!in_array($extension, $allowedExts)) {
        echo "Veuillez télécharger une image valide (JPG, PNG, GIF).";
    } else {
        // Déplacer l'image téléchargée dans un dossier
        $uploadDir = "uploads/";
        $uploadFile = $uploadDir . basename($screenshot['name']);
        if (move_uploaded_file($screenshot['tmp_name'], $uploadFile)) {
            // Enregistrer l'email et la capture d'écran
            // Envoi d'un email à l'administrateur pour vérifier le paiement
            $to = "votre-email@example.com";
            $subject = "Nouvelle demande de paiement";
            $message = "Un utilisateur avec l'email $email a effectué un paiement. Vérifiez la capture d'écran ici : $uploadFile.";
            $headers = "From: no-reply@example.com";

            // Si l'email est envoyé, afficher un message de confirmation
            if (mail($to, $subject, $message, $headers)) {
                echo "Merci pour votre paiement ! Vous recevrez un lien pour l'outil premium après validation.";
            } else {
                echo "Une erreur s'est produite lors de l'envoi de l'email.";
            }
        } else {
            echo "Erreur lors de l'upload de l'image. Essayez à nouveau.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement pour l'outil Premium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #f1f1f1;
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        input[type="file"] {
            color: #fff;
            background-color: #333;
            padding: 10px;
            margin-top: 20px;
        }
        input[type="email"], button {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Paiement Premium</h1>
        <p>Pour accéder à l'outil premium, effectuez un virement de 4€ et téléchargez une capture d'écran de votre virement.</p>
        <h3>Mon RIB : <a href="rib.pdf" target="_blank">Télécharger le RIB</a></h3>
        <form action="payment.php" method="POST" enctype="multipart/form-data">
            <label for="email">Votre adresse email :</label><br>
            <input type="email" name="email" required><br>
            <label for="screenshot">Capture d'écran du virement :</label><br>
            <input type="file" name="screenshot" required><br><br>
            <button type="submit" class="button">Envoyer la capture d'écran</button>
        </form>
    </div>

</body>
</html>
