<?php
session_start();

if (empty($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
require_once('FPDF/fpdf.php');
require_once '../bd/conBd.php';

$idReservation = $_GET['idReservation'] ?? null;

if (!$idReservation) {
    header("Location: accueil.php");
    exit;
}
// Récupérer les détails de la réservation
$stmt = $connexion->prepare("
    SELECT r.*, c.nomChambre, cat.libelleCategorie, cat.montantCategorie
    FROM reservation r
    JOIN chambre c ON r.idChambre = c.idChambre
    JOIN categorie cat ON c.idCategorieF = cat.idCategorie
    WHERE r.idReservation = ?
");
$stmt->execute([$idReservation]);
$reservation = $stmt->fetch();

if (!$reservation) {
    header("Location: http://localhost/Php2/boulakoDaraDiaral/pages/accueil.php");
    exit;
}

// Récupérer les prestations
$stmt = $connexion->prepare("
    SELECT p.nomPrestation, p.prixPrestation 
    FROM reservation_prestation rr
    JOIN prestation p ON rr.idPrestation = p.idPrestation
    WHERE rr.idReservation = ?
");
$stmt->execute([$idReservation]);
$prestations = $stmt->fetchAll();

// Calcul du montant total (incluant la chambre)
$montantTotal = $reservation['montantCategorie'];
foreach ($prestations as $prestation) {
    $montantTotal += $prestation['prixPrestation'];
}

// Génération du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Confirmation de Réservation'), 0, 1, 'C');
$pdf->Ln(10);


// Numéro de réservation
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Numéro de Réservation: ') . $reservation['numReservation'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Nom: ') . $_SESSION['user']['nom'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Prénom: ') . $_SESSION['user']['prenom'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Chambre: ') . $reservation['nomChambre'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Catégorie: ') . $reservation['libelleCategorie'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Date de début: ') . $reservation['dateDeb'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Date de fin: ') . $reservation['dateFin'], 0, 1);
$pdf->Ln(10);

// Prestations
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Prestations choisies:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
foreach ($prestations as $prestation) {
    $pdf->Cell(0, 10, utf8_decode($prestation['nomPrestation']) . ' - ' . $prestation['prixPrestation'] . ' FCFA', 0, 1);
}
$pdf->Ln(10);

// Montant total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Montant Total: ') . $montantTotal . ' FCFA', 0, 1);
$pdf->Ln(10);

// Message de remerciement
$pdf->SetFont('Arial', 'I', 12);
$pdf->MultiCell(0, 10, utf8_decode('Merci pour votre réservation! Nous vous remercions de votre confiance et nous sommes impatients de vous accueillir. N\'hésitez pas à nous contacter pour toute question supplémentaire.'));
$pdf->Ln(10);

// Contact
$pdf->Cell(0, 10, utf8_decode('Contactez-nous: +221 33 835 16 06 | boulakoDaraDiaral25@gmail.com'), 0, 1, 'C');



// Afficher le PDF dans le navigateur
$pdf->Output('I', 'reservation_' . $idReservation . '.pdf');  // 'I' pour l'affichage dans le navigateur

// Ne pas faire de redirection ici, car elle interrompt l'affichage du PDF
// Si vous avez besoin de rediriger, faites-le avant de générer le PDF.
