-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 07:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guichet_automatique`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartebancaire`
--

CREATE TABLE `cartebancaire` (
  `numéroCarte` int(50) NOT NULL,
  `codePIN` varchar(255) NOT NULL,
  `dateExpiration` varchar(255) NOT NULL,
  `état` tinytext NOT NULL DEFAULT 'active',
  `clientId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comptebancaire`
--

CREATE TABLE `comptebancaire` (
  `idCompte` bigint(20) NOT NULL,
  `type` tinytext NOT NULL DEFAULT 'courant',
  `solde` bigint(20) NOT NULL,
  `devise` varchar(255) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `limite_retrait` varchar(255) NOT NULL,
  `statut_compte` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comptebancaire`
--

INSERT INTO `comptebancaire` (`idCompte`, `type`, `solde`, `devise`, `clientId`, `limite_retrait`, `statut_compte`) VALUES
(1, 'courant', 800, 'USD', 1, '10', 'Activé'),
(2, 'courant', 100, 'fb', 2, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `guichetautomatique`
--

CREATE TABLE `guichetautomatique` (
  `idGuichet` bigint(20) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `etat` tinytext NOT NULL DEFAULT 'en service',
  `soldedisponible` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guichetautomatique`
--

INSERT INTO `guichetautomatique` (`idGuichet`, `localisation`, `nom`, `etat`, `soldedisponible`) VALUES
(1, 'Bujumbura Mairie', 'Guichet Buja', 'Ouvert', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `historique`
--

CREATE TABLE `historique` (
  `idHistorique` bigint(20) NOT NULL,
  `dateHeure` varchar(255) NOT NULL,
  `typeEvenement` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `idGuichet` bigint(20) NOT NULL,
  `idPersonnel` bigint(20) NOT NULL,
  `idClient` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `historique`
--

INSERT INTO `historique` (`idHistorique`, `dateHeure`, `typeEvenement`, `message`, `idGuichet`, `idPersonnel`, `idClient`) VALUES
(1, '2025-05-18 03:45:00', 'Dépôt', 'Dépôt de 50 effectué sur le compte 1 via guichet 1.', 1, 0, 1),
(2, '2025-05-18 03:46:48', 'Dépôt', 'Dépôt de 50 effectué sur le compte 1 via guichet 1.', 1, 1, 1),
(3, '2025-05-18 04:02:57', 'Retrait', 'Retrait de 50 effectué du compte 1 via guichet 1.', 1, 1, 1),
(4, '2025-05-18 16:53:25', 'Retrait', 'Retrait de 50 effectué du compte 1 via guichet 1.', 1, 1, 1),
(5, '2025-05-18 16:54:42', 'Retrait', 'Retrait de 50 effectué du compte 1 via guichet 1.', 1, 1, 1),
(6, '2025-05-18 16:54:59', 'Retrait', 'Retrait de 50 effectué du compte 1 via guichet 1.', 1, 1, 1),
(7, '2025-05-18 16:55:24', 'Retrait', 'Retrait de 50 effectué du compte 1 via guichet 1.', 1, 1, 1),
(8, '2025-05-18 16:59:07', 'Dépôt', 'Dépôt de 300 effectué sur le compte 1 via guichet 1.', 1, 1, 1),
(9, '2025-05-18 16:59:22', 'Dépôt', 'Dépôt de 300 effectué sur le compte 1 via guichet 1.', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `personnels`
--

CREATE TABLE `personnels` (
  `id` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `matricule` bigint(20) NOT NULL,
  `statut` tinytext NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnels`
--

INSERT INTO `personnels` (`id`, `type`, `matricule`, `statut`) VALUES
('1', 'Client', 0, 'actif'),
('2', 'Technicien', 0, 'actif'),
('3', 'Technicien', 0, 'actif'),
('4', 'Client', 0, 'actif'),
('5', 'Client', 0, 'actif'),
('6', 'Administrateur', 0, 'Désactivé'),
('7', 'Administrateur', 0, 'Désactivé'),
('8', 'Administrateur', 0, 'Désactivé'),
('9', 'Administrateur', 0, 'Activé'),
('10', 'Client', 0, 'Activé');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `idTransaction` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `dateHeure` varchar(255) NOT NULL,
  `montant` bigint(20) NOT NULL,
  `idCompte` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` tinytext NOT NULL DEFAULT 'Client',
  `limite_retrait` varchar(255) NOT NULL,
  `statut_compte` varchar(255) NOT NULL,
  `devise_utilisee` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `nom`, `prenom`, `mot_de_passe`, `email`, `type`, `limite_retrait`, `statut_compte`, `devise_utilisee`) VALUES
(1, '', 'Jean-luc', 'kashindi', '$2y$10$DYND47EApmkCpAyPNzuqfe0YiSGkARj2eHERubWhWqmdtGSiQYxh2', 'editeluc@gmail.com', 'Technicien', '', '', ''),
(2, '', 'David', 'kashindi', '$2y$10$hhNcc9gz4QBgSmLy694eHumRqZup2B1HJLttDhwadubU.EQVInC4S', 'dav@gmail.com', 'Technicien', '', '', ''),
(3, '', 'moise', 'kashindi', '$2y$10$Be.LIBUeulFpEQb6mXJvdeMVND/E5e.Ec0Z0avwCaWTb7Uhhbo4S2', 'moise@gmail.com', 'Technicien', '', '', ''),
(4, '', 'Safi', 'Kibas', '$2y$10$2DE7jRnOKxmv5PiUCvbVjewMYi1xQBadHzStjVm.b284Ba51LeYg6', 'safi@gmail.com', 'Admin', '', '', ''),
(6, '', 'Jean-luc', 'Shikaneza', '$2y$10$g6/DWqT163nJVjJVs5nMhO02nzdoTgNLU0VYq3WVxCAeiuP1bdpYC', 'luc@gmail.com', 'Administrateur', '', 'Désactivé', ''),
(7, '', 'Jean-luc', 'Shikaneza', '$2y$10$0yzsiqoAL/A50rMK7LCRoOvLgDTNQxpiE.lz8vh0PKlpJSiS/LhgO', 'kalenga.chance@ecole.com', 'Administrateur', '', 'Désactivé', ''),
(8, '', 'Jean-luc', 'Shikaneza', '$2y$10$sONJCygekVNxKyEmtA.oxOF/AISJlR4cOtY6skalS1FQ1ekKWELjW', 'kalenga.chance@ecole.com', 'Administrateur', '', 'Désactivé', ''),
(9, '', 'Jean-luc', 'kashindi', '$2y$10$vMLRYNLfQmpgUEVh2hZeXOkf/uF7wthGIo6UOT1JxuJfqFiegY74C', 'jeanluckashindi812@gmil.com', 'Administrateur', '', 'Activé', ''),
(10, '', 'Nathalie', 'kashindi', '$2y$10$EVFAxCMT33k4qjvKRxtA1uQ69kft4hz9wnADsMSyWutjNqGE6X2j.', 'nath@gmail.com', 'Client', '', 'Activé', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comptebancaire`
--
ALTER TABLE `comptebancaire`
  ADD PRIMARY KEY (`idCompte`);

--
-- Indexes for table `guichetautomatique`
--
ALTER TABLE `guichetautomatique`
  ADD PRIMARY KEY (`idGuichet`);

--
-- Indexes for table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`idHistorique`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`idTransaction`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comptebancaire`
--
ALTER TABLE `comptebancaire`
  MODIFY `idCompte` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guichetautomatique`
--
ALTER TABLE `guichetautomatique`
  MODIFY `idGuichet` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `historique`
--
ALTER TABLE `historique`
  MODIFY `idHistorique` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idTransaction` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
