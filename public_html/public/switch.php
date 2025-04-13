<?php

$redirect = '/dev_site';
$message = "Version de développement activée pour 24 heures";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement d'environnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .message {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="message"><?php echo $message; ?></div>
    
    <div id="countdown">Redirection dans 3 secondes...</div>
    
    <p><a class="btn" href="<?php echo $redirect; ?>">Continuer maintenant</a></p>
    
    <script>
        setTimeout(function() {
            window.location.href = '<?php echo $redirect; ?>';
        }, 30000);
    </script>
</body>
</html>