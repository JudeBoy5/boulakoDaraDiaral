<?php
session_start();
require_once '../bd/conBd.php';

// Fonction pour ajouter une chambre
function addChambre($nomChambre, $numTelChambre, $idCatF, $image) {
    global $connexion;
    $sql = "INSERT INTO chambre (nomChambre, numTelChambre, idCategorieF, image) 
            VALUES ('$nomChambre', '$numTelChambre', '$idCatF', '$image')";
    return $connexion->exec($sql);
}

// Fonction pour récupérer les catégories
function getCategorie() {
    $sql = "SELECT * FROM categorie";
    global $connexion; 
    return $connexion->query($sql)->fetchAll(); 
}

// Fonction pour récupérer les chambres avec les informations de catégorie
function getChambre() {
    $sql = "SELECT chambre.*, categorie.libelleCategorie, categorie.montantCategorie 
            FROM chambre 
            INNER JOIN categorie ON chambre.idCategorieF = categorie.idCategorie";
    global $connexion; 
    $exe = $connexion->query($sql); 
    return $exe->fetchAll(); 
}

// Fonction pour modifier une chambre
function updateChambre($id, $nomChambre, $etatChambre, $numTelChambre, $idCatF, $image = null) {
    global $connexion;
    $sql = "UPDATE chambre 
            SET nomChambre = '$nomChambre', 
                etatChambre = '$etatChambre', 
                numTelChambre = '$numTelChambre', 
                idCategorieF = '$idCatF',
                image = '$image'
                WHERE chambre.idChambre = $id ";
    return $connexion->exec($sql);
}

// Fonction pour supprimer une chambre
function deleteChambre($id) {
    global $connexion;
    $sql = "DELETE FROM chambre WHERE chambre.idChambre = $id";
    return $connexion->exec($sql);
}

// Récupérer la liste des chambres et des catégories
$listeChambre = getChambre();
$listeCategorie = getCategorie();

// Gestion de l'ajout d'une chambre
if (isset($_POST['ajouter'])) {
    extract($_POST);
    $target_dir = "imgs/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $result = addChambre($nomChambre, $numTelChambre, $idCat, $target_file);
            if ($result) {
                header("location: listerChambre.php");
                exit();
            }
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        echo "Le fichier n'est pas une image.";
    }
}

// Gestion de la modification d'une chambre
if (isset($_POST['modifier'])) {
    extract($_POST);
    $id = $_POST['id'];
    $target_file = $_POST['existing_image'] ?? null; // Conserver l'image existante par défaut

    // Vérifier si une nouvelle image a été téléchargée
    if ($_FILES["image"]["name"]) {
        $target_dir = "imgs/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                exit;
            }
        } else {
            echo "Le fichier n'est pas une image.";
            exit;
        }
    }

    // Mettre à jour la chambre dans la base de données
    $result = updateChambre($id, $nomChambre, $etatChambre, $numTelChambre, $idCat, $target_file);

    if ($result) {
        // Rediriger vers la même page après la modification
        header("Location: listerChambre.php");
        exit;
    } else {
        echo "Une erreur s'est produite lors de la mise à jour de la chambre.";
    }
}

// Gestion de la suppression d'une chambre
if (isset($_GET['idS'])) {
    $id = $_GET['idS'];
    deleteChambre($id);
    header("location: listerChambre.php");
}

// Récupérer les informations de la chambre à modifier
if (isset($_GET['idM'])) {
    $id = $_GET['idM'];
    $sql = "SELECT * FROM chambre WHERE idChambre = $id";
    $chambre = $connexion->query($sql)->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <title>boulakoDaraDiaral</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../assets/assets2/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../assets/assets2/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<style>
    .logo-letter {
        font-size: 2em;  /* Agrandir la lettre B */
        color: #007bff;   /* Bleu pour rester cohérent avec le thème */
        font-weight: 800; /* Gras pour accentuer la lettre */
    }

    /* Style pour l'ensemble du texte du logo */
    .logo-text h2 {
        font-family: 'Montserrat', sans-serif; /* Une police élégante */
        letter-spacing: 1px;                   /* Espacement des lettres pour un effet aéré */
    }

</style>
<body>
    <div class="container-xxl bg-white p-0">
       
  <!-- Header Start -->
    <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
            <div class="col-lg-4 d-none d-lg-flex justify-content-center py-3">
                <a href="dashboard.php" class="navbar-brand text-center logo-text">
                    <h2 class="m-0 text-primary text-uppercase" style="white-space: nowrap;">
                        <span class="logo-letter">B</span>oulakoDaraDiaral
                    </h2>
                </a>
            </div>

                <div class="col-lg-8">
                        <div class="row gx-0 bg-white d-none d-lg-flex py-2">
                        <div class="col-lg-7 px-4">
                            <div class="d-inline-flex align-items-center">
                                <i class="fa fa-envelope text-primary me-2"></i>
                                <p class="mb-0 me-4">boulakoDaraDiaral25@gmail.com</p>
                                <i class="fa fa-phone-alt text-primary me-2"></i>
                                <p class="mb-0">+221 33 835 16 06</p>
                            </div>
                        </div>
                        <div class="col-lg-5 text-end px-4">
                            <div class="d-inline-flex align-items-center">
                                <a class="me-3 text-dark" href="https://www.facebook.com/share/1DayGRzEg8/?mibextid=wwXIfr"><i class="fab fa-facebook-f"></i></a>
                                <a class="me-3 text-dark" href="https://github.com/JudeBoy5"><i class="fab fa-github"></i></a>
                                <a class="me-3 text-dark" href="https://www.instagram.com/hotelafricaqueen?igsh=MTAxMTU1Nm9rdDd0cQ=="><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="dashboard.php" class="navbar-brand d-block d-lg-none">
                        <h1 class="m-0 text-primary text-uppercase" style="white-space: nowrap;">boulakoDaraDiaral</h1>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0 ms-5">
                                <a href="dashboard.php" class="nav-item nav-link active">Dashboard</a>
                                <a href="listerChambre.php" class="nav-item nav-link ">Chambre</a>
                                <a href="listerCategorie.php" class="nav-item nav-link">Catégorie</a>
                                <a href="listerPrestation.php" class="nav-item nav-link">Prestation</a>
                                <a href="listerReservation.php" class="nav-item nav-link">Réservation</a>

                            </div>
                        </div>
                        <div class="navbar-nav ms-auto py-0">
                            <a href="logout.php" class="nav-item nav-link">Déconnexion</a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    <!-- Header End -->

        <!-- Formulaire d'ajout et de modification -->
        <div class="container mt-5">
            <h1 class="text-center">Gestion des Chambres</h1>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4"><?= isset($chambre) ? 'Modifier' : 'Ajouter' ?> une Chambre</h3>
                            <form id="form" class="form" action="listerChambre.php" method="POST" enctype="multipart/form-data">
                                <?php if (isset($chambre)): ?>
                                    <input type="hidden" name="id" value="<?= $chambre['idChambre'] ?>">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="nomChambre" class="form-label">Nom de la Chambre</label>
                                    <input type="text" class="form-control" id="nomChambre" name="nomChambre" value="<?= isset($chambre) ? $chambre['nomChambre'] : '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="numTelChambre" class="form-label">Numéro Téléphone Chambre</label>
                                    <input type="text" class="form-control" id="numTelChambre" name="numTelChambre" value="<?= isset($chambre) ? $chambre['numTelChambre'] : '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">État</label>
                                    <select class="form-select" name="etatChambre" required>
                                        <option value="disponible" <?= isset($chambre) && $chambre['etatChambre'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                        <option value="indisponible" <?= isset($chambre) && $chambre['etatChambre'] == 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie</label>
                                    <select class="form-select" id="categorie" name="idCat" required>
                                        <?php foreach ($listeCategorie as $value): ?>
                                            <option value="<?= $value['idCategorie'] ?>" <?= isset($chambre) && $chambre['idCategorieF'] == $value['idCategorie'] ? 'selected' : '' ?>>
                                                <?= $value['libelleCategorie'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de la Chambre</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <?php if (isset($chambre) && !empty($chambre['image'])): ?>
                                        <img src="<?= $chambre['image'] ?>" alt="Image de la chambre" style="width:100px; margin-top:10px;">
                                        <input type="hidden" name="existing_image" value="<?= $chambre['image'] ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" name="<?= isset($chambre) ? 'modifier' : 'ajouter' ?>">
                                        <?= isset($chambre) ? 'Modifier' : 'Ajouter' ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Liste des chambres -->
        <div class="container mt-5">
            <h2 class="text-center">Liste des Chambres</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom Chambre</th>
                        <th>État</th>
                        <th>Num Tel Chambre</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listeChambre as $chambre): ?>
                        <tr>
                            <td><?= $chambre['nomChambre'] ?></td>
                            <td><?= $chambre['etatChambre'] ?></td>
                            <td><?= $chambre['numTelChambre'] ?></td>
                            <td><?= $chambre['libelleCategorie'] ?></td>
                            <td><?= $chambre['montantCategorie'] ?></td>
                            <td><img src="<?= $chambre['image'] ?>" alt="Image de la chambre" style="width:100px;"></td>
                            <td>
                                <a href="listerChambre.php?idM=<?= $chambre['idChambre'] ?>" class="btn btn-success">Modifier</a>
                                <a href="listerChambre.php?idS=<?= $chambre['idChambre'] ?>" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
<!-- Footer Start -->
       <footer class="container bg-dark text-white pt-5 pb-4">
                <div class="container text-center text-md-left">
                    <div class="row text-center text-md-left">
                        <!-- About Section -->
                        <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                            <h5 class="text-uppercase mb-4 font-weight-bold text-primary">boulakoDaraDiaral</h5>
                            <p>Offrez-vous un séjour inoubliable dans notre établissement où confort et élégance se rencontrent.</p>
                        </div>



                        <!-- Contact Info -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                            <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Contact</h5>
                            <p><i class="fa fa-home me-2"></i> Dakar, Sénégal</p>
                            <p><i class="fa fa-envelope me-2"></i> boulakoDaraDiaral25@gmail.com</p>
                            <p><i class="fa fa-phone me-2"></i> +221 33 835 16 06</p>
                        </div>

                        <!-- Social Media -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mt-3">
                            <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Suivez-nous</h5>
                            <a class="btn btn-outline-light btn-floating m-1" href="https://www.facebook.com/share/1DayGRzEg8/?mibextid=wwXIfr" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="btn btn-outline-light btn-floating m-1" href="https://github.com/JudeBoy5" target="_blank">
                                <i class="fab fa-github"></i>
                            </a>
                            <a class="btn btn-outline-light btn-floating m-1" href="https://www.instagram.com/hotelafricaqueen?igsh=MTAxMTU1Nm9rdDd0cQ==" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="mb-4">

                    <!-- Copyright -->
                    <div class="row align-items-center">
                        <div class="col-md-7 col-lg-8">
                            <p class="mb-0">© 2025 <span class="text-primary fw-bold">boulakoDaraDiaral</span>. Tous droits réservés.</p>
                        </div>
                        <div class="col-md-5 col-lg-4">
                            <p class="mb-0 text-md-right">Conçu par <a href="https://github.com/JudeBoy5" class="text-white text-decoration-none fw-bold">JudeBoy5</a></p>
                        </div>
                    </div>
                </div>
            </footer>
<!-- Footer End -->
</body>
</html>