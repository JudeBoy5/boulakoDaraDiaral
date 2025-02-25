<?php
session_start();

require_once '../bd/conBd.php';
// Redirection si non connecté
if (empty($_SESSION['user'])) {
    header("Location: connexion.php");
}
// Récupération des infos utilisateur
$user = $_SESSION['user'];

// Fonction pour récupérer les catégories
function getCategorie() {
    $sql = "SELECT * FROM categorie";
    global $connexion; 
    return $connexion->query($sql)->fetchAll(); 
}

// Fonction pour récupérer les chambres disponibles
function getChambre() {
    global $connexion;
    $sql = "SELECT chambre.*, categorie.libelleCategorie, categorie.montantCategorie 
            FROM chambre 
            INNER JOIN categorie ON chambre.idCategorieF = categorie.idCategorie
            WHERE chambre.etatChambre = 'disponible'"; // Filtrer par chambres disponibles
    return $connexion->query($sql)->fetchAll();
}

// Fonction pour récupérer les prestations
function getPrestation() {
    $sql = "SELECT * FROM prestation";
    global $connexion; 
    return $connexion->query($sql)->fetchAll(); 
}

// Fonction pour récupérer les utilisateurs (clients)
function getUser() {
    $sql = "SELECT * FROM users";
    global $connexion;
    return $connexion->query($sql)->fetchAll();
}

// Récupérer les données
$listeprestation = getPrestation();
$listeClients = getUser();
$listecategorie = getCategorie();
$listeChambre = getChambre(); // Récupérer uniquement les chambres disponibles
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hotelier - Hotel HTML Template</title>
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

</head>
<style>
   /* Changer la couleur du hover des liens */
    .nav-item.nav-link {
        color: #ffffff; /* Couleur de base des liens */
        font-size: 1.1rem; /* Taille des liens */
    }

    .nav-item.nav-link:hover {
        color: #007bff; /* Couleur bleue au survol (bleu classique) */
    }

    /* Style pour la lettre B du logo */
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
    .service-item {
        background-color: #ffffff; /* Fond blanc par défaut */
        transition: all 0.3s ease-in-out;
        border: 1px solid #ddd; /* Bordure légère */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce */
        text-decoration: none; /* Pas de soulignement par défaut */
    }

    .service-item:hover {
        background-color: #0d6efd; /* Couleur primaire de Bootstrap pour le survol */
        color: #ffffff !important; /* Texte en blanc au survol */
        transform: translateY(-5px); /* Légère élévation au survol */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Ombre plus marquée */
    }

    .service-item:hover h5,
    .service-item:hover p {
        /* text-decoration: underline; Soulignement du texte au survol */
    }

    .service-icon {
        transition: all 0.3s ease-in-out;
    }

    .service-item:hover .service-icon {
        background-color: #ffffff; /* L'icône reste sur fond blanc au survol */
        border-color: #ffffff;
    }

    .service-icon i {
        transition: color 0.3s ease-in-out;
    }

    .service-item:hover .service-icon i {
        color: #0d6efd; /* Icône en bleu primaire au survol */
    }

 

</style>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Header Start -->
        <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
            <div class="col-lg-4 d-none d-lg-flex justify-content-center py-3">
                <a href="accueil.php" class="navbar-brand text-center logo-text">
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

        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5">
            <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="w-100" src="../assets/assets2/img/carousel-1.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                                <h1 class="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                                <a href="#nos-chambres" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Nos Chambres</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item ">
                        <img class="w-100" src="../assets/assets2/img/carousel-2.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                                <h1 class="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                                <a href="#nos-chambres" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Nos Chambres</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->

        <!-- Notifications Start -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <!-- Notifications End -->
        <!-- Nos Chambres Section -->
        <div class="container-xxl py-5" id="nos-chambres">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Nos Catégories</h6>
                    <h1 class="mb-5">Explorez Nos <span class="text-primary text-uppercase">Catégories</span></h1>
                </div>
                <div class="row g-4">
                <?php foreach ($listecategorie as $categorie): ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="room-item shadow rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="../assets/assets2/img/room-1.jpg" alt="">
                            </div>
                            <div class="p-4 mt-2">
                                <img src="<?= $categorie['imageCat'] ?>" class="card-img-top" alt="Image de la categorie" style="height: 200px; object-fit: cover;">
                                <h5 class="mb-0"><?= $categorie['libelleCategorie'] ?></h5>
                                <p class="text-body mb-3"><?= $categorie['description'] ?></p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReservation<?= $categorie['idCategorie'] ?>">
                                    Réserver
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de réservation pour chaque catégorie -->
                    <div class="modal fade" id="modalReservation<?= $categorie['idCategorie'] ?>" tabindex="-1" aria-labelledby="modalReservationLabel<?= $categorie['idCategorie'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalReservationLabel<?= $categorie['idCategorie'] ?>">Réserver une chambre <?= $categorie['libelleCategorie'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php 
                                    // Récupérer les chambres disponibles pour cette catégorie
                                    $chambres_disponibles = array_filter($listeChambre, function($chambre) use ($categorie) {
                                        return $chambre['idCategorieF'] == $categorie['idCategorie'] && $chambre['etatChambre'] === 'disponible';
                                    });
                                    
                                    if(empty($chambres_disponibles)): ?>
                                        <div class="alert alert-danger">Cette catégorie n'a plus de chambres disponibles</div>
                                    <?php else: ?>
                                        <form method="POST" id="reservationForm" class="reservationForm" action="confirmation_reservation.php">
                                            <!-- <input type="hidden" name="action" value="reserver"> -->
                                            <input type="hidden" name="idCategorie" value="<?= $categorie['idCategorie'] ?>">
                                            <input type="hidden" name="idUser" value="<?= $user['idUser'] ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Chambre</label>
                                                <select name="idChambre" class="form-select" required>
                                                    <?php foreach($chambres_disponibles as $chambre): ?>
                                                        <option value="<?= $chambre['idChambre'] ?>"><?= $chambre['nomChambre'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Date d'arrivée</label>
                                                <input type="date" name="dateDebut" id="dateDebut" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Date de départ</label>
                                                <input type="date" name="dateFin" id="dateFin" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Prestations</label><br>
                                                <?php foreach ($listeprestation as $prestation): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="prestations[]" value="<?= $prestation['idPrestation'] ?>" id="prestation<?= $prestation['idPrestation'] ?>">
                                                        <label class="form-check-label" for="prestation<?= $prestation['idPrestation'] ?>">
                                                            <?= $prestation['nomPrestation'] ?> - <?= $prestation['prixPrestation'] ?> FCFA
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button type="submit" name="confirmer"class="btn btn-primary w-100">Confirmer la Réservation</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Nos Chambres End -->

        <!-- Service Start -->
        <div id="service" class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Nos Services</h6>
                    <h1 class="mb-5">Découvrez Nos <span class="text-primary text-uppercase">Prestations</span></h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-hotel fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Chambres & Appartements</h5>
                            <p class="text-body mb-0">Profitez de nos chambres modernes et confortables pour un séjour inoubliable.</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-utensils fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Restauration & Gastronomie</h5>
                            <p class="text-body mb-0">Savourez une cuisine raffinée avec des plats locaux et internationaux.</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-spa fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Spa & Bien-Être</h5>
                            <p class="text-body mb-0">Détendez-vous avec nos soins spa, massages et espaces de relaxation.</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-swimmer fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Sports & Loisirs</h5>
                            <p class="text-body mb-0">Des activités sportives pour tous les âges, de la piscine aux jeux en plein air.</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-glass-cheers fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Événements & Réceptions</h5>
                            <p class="text-body mb-0">Organisez vos fêtes, mariages ou séminaires dans un cadre exceptionnel.</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <a class="service-item rounded" href="#service">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-dumbbell fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Salle de Sport & Yoga</h5>
                            <p class="text-body mb-0">Maintenez votre forme avec nos équipements de fitness et cours de yoga.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
<!-- Service End -->


       <!-- Footer Start -->
            <footer class="bg-dark text-white pt-5 pb-4">
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

         

    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
    <!-- Script de validation des dates -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reservationForms = document.querySelectorAll('.reservationForm');

            reservationForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const dateDebut = new Date(form.querySelector('#dateDebut').value);
                    const dateFin = new Date(form.querySelector('#dateFin').value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Ignorer l'heure pour la comparaison

                    // Validation des dates
                    if (dateDebut >= dateFin) {
                        event.preventDefault();
                        alert('La date de début doit être antérieure à la date de fin.');
                    } else if (dateDebut < today || dateFin < today) {
                        event.preventDefault();
                                                // alert('Les dates doivent être dans le futur.');
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'La date de début doit être antérieure à la date de fin.'
                        });
                                            }
                });
            });
        });
    </script>
    
</body>

</html>