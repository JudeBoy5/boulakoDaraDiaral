-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 08 fév. 2025 à 00:56
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boulakodaradiaral1`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `idCategorie` int(11) NOT NULL,
  `codeCategorie` varchar(5) NOT NULL,
  `libelleCategorie` varchar(50) NOT NULL,
  `montantCategorie` float NOT NULL,
  `etatCategorie` enum('disponible','indisponible') DEFAULT 'disponible',
  `description` text DEFAULT NULL,
  `imageCat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `codeCategorie`, `libelleCategorie`, `montantCategorie`, `etatCategorie`, `description`, `imageCat`) VALUES
(1, 'cat01', 'Standard', 20000, 'disponible', 'Confort simple ', 'imgs/room-1.jpg'),
(2, 'cat02', ' Premium', 30000, 'disponible', 'Chambre simple avec lits jumeaux, idéale pour une nuitée.', 'imgs/room-2.jpg'),
(3, 'cat03', ' Junior', 40000, 'disponible', 'Chambre spacieuse avec lit double, climatisation, et vue sur le jardin.', 'imgs/room-3.jpg'),
(4, 'cat04', ' Luxe', 40000, 'indisponible', 'Chambre de luxe avec lit king-size, baignoire, et vue sur la mer.', 'imgs/room-4.jpg'),
(5, 'cat05', 'Suite Royale', 100000, 'disponible', 'Chambre pour famille avec lits superposés et espace pour enfants.', 'imgs/room-5.jpg'),
(6, 'cat06', 'Royale', 50000, 'disponible', 'Profitez en !!', 'imgs/room-6.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `chambre`
--

CREATE TABLE `chambre` (
  `idChambre` int(5) NOT NULL,
  `nomChambre` varchar(50) NOT NULL,
  `numTelChambre` varchar(12) NOT NULL,
  `etatChambre` enum('disponible','indisponible') NOT NULL DEFAULT 'disponible',
  `idCategorieF` int(11) DEFAULT NULL,
  `image` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chambre`
--

INSERT INTO `chambre` (`idChambre`, `nomChambre`, `numTelChambre`, `etatChambre`, `idCategorieF`, `image`) VALUES
(7, 'chambremodifie', '778963632', 'indisponible', 1, 'imgs/room-1.jpg'),
(8, 'chambre2', '741233698', 'disponible', 1, 'imgs/room-1.jpg'),
(9, 'prem01', '789699654', 'indisponible', 2, 'imgs/room-2.jpg'),
(10, 'prem02', '789699654', 'disponible', 2, 'imgs/room-2.jpg'),
(11, 'jun1', '774510028', 'indisponible', 3, 'imgs/room-3.jpg'),
(12, 'jun2', '774510028', 'disponible', 3, 'imgs/room-4.jpg'),
(13, 'lux01', '774048747', 'indisponible', 4, 'imgs/room-5.jpg'),
(14, 'suit01', '772054896', 'disponible', 5, 'imgs/room-5.jpg'),
(15, 's1', '778963632', 'indisponible', 2, 'imgs/room-1.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `prestation`
--

CREATE TABLE `prestation` (
  `idPrestation` int(3) NOT NULL,
  `codePrestation` varchar(20) NOT NULL,
  `nomPrestation` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `prixPrestation` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prestation`
--

INSERT INTO `prestation` (`idPrestation`, `codePrestation`, `nomPrestation`, `description`, `prixPrestation`) VALUES
(1, 'prest01', 'petit déjeuner', 'simple à digérer !!!', 5000),
(2, 'prest02', 'Diner Gastronomique', 'Découvrez les délices de nos maisons', 15000),
(3, 'prest03', 'Accés au spa', 'Profitez de nos massages relaxants', 20000),
(4, 'prest05', 'accés à la piscine', 'plongez dans le bonheur', 12000);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `idReservation` int(5) NOT NULL,
  `numReservation` varchar(10) DEFAULT NULL,
  `dateDeb` date NOT NULL,
  `dateFin` date NOT NULL,
  `idChambre` int(5) DEFAULT NULL,
  `idUser` int(5) DEFAULT NULL,
  `statut` enum('en attente','validée','annulée') DEFAULT 'en attente',
  `montantTotal` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`idReservation`, `numReservation`, `dateDeb`, `dateFin`, `idChambre`, `idUser`, `statut`, `montantTotal`, `created_at`) VALUES
(1, 'RES-67a693', '2025-02-09', '2025-02-11', 13, 6, 'en attente', 105000, '2025-02-07 23:15:11'),
(2, 'RES-67a697', '2025-02-09', '2025-02-12', 11, 8, 'en attente', 145000, '2025-02-07 23:30:05'),
(3, 'RES-67a697', '2025-02-12', '2025-02-15', 9, 8, 'en attente', 95000, '2025-02-07 23:30:45');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_prestation`
--

CREATE TABLE `reservation_prestation` (
  `idReservationPrestation` int(5) NOT NULL,
  `idReservation` int(5) DEFAULT NULL,
  `idPrestation` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation_prestation`
--

INSERT INTO `reservation_prestation` (`idReservationPrestation`, `idReservation`, `idPrestation`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 3),
(5, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `idUser` int(5) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `adresse` varchar(30) NOT NULL,
  `role` enum('client','admin') NOT NULL DEFAULT 'client',
  `login` varchar(15) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `nom`, `prenom`, `tel`, `email`, `adresse`, `role`, `login`, `mdp`) VALUES
(1, 'dieng', 'papa Weurseuck', '782665906', 'papadieng@gmail.com', 'Parcelles Assainies', 'admin', 'jude', '$2y$10$1vmgazSDQVO9.tcR7MaCx.aXykJwuSUYsurG2ctbuFDBqw2/2gtru'),
(3, 'bellingham', 'jobe', '748512026', 'jo@gmail.com', 'californie', 'client', 'jobe', '$2y$10$E1PHGpCA23ePVS4k64VpzOs/6Rc.Dw.w0pUihcQBHoIgzi8o2qQW.'),
(4, 'djigo', 'mouhamed', '789633210', 'djigotech@gmail.com', 'parcelles', 'client', 'djigo', '$2y$10$DWOIZ14k0BdWr2065y4ny.yaXRkZwadZAiolb3kxkQDJsBbi1PPI.'),
(5, 'fall', 'kirikou', '763253724', 'kirikou@gmail.com', 'parcelles u5', 'client', 'kirikou', '$2y$10$e/pM2I4B5TBP6gcWk0G5rOdNJ3TOL5ZiOv.vJXKDV7s/Ajj/fZiLq'),
(6, 'Fall', 'Djibril', '774112562', 'dji@gmail.com', 'monaco', 'client', 'Djii', '$2y$10$oh7.q8ZyIJy..fiYLQNUKONJE9WktUQ46Zo0JLK3oCxX9Dn5twEra'),
(7, 'dieng', 'mamadou', '781162501', 'mamad@gmail.com', 'guediawaye', 'client', 'mama', '$2y$10$H8lAn2egbbWEYVDCnSvGM.nt9th57LZL7J9MulvKDGxw/YzcUqoEW'),
(8, 'Touty', 'mame', '741256321', 'bousso@gmail.com', 'parcelles', 'client', 'mame', '$2y$10$F4zWZ0T3sxMVhNJvr/xbze9LVIjiAYxU9HSMlXe01NpA9QKAaoF6q');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `chambre`
--
ALTER TABLE `chambre`
  ADD PRIMARY KEY (`idChambre`),
  ADD KEY `fkidCategorieF` (`idCategorieF`);

--
-- Index pour la table `prestation`
--
ALTER TABLE `prestation`
  ADD PRIMARY KEY (`idPrestation`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`idReservation`),
  ADD KEY `fkIdChambre` (`idChambre`),
  ADD KEY `fkIdUser` (`idUser`);

--
-- Index pour la table `reservation_prestation`
--
ALTER TABLE `reservation_prestation`
  ADD PRIMARY KEY (`idReservationPrestation`),
  ADD KEY `idReservation` (`idReservation`),
  ADD KEY `idPrestation` (`idPrestation`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `chambre`
--
ALTER TABLE `chambre`
  MODIFY `idChambre` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `prestation`
--
ALTER TABLE `prestation`
  MODIFY `idPrestation` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `idReservation` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservation_prestation`
--
ALTER TABLE `reservation_prestation`
  MODIFY `idReservationPrestation` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chambre`
--
ALTER TABLE `chambre`
  ADD CONSTRAINT `fkidCategorieF` FOREIGN KEY (`idCategorieF`) REFERENCES `categorie` (`idCategorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fkIdChambre` FOREIGN KEY (`idChambre`) REFERENCES `chambre` (`idChambre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkIdUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservation_prestation`
--
ALTER TABLE `reservation_prestation`
  ADD CONSTRAINT `reservation_prestation_ibfk_1` FOREIGN KEY (`idReservation`) REFERENCES `reservation` (`idReservation`),
  ADD CONSTRAINT `reservation_prestation_ibfk_2` FOREIGN KEY (`idPrestation`) REFERENCES `prestation` (`idPrestation`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
