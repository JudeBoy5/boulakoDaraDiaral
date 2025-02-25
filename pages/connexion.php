<?php
    session_start();
    require_once '../bd/conBD.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/styleCon.css">
</head>
<body>

<div class="login-container">
    <h1>Connexion</h1>
    <form action="http://localhost/Php2/boulakoDaraDiaral/index.php" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <br>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <br><br>
        <input type="submit" name="connexion"><br><br>
        <a href="http://localhost/Php2/boulakoDaraDiaral/pages/inscription.php"> S'inscrire</a>
    </form>
</div>

</body>
</html>





