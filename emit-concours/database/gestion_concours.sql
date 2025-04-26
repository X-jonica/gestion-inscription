-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 26 avr. 2025 à 16:02
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_concours`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `nom`, `email`, `mot_de_passe`, `date_creation`) VALUES
(3, 'Clarisse', 'admin@concours.com', 'admin123', '2025-04-25 23:17:37');

-- --------------------------------------------------------

--
-- Structure de la table `candidats`
--

CREATE TABLE `candidats` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `type_bacc` varchar(50) DEFAULT NULL,
  `annee_bacc` year(4) DEFAULT NULL,
  `recu_paiement` varchar(100) DEFAULT '0',
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `candidats`
--

INSERT INTO `candidats` (`id`, `nom`, `prenom`, `email`, `telephone`, `type_bacc`, `annee_bacc`, `recu_paiement`, `password_hash`) VALUES
(18, 'Rakoto', 'Jean', 'jean.rakoto@exemple.com', '032123458', 'Scientifique', '2018', '1564i48', NULL),
(19, 'Rasoanaivo', 'Marie', 'marie.rasoanaivo@gmail.com', '0340545512', 'Littéraire', '2019', '1564i49', NULL),
(20, 'Andrianarivo', 'Jacques', 'jacques.andrianarivo@gmail.fr', '0323332211', 'Scientifique', '2020', '1564i48', NULL),
(21, 'Rabetafika', 'Sophie', 'sophie.rabetafika@gmail.com', '0324455667', 'Scientifique', '2022', '1564i54', NULL),
(22, 'Randrianarisoa', 'Patrick', 'patrick.randrianarisoa@example.com', '0325544332', 'Littéraire', '2021', '1564i55', NULL),
(23, 'Raharimanana', 'Lalao', 'lalao.raharimanana@example.com', '0326677889', 'Scientifique', '2024', '1564i58', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `concours`
--

CREATE TABLE `concours` (
  `id` int(11) NOT NULL,
  `mention` varchar(50) NOT NULL,
  `date_concours` date NOT NULL,
  `statut` enum('ouvert','fermé') DEFAULT 'ouvert'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `concours`
--

INSERT INTO `concours` (`id`, `mention`, `date_concours`, `statut`) VALUES
(1, 'Informatique', '2025-05-10', 'ouvert'),
(2, 'Management', '2025-05-15', 'ouvert'),
(3, 'Multimédia', '2025-05-20', 'ouvert');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int(11) NOT NULL,
  `candidat_id` int(11) NOT NULL,
  `concours_id` int(11) NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','validé','rejeté') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `candidat_id`, `concours_id`, `date_inscription`, `statut`) VALUES
(17, 18, 1, '2025-04-26 15:27:34', 'validé'),
(18, 19, 2, '2025-04-26 15:30:25', 'en_attente'),
(19, 20, 2, '2025-04-26 15:31:36', 'en_attente'),
(20, 21, 1, '2025-04-26 15:32:29', 'validé'),
(21, 22, 3, '2025-04-26 15:33:22', 'en_attente'),
(22, 23, 1, '2025-04-26 15:34:11', 'rejeté');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `concours`
--
ALTER TABLE `concours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidat_id` (`candidat_id`),
  ADD KEY `concours_id` (`concours_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `candidats`
--
ALTER TABLE `candidats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `concours`
--
ALTER TABLE `concours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`candidat_id`) REFERENCES `candidats` (`id`),
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`concours_id`) REFERENCES `concours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
