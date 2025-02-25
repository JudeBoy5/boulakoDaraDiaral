<?php
session_start();

require_once 'conBD.php';

//  * Fonction pour trouver un utilisateur par login et mot de passe (avec hashage).
function findUserByLoginAndPass($login, $mdp, $role){
    global $connexion;
    
    // Préparer la requête pour éviter les injections SQL
    $sql = "SELECT * FROM users WHERE login = :login AND role = :role";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe correspond
    if ($user && password_verify($mdp, $user['mdp'])) {
        return $user;
    }

    return false;
}

//  * Fonction pour l'inscription d'un utilisateur (avec hashage du mot de passe).
function inscription($nom, $prenom, $tel, $email, $adresse, $role, $login, $password) {
    global $connexion;

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Utilisation de requêtes préparées pour sécuriser l'insertion
    $sql = "INSERT INTO users (nom, prenom, tel, email, adresse, role, login, mdp) 
            VALUES (:nom, :prenom, :tel, :email, :adresse, :role, :login, :mdp)";
    
    $stmt = $connexion->prepare($sql);
    
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':mdp', $hashedPassword);

    // Exécuter la requête et retourner le résultat
    return $stmt->execute();
}

?>
