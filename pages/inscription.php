<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Validator</title>
	<link rel="stylesheet" href="../assets/css/styleIns.css">
</head>
<body>
	<div class="container">
		<form id="form" class="form" action="http://localhost/Php2/boulakoDaraDiaral/index.php" method="POST">
			<h2>Inscription</h2>
			<div class="form-control">
				<label for="username">  Prenom </label>
				<input type="text" id="username" placeholder="Enter username" name="prenomclient" required>
				<small>Validation Error !</small>
			</div>
            <div class="form-control">
				<label for="username">  Nom </label>
				<input type="text" id="username" placeholder="Enter name" name="nomclient" required>
				<small>Validation Error !</small>
			</div>
            <div class="form-control">
				<label for="username">  Adresse </label>
				<input type="text" id="username" placeholder="Enter your adress" name="adresseclient" required>
				<small>Validation Error !</small>
			</div>
            <div class="form-control">
				<label for="username">  Tel </label>
				<input type="text" id="username" placeholder="Enter your phone number" name="telclient"required>
				<small>Validation Error !</small>
			</div>
            <div class="form-control">
				<label for="username">  Email </label>
				<input type="email" id="username" placeholder="Enter email" name="emailclient"required>
				<small>Validation Error !</small>
			</div>
         
               <div class="form-control">
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option value="Client">Client</option>
                        <option value="Client">admin</option>
                        <small>Validation Error !</small>
                    </select>
               </div>
                
			<!-- </div> -->
            <div class="form-control">
				<label for="username">  Login </label>
				<input type="text" id="username" placeholder="Enter login" name="loginClient">
				<small>Validation Error !</small>
			</div>
			<div class="form-control">
				<label for="password">Mot de Passe</label>
				<input type="password" id="password" placeholder="Enter password" name="mdpClient">
				<small>Validation Error !</small>
			</div>
			<button type="submit" name="inscrire">S'Inscrire</button><br><br>
			<a href="http://localhost/Php2/boulakoDaraDiaral/pages/connexion.php"> Se connecter</a>
		</form>

	</div>

</body>
</html> 
