
<?php
    session_start();
    require_once 'bd/conBd.php';
    require_once 'bd/authentification.php';

    // session_start();

    if(isset($_POST['inscrire'])){
        // Inscription d'un utilisateur
        $client = inscription($_POST['nomclient'], $_POST['prenomclient'], $_POST['telclient'], $_POST['emailclient'], $_POST['adresseclient'], $_POST['role'], $_POST['loginClient'], $_POST['mdpClient']);
        
        if ($client) {
            // Redirection après inscription réussie
            header("location:http://localhost/Php2/boulakoDaraDiaral/pages/connexion.php");
        } else {
            $error = "Erreur lors de l'inscription";
            die($error);
        }
    } 

    if (isset($_POST['connexion'])) {
        $login = $_POST['username'];
        $mdp = $_POST['password']; // Mot de passe non haché entré par l'utilisateur
    
        // Recherche de l'utilisateur dans la base de données
        $stmt = $connexion->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($mdp, $user['mdp'])) { // Vérification du mot de passe haché
            $_SESSION['user'] = [
                'idUser' => $user['idUser'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'role' => $user['role']
            ];

            // Redirection selon le rôle
            if ($user['role'] === "admin") {
                header("Location: http://localhost/Php2/boulakoDaraDiaral/pages/dashboard.php");
            } else {
                header("Location: http://localhost/Php2/boulakoDaraDiaral/pages/accueil.php");
            }
        } else {
            header("Location: http://localhost/Php2/boulakoDaraDiaral/pages/inscription.php");
        }
    }
?>