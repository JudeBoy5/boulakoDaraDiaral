<?php
session_start();

// Vérification de la session de l'utilisateur
if (empty($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

// Si le formulaire est soumis, enregistrer les données de réservation en session
if (isset($_POST['confirmer'])) {
    $dateDebut = new DateTime($_POST['dateDebut']);
    $dateFin = new DateTime($_POST['dateFin']);
    $today = new DateTime();

    // Validation des dates côté serveur
    if ($dateDebut >= $dateFin) {
        $_SESSION['message'] = 'La date de début doit être antérieure à la date de fin.';
        $_SESSION['message_type'] = 'danger';
        header("Location: accueil.php");
        exit;
    } elseif ($dateDebut < $today || $dateFin < $today) {
        $_SESSION['message'] = 'Les dates doivent être dans le futur.';
        $_SESSION['message_type'] = 'danger';
        header("Location: accueil.php");
        exit;
    }

    $_SESSION['reservation_data'] = [
        'dateDebut' => $_POST['dateDebut'],
        'dateFin' => $_POST['dateFin'],
        'idChambre' => $_POST['idChambre'],
        'prestations' => isset($_POST['prestations']) && is_array($_POST['prestations']) ? $_POST['prestations'] : []  // Vérification si c'est un tableau
    ];
    header("Location: confirmation_reservation.php");
    exit;
}

require_once '../bd/conBd.php';

$idUser = $_SESSION['user']['idUser'];

// Vérification si des données de réservation existent en session
$reservationData = $_SESSION['reservation_data'] ?? null;
if ($reservationData) {
    $dateDeb = new DateTime($reservationData['dateDebut']);
    $dateF = new DateTime($reservationData['dateFin']);
    $duree = $dateDeb->diff($dateF)->days;

    // Récupérer les infos de la chambre
    $stmt = $connexion->prepare("
        SELECT c.nomChambre, cat.libelleCategorie, cat.montantCategorie 
        FROM chambre c 
        JOIN categorie cat ON c.idCategorieF = cat.idCategorie 
        WHERE c.idChambre = ?
    ");
    $stmt->execute([$reservationData['idChambre']]);
    $chambreInfo = $stmt->fetch();

    // Calcul du montant de la chambre
    $montantChambre = $chambreInfo['montantCategorie'] * $duree;

    // Calcul des prestations choisies
    $montantPrestations = 0;
    $prestationsDetails = [];
    if (!empty($reservationData['prestations'])) {
        $placeholders = rtrim(str_repeat('?,', count($reservationData['prestations'])), ',');
        $stmt = $connexion->prepare("SELECT idPrestation, nomPrestation, prixPrestation FROM prestation WHERE idPrestation IN ($placeholders)");
        $stmt->execute($reservationData['prestations']);
        $prestationsDetails = $stmt->fetchAll();

        // Calculer le montant total des prestations
        foreach ($prestationsDetails as $prestation) {
            $montantPrestations += $prestation['prixPrestation'];
        }
    }

    // Montant total sans multiplication des prestations par la durée
    $montantTotal = $montantChambre + $montantPrestations;
}

// Gestion des actions (Annuler, Valider)
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'annuler') {
        // Libérer la session des données de réservation
        unset($_SESSION['reservation_data']);
        header("Location: connexion.php");
        exit;
    } elseif ($_POST['action'] === 'valider') {
        // Générer un numéro de réservation unique
        $numReservation = uniqid('RES-');
        $dateDebFormatted = $dateDeb->format('Y-m-d');
        $dateFinFormatted = $dateF->format('Y-m-d');

        // Insertion dans la base de données
        $stmt = $connexion->prepare("
            INSERT INTO reservation (numReservation, idUser, idChambre, dateDeb, dateFin, montantTotal, statut)
            VALUES (?, ?, ?, ?, ?, ?, 'en attente')
        ");
        $stmt->execute([$numReservation, $idUser, $reservationData['idChambre'], $dateDebFormatted, $dateFinFormatted, $montantTotal]);

        $idReservation = $connexion->lastInsertId();

        // Insertion des prestations
        if (!empty($reservationData['prestations'])) {
            $stmt = $connexion->prepare("INSERT INTO reservation_prestation (idReservation, idPrestation) VALUES (?, ?)");
            foreach ($reservationData['prestations'] as $idPrestation) {
                $stmt->execute([$idReservation, $idPrestation]);
            }
        }

        // Changer l'état de la chambre
        $sql = "UPDATE chambre SET etatChambre = 'indisponible' WHERE chambre.idChambre = :idChambre";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':idChambre' => $reservationData['idChambre']]);

        // Libérer la session des données de réservation
        unset($_SESSION['reservation_data']);

        // Redirection vers la génération du PDF
        header("Location: generer_pdf.php?idReservation=$idReservation");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title>Confirmation Réservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/assets2/img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/assets2/css/style.css" rel="stylesheet">

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
    /* * Style pour la lettre B du logo */ */
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
                            <a href="accueil.php" class="nav-item nav-link active">Accueil</a>
                                <a href="#nos-chambres" class="nav-item nav-link">Nos Chambres</a>
                                <a href="#service" class="nav-item nav-link">Nos Services</a>

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
        <h2 class="mb-4 text-center">Détails de votre Réservation</h2>

        <?php if (isset($reservationData)): ?>
            <table class="table table-bordered">
                <tr><th>Nom</th><td><?= htmlspecialchars($_SESSION['user']['nom']) ?></td></tr>
                <tr><th>Prénom</th><td><?= htmlspecialchars($_SESSION['user']['prenom']) ?></td></tr>
                <tr><th>Chambre</th><td><?= htmlspecialchars($chambreInfo['nomChambre']) ?></td></tr>
                <tr><th>Catégorie</th><td><?= htmlspecialchars($chambreInfo['libelleCategorie']) ?></td></tr>
                <tr><th>Prix Chambre</th><td><?= htmlspecialchars($chambreInfo['montantCategorie']) ?> FCFA</td></tr>
                <tr><th>Prestations</th>
                    <td>
                        <ul class="list-unstyled">
                            <?php 
                            if (!empty($prestationsDetails)) {
                                foreach ($prestationsDetails as $prestation) {
                                    echo "<li>" . htmlspecialchars($prestation['nomPrestation']) . " - " . htmlspecialchars($prestation['prixPrestation']) . " FCFA</li>";
                                }
                            } else {
                                echo "<li>Aucune prestation choisie.</li>";
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <tr><th>Montant Total</th><td><?= htmlspecialchars($montantTotal) ?> FCFA</td></tr>
            </table>

            <div class="d-flex justify-content-center mt-4">
                <form method="post" action="confirmation_reservation.php">
                    <button type="submit" name="action" value="annuler" class="btn btn-danger me-3">Annuler</button>
                    <button type="submit" name="action" value="valider" class="btn btn-success">Valider</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">Aucune réservation en cours. Veuillez retourner à l'accueil.</div>
        <?php endif; ?>
        </div><br><br><br>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>