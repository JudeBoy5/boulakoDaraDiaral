<?php
session_start();

require_once '../bd/conBd.php';
function getAllReservations() {
    global $connexion;
    $sql = "SELECT reservation.*, users.nom, users.prenom, users.email, chambre.nomChambre 
            FROM reservation 
            INNER JOIN users ON reservation.idUser = users.idUser 
            INNER JOIN chambre ON reservation.idChambre = chambre.idChambre 
            ORDER BY reservation.dateDeb DESC"; // Tri par date de début (du plus récent au plus ancien)
    return $connexion->query($sql)->fetchAll();
}

// Récupérer toutes les réservations
$reservations = getAllReservations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>
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


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
<!-- Header Start -->
    <div class="container bg-dark px-0">
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

    <div class="container mt-5">
        <h1 class="text-center">Liste des Réservations</h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Chambre</th>
                    <th>Dates</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= $reservation['numReservation'] ?></td>
                        <td><?= $reservation['nom'] ?> <?= $reservation['prenom'] ?></td>
                        <td><?= $reservation['nomChambre'] ?></td>
                        <td><?= $reservation['dateDeb'] ?> - <?= $reservation['dateFin'] ?></td>
                        <td><?= $reservation['montantTotal'] ?> FCFA</td>
                        <td>
                            <span class="badge bg-<?= $reservation['statut'] === 'validée' ? 'success' : 'warning' ?>">
                                <?= $reservation['statut'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($reservation['statut'] === 'en attente'): ?>
                                <form method="POST" action="gestion_reservation.php">
                                    <input type="hidden" name="action" value="valider_reservation_admin">
                                    <input type="hidden" name="idReservation" value="<?= $reservation['idReservation'] ?>">
                                    <button type="submit" class="btn btn-success">Valider</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        
    </div>
</footer>
<!-- Footer End -->

</body>
</html>