<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "boulakodaradiaral1";


try {
  $connexion = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
  // set the PDO error mode to exception
  $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";

} catch(PDOException $e) {
  echo "Oups ! Il y a eu un imprevu: " . $e->getMessage();
//   include 'pages/maintenance.php';
  die();
}
?>