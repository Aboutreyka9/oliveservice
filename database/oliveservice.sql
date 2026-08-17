-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 17 août 2026 à 14:17
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `oliveservice`
--

-- --------------------------------------------------------

--
-- Structure de la table `annees`
--

DROP TABLE IF EXISTS `annees`;
CREATE TABLE IF NOT EXISTS `annees` (
  `id_annee` int NOT NULL AUTO_INCREMENT,
  `libelle_annee` varchar(50) NOT NULL,
  `date_debut_annee` date NOT NULL,
  `date_fin_annee` date NOT NULL,
  `code_annee` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_annee` datetime NOT NULL,
  `updated_at_annee` datetime DEFAULT NULL,
  `statut_annee` enum('actif','inactif','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_annee`),
  UNIQUE KEY `uq_annee_libelle` (`libelle_annee`,`etablissement_code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `annees`
--

INSERT INTO `annees` (`id_annee`, `libelle_annee`, `date_debut_annee`, `date_fin_annee`, `code_annee`, `etablissement_code`, `created_at_annee`, `updated_at_annee`, `statut_annee`, `user_code`) VALUES
(1, '2025-2026', '2026-07-07', '2027-01-07', 'VL0hWQ', '5454544456', '2026-07-22 23:40:46', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(2, '2025- 2026', '2026-07-07', '2027-01-07', 'Lv9LWUf7IdxEny', '5454544456', '2026-07-22 23:41:31', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(3, '2026-2027', '2026-07-07', '2027-01-07', '6DSpC5ev5eJac6ShmSSwUHm4ah1s9baP', '5454544456', '2026-07-23 00:18:51', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(4, '20252026', '2026-07-22', '2027-01-22', '7ObaWc', '5454544456', '2026-07-23 00:21:22', NULL, 'inactif', '5wBEh2OfI00frxk8ITPf'),
(5, '2022-2023', '2022-01-22', '2023-01-22', 'aP939', '5454544456', '2026-07-23 02:34:31', '2026-07-23 03:52:45', 'inactif', '5wBEh2OfI00frxk8ITPf'),
(6, '2027-2028', '2026-07-22', '2028-01-22', 'T2POn4rE', '5454544456', '2026-07-23 02:39:31', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(7, '2028-2029', '2028-10-04', '2029-10-17', '0GklBk07waYoLB6pHwY', '5454544456', '2026-07-23 03:08:13', '2026-07-23 03:46:31', 'actif', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `code_article` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `libelle_article` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description_article` text,
  `statut_article` enum('actif','inactif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  `created_at_article` timestamp NOT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `code_accessoire` (`code_article`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_article`, `code_article`, `libelle_article`, `description_article`, `statut_article`, `created_at_article`, `etablissement_code`, `user_code`) VALUES
(1, 'MGUlgKfYtYque', 'Huile', '', 'actif', '2026-08-04 10:34:19', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(2, 'd6IUbns', 'Savon', '', 'actif', '2026-08-04 10:34:30', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(3, 'V5a3XlKXuxJmG2POYGsAPAt4OSQ6', 'Poulet', '', 'actif', '2026-08-04 10:34:39', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(4, 'URFZFVbJ0Pd', 'Marmite', '', 'actif', '2026-08-04 10:34:47', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(5, 'BBjHzzgKfQZTP', 'Sac de riz', '', 'actif', '2026-08-04 10:34:56', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(6, 'tQ0oaE7ppDJ4zETjVl9u', 'boite de sardine', '', 'actif', '2026-08-04 10:35:12', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(7, 'uLzk80G4zBGwJMu7uuY2VEFBDhNWhm', 'pack d&#039;eau', '', 'actif', '2026-08-04 10:35:32', '5454544456', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_packs`
--

DROP TABLE IF EXISTS `categorie_packs`;
CREATE TABLE IF NOT EXISTS `categorie_packs` (
  `id_categorie_pack` int NOT NULL AUTO_INCREMENT,
  `libelle_categorie_pack` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code_categorie_pack` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_categorie_pack` datetime NOT NULL,
  `updated_at_categorie_pack` datetime DEFAULT NULL,
  `statut_categorie_pack` enum('actif','inactif','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_categorie_pack`),
  UNIQUE KEY `uq_annee_libelle` (`libelle_categorie_pack`,`etablissement_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie_packs`
--

INSERT INTO `categorie_packs` (`id_categorie_pack`, `libelle_categorie_pack`, `code_categorie_pack`, `etablissement_code`, `created_at_categorie_pack`, `updated_at_categorie_pack`, `statut_categorie_pack`, `user_code`) VALUES
(1, 'categorie A', '6QIlVfXP0LiXE9tBzHownYLAA324qDi2', '5454544456', '2026-08-01 18:09:24', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(2, 'categorie B', 'l36Gc45t', '5454544456', '2026-08-01 18:09:30', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(3, 'CATEGORIE 3', '0o9Wub9H', '5454544456', '2026-08-03 12:29:41', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(4, 'CATEGORIE A6', 'WylIcOZKZQUCJe304', '5454544456', '2026-08-03 12:31:46', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(5, 'CATEGORIE P', 've7D90DQcfhu6k0lpJ1S', '5454544456', '2026-08-03 12:56:06', NULL, 'actif', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `cautisation_clients`
--

DROP TABLE IF EXISTS `cautisation_clients`;
CREATE TABLE IF NOT EXISTS `cautisation_clients` (
  `id_cautisation_client` int NOT NULL,
  `code_cautisation_client` varchar(50) NOT NULL,
  `montant_cautisation_client` int NOT NULL,
  `inscription_code` varchar(50) NOT NULL,
  `statut_cautisation_client` enum('En attente','valide','ennule') NOT NULL,
  `created_at_cautisation_client` datetime NOT NULL,
  `updated_at_cautisation_client` datetime NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `code_client` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nom_client` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sexe_client` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lieu_residence_client` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `profession_client` varchar(255) DEFAULT NULL,
  `telephone_client` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email_client` varchar(50) DEFAULT NULL,
  `photo_client` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password_client` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `numero_cni` varchar(50) DEFAULT NULL,
  `created_at_client` datetime NOT NULL,
  `updated_at_client` datetime DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `zone_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `code_etudiant` (`code_client`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `code_client`, `nom_client`, `sexe_client`, `lieu_residence_client`, `profession_client`, `telephone_client`, `email_client`, `photo_client`, `password_client`, `numero_cni`, `created_at_client`, `updated_at_client`, `user_code`, `zone_code`, `etablissement_code`) VALUES
(9, 'wyopxuIweBV8GjQ3omFoXe4', 'Test kolo', 'Mlle', 'Cupidatat deserunt a', 'Mane', '(+225) 01 44 42 39 72', NULL, NULL, NULL, NULL, '2026-08-14 12:21:09', '2026-08-16 08:41:42', '5wBEh2OfI00frxk8ITPf', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456'),
(10, 'wE2rKMLnr8Cpou32QYzai6ED', 'Ut est dolore aut n', 'Masculin', 'Aliquam similique ma', 'Ipsam molestiae dolo', '(+225) 01 71 65 26 95', 'jacicasu@mailinator.com', NULL, NULL, NULL, '2026-08-14 12:28:15', NULL, '5wBEh2OfI00frxk8ITPf', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456'),
(11, 'OM30bC7UHKbwG0CjDT', 'Quia quia earum quis', 'Masculin', 'Sed ad voluptas ulla', 'Qui sint elit unde', '(+225) 01 27 77 58 40', 'fokul@mailinator.com', NULL, NULL, NULL, '2026-08-14 12:29:32', NULL, '5wBEh2OfI00frxk8ITPf', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456');

-- --------------------------------------------------------

--
-- Structure de la table `commercials`
--

DROP TABLE IF EXISTS `commercials`;
CREATE TABLE IF NOT EXISTS `commercials` (
  `id_commercial` int NOT NULL AUTO_INCREMENT,
  `code_commercial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statut_commercial` enum('actif','inactif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  `created_at_commercial` datetime NOT NULL,
  `updated_at_commercial` datetime DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_commercial`),
  UNIQUE KEY `code_enseignant` (`code_commercial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE IF NOT EXISTS `depenses` (
  `id_depense` int NOT NULL AUTO_INCREMENT,
  `type_depense_code` varchar(50) NOT NULL,
  `code_depense` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `montant_depense` float NOT NULL,
  `description_depense` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode_depense` datetime NOT NULL,
  `created_at_depense` datetime NOT NULL,
  `updated_at_depense` datetime DEFAULT NULL,
  `annee_code` varchar(50) NOT NULL,
  `statut_depense` enum('En attente','Confirmee','Annulee') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_confirm` varchar(50) DEFAULT NULL,
  `created_at_confirm` datetime DEFAULT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_depense`),
  KEY `type_id` (`type_depense_code`),
  KEY `employe_id` (`user_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `depenses`
--

INSERT INTO `depenses` (`id_depense`, `type_depense_code`, `code_depense`, `user_code`, `montant_depense`, `description_depense`, `periode_depense`, `created_at_depense`, `updated_at_depense`, `annee_code`, `statut_depense`, `user_confirm`, `created_at_confirm`, `etablissement_code`) VALUES
(1, '6insFl', 'hs276kQkxDP7xR2Y', '5wBEh2OfI00frxk8ITPf', 9000, 'Adipisci itaque exer', '1980-04-22 00:00:00', '2026-07-25 00:25:54', '2026-07-26 02:54:52', '0GklBk07waYoLB6pHwY', 'En attente', NULL, NULL, '5454544456'),
(2, 'JrghRg3EL', 'ex9NbVtWCp', '5wBEh2OfI00frxk8ITPf', 5000, 'Perferendis sed iste', '2011-12-16 06:26:00', '2026-07-25 00:27:30', NULL, '0GklBk07waYoLB6pHwY', 'Confirmee', '5wBEh2OfI00frxk8ITPf', '2026-07-25 00:27:30', '5454544456'),
(3, 'galafcB9ipUnBcAv9Fc9xvFG0m', 'EZwl982dJ35Y7TFe0dCbiccof87gP', '5wBEh2OfI00frxk8ITPf', 8000, '', '2023-06-25 09:45:29', '2026-07-25 14:51:22', NULL, '0GklBk07waYoLB6pHwY', 'En attente', NULL, NULL, '5454544456');

-- --------------------------------------------------------

--
-- Structure de la table `distributions`
--

DROP TABLE IF EXISTS `distributions`;
CREATE TABLE IF NOT EXISTS `distributions` (
  `id_distribution` int NOT NULL AUTO_INCREMENT,
  `code_distribution` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `inscription_code` varchar(50) NOT NULL,
  `zone_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statut_distribution` enum('En attente','valide','ennule') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at_distribution` timestamp NOT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_distribution`),
  UNIQUE KEY `code_accessoire_inscription` (`code_distribution`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etablissements`
--

DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE IF NOT EXISTS `etablissements` (
  `id_etablissement` int NOT NULL AUTO_INCREMENT,
  `code_etablissement` varchar(50) NOT NULL,
  `libelle_etablissement` varchar(200) NOT NULL,
  `adresse_etablissement` text,
  `telephone_etablissement` varchar(30) DEFAULT NULL,
  `telephone_etablissement2` varchar(30) DEFAULT NULL,
  `email_etablissement` varchar(150) DEFAULT NULL,
  `logo_etablissement` varchar(500) DEFAULT NULL,
  `slogan_etablissement` varchar(200) DEFAULT NULL,
  `created_at_etablissement` datetime NOT NULL,
  `updated_at_etablissement` datetime DEFAULT NULL,
  `statut_etablissement` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_etablissement`),
  UNIQUE KEY `code_etablissement` (`code_etablissement`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etablissements`
--

INSERT INTO `etablissements` (`id_etablissement`, `code_etablissement`, `libelle_etablissement`, `adresse_etablissement`, `telephone_etablissement`, `telephone_etablissement2`, `email_etablissement`, `logo_etablissement`, `slogan_etablissement`, `created_at_etablissement`, `updated_at_etablissement`, `statut_etablissement`) VALUES
(1, '5454544456', 'etablissement A', 'adresse', '0102030405', '0302010405', 'test@gmail.com', 'testtt', 'test', '2026-07-05 15:36:21', NULL, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `fonctions`
--

DROP TABLE IF EXISTS `fonctions`;
CREATE TABLE IF NOT EXISTS `fonctions` (
  `id_fonction` int NOT NULL AUTO_INCREMENT,
  `libelle_fonction` varchar(50) NOT NULL,
  `code_fonction` varchar(50) NOT NULL,
  `statut_fonction` enum('actif','inactif','','') NOT NULL DEFAULT 'actif',
  `description_fonction` text,
  `user_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `etablissement_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_at_fonction` datetime DEFAULT NULL,
  `updated_at_fonction` datetime DEFAULT NULL,
  PRIMARY KEY (`id_fonction`),
  UNIQUE KEY `code_fonction` (`code_fonction`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fonctions`
--

INSERT INTO `fonctions` (`id_fonction`, `libelle_fonction`, `code_fonction`, `statut_fonction`, `description_fonction`, `user_code`, `etablissement_code`, `created_at_fonction`, `updated_at_fonction`) VALUES
(1, 'fonction a', '8875', 'actif', NULL, '123', '5454544456', '2026-07-13 23:32:56', NULL),
(2, 'FONCTION B', '958', 'inactif', 'desc 222', '123', '5454544456', '2026-07-15 23:33:04', '2026-07-16 04:38:06'),
(5, 'FONCTION C', '545644', 'actif', 'desc', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 02:54:29', NULL),
(8, 'FONCTION D', 'LYsl3iNLmmrAUiL', 'actif', 'desc', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 02:56:59', '2026-07-16 05:12:37'),
(9, 'FONCTION E', 'C1OapnqyN8Uf11SlLl', 'actif', 'desc', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 02:57:08', NULL),
(10, 'FONCTION F', 'KXxGPo0ZktLI4V', 'actif', 'dfdf', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 03:01:01', NULL),
(11, 'FONCTION G', 'anZU3oVbNt8KhPwwai', 'actif', '', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 18:30:25', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `code_inscription` varchar(50) NOT NULL,
  `client_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `zone_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `session_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  `created_at_inscription` datetime NOT NULL,
  `updated_at_inscription` datetime DEFAULT NULL,
  `statut_inscription` enum('valide','solde','annule','reconduit') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_inscription`),
  UNIQUE KEY `uq_inscription` (`client_code`,`annee_code`),
  KEY `user_code` (`user_code`),
  KEY `session_code` (`session_code`),
  KEY `zone_code` (`zone_code`),
  KEY `etablissement_code` (`etablissement_code`),
  KEY `code_inscription` (`code_inscription`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id_inscription`, `code_inscription`, `client_code`, `zone_code`, `etablissement_code`, `annee_code`, `session_code`, `user_code`, `created_at_inscription`, `updated_at_inscription`, `statut_inscription`) VALUES
(10, 'IZH94NvZA24MgPaeNvxHTaL', 'lopBd5aRL3H', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456', '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '5wBEh2OfI00frxk8ITPf', '2026-08-14 12:17:58', NULL, 'valide'),
(11, 'aF4DpbPrR72MqzBcKTU56', 'wyopxuIweBV8GjQ3omFoXe4', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456', '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '5wBEh2OfI00frxk8ITPf', '2026-08-14 12:21:09', NULL, 'valide'),
(12, 'JefTHmBKIuN', 'wE2rKMLnr8Cpou32QYzai6ED', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456', '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '5wBEh2OfI00frxk8ITPf', '2026-08-14 12:28:15', NULL, 'valide'),
(13, 'vV3ghTnI5WaB1', 'OM30bC7UHKbwG0CjDT', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456', '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '5wBEh2OfI00frxk8ITPf', '2026-08-14 12:29:32', NULL, 'valide');

-- --------------------------------------------------------

--
-- Structure de la table `packs`
--

DROP TABLE IF EXISTS `packs`;
CREATE TABLE IF NOT EXISTS `packs` (
  `id_pack` int NOT NULL AUTO_INCREMENT,
  `code_pack` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `libelle_pack` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `montant_pack` int NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `session_code` varchar(50) NOT NULL,
  `zone_code` varchar(50) NOT NULL,
  `categorie_pack_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_pack` datetime NOT NULL,
  `updated_at_pack` datetime DEFAULT NULL,
  `statut_pack` enum('actif','inactif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pack`),
  UNIQUE KEY `code_cycle` (`code_pack`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `packs`
--

INSERT INTO `packs` (`id_pack`, `code_pack`, `libelle_pack`, `montant_pack`, `annee_code`, `session_code`, `zone_code`, `categorie_pack_code`, `etablissement_code`, `created_at_pack`, `updated_at_pack`, `statut_pack`, `user_code`) VALUES
(11, 'l4ymf5', 'KJCFHD', 5000, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '6QIlVfXP0LiXE9tBzHownYLAA324qDi2', '5454544456', '2026-08-08 01:42:49', '2026-08-11 11:42:24', 'actif', '5wBEh2OfI00frxk8ITPf'),
(12, 'itT57K3khk9', 'KJCFHD4', 5000, '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '0o9Wub9H', '5454544456', '2026-08-08 01:43:07', '2026-08-11 11:42:45', 'actif', '5wBEh2OfI00frxk8ITPf'),
(13, 'XMGgOdiDgzQK1', 'KJCFHD', 5000, '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '0o9Wub9H', '5454544456', '2026-08-08 01:43:23', '2026-08-10 01:25:57', 'actif', '5wBEh2OfI00frxk8ITPf'),
(14, 'FNEF8arPTaNxhYUGlGrjhHlN', 'PACK 1', 900, '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', 'WylIcOZKZQUCJe304', '5454544456', '2026-08-08 11:47:30', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(15, 'Il1dx', 'PACK 55', 500, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '0o9Wub9H', '5454544456', '2026-08-09 10:52:39', '2026-08-11 11:34:11', 'actif', '5wBEh2OfI00frxk8ITPf'),
(16, '0cQQ2lb44PX', 'PACK 2', 300, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '0o9Wub9H', '5454544456', '2026-08-09 11:03:01', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(17, 'wv5aMldjjok4ds1k', 'CUM SOLUTA ELIT VEN', 92, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', 'l36Gc45t', '5454544456', '2026-08-09 22:02:50', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(18, 'dKzkvIgRTCuCc6OFMHL8CsiH', 'DOLORES VELIT QUIDEM', 35, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', 've7D90DQcfhu6k0lpJ1S', '5454544456', '2026-08-09 22:04:30', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(19, '2fR8BqCBEbQ0b94mWKelXn4HNPSodCN', 'PACK 1', 100, '0GklBk07waYoLB6pHwY', 'l8rmIqVzNWaRYF6Nb7kuckHC', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '6QIlVfXP0LiXE9tBzHownYLAA324qDi2', '5454544456', '2026-08-09 22:05:28', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(20, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'PACK 1', 100, '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '0o9Wub9H', '5454544456', '2026-08-09 22:06:19', '2026-08-11 11:38:08', 'actif', '5wBEh2OfI00frxk8ITPf'),
(21, '4Xghnzv8tQKcVu', 'QUASI DOLOREM OFFICI', 75, '0GklBk07waYoLB6pHwY', 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', 'l36Gc45t', '5454544456', '2026-08-10 01:24:07', NULL, 'actif', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `pack_articles`
--

DROP TABLE IF EXISTS `pack_articles`;
CREATE TABLE IF NOT EXISTS `pack_articles` (
  `id_pack_article` int NOT NULL AUTO_INCREMENT,
  `quantite_article` int NOT NULL,
  `pack_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `article_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `created_at_pack_article` timestamp NOT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pack_article`),
  UNIQUE KEY `unique_pack_article` (`pack_code`,`article_code`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `pack_articles`
--

INSERT INTO `pack_articles` (`id_pack_article`, `quantite_article`, `pack_code`, `article_code`, `annee_code`, `created_at_pack_article`, `etablissement_code`, `user_code`) VALUES
(8, 1, 'l4ymf5', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-08 01:42:49', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(9, 1, 'l4ymf5', 'BBjHzzgKfQZTP', '0GklBk07waYoLB6pHwY', '2026-08-08 01:42:49', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(10, 1, 'itT57K3khk9', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:07', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(11, 1, 'itT57K3khk9', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:07', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(14, 1, 'XMGgOdiDgzQK1', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:23', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(15, 1, 'XMGgOdiDgzQK1', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:23', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(16, 3, 'XMGgOdiDgzQK1', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:23', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(17, 1, 'XMGgOdiDgzQK1', 'BBjHzzgKfQZTP', '0GklBk07waYoLB6pHwY', '2026-08-08 01:43:23', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(18, 1, 'FNEF8arPTaNxhYUGlGrjhHlN', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-08 11:47:30', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(19, 1, 'FNEF8arPTaNxhYUGlGrjhHlN', 'V5a3XlKXuxJmG2POYGsAPAt4OSQ6', '0GklBk07waYoLB6pHwY', '2026-08-08 11:47:30', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(22, 1, '0cQQ2lb44PX', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-09 11:03:01', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(23, 1, '0cQQ2lb44PX', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-09 11:03:01', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(24, 1, 'wv5aMldjjok4ds1k', 'd6IUbns', '0GklBk07waYoLB6pHwY', '2026-08-09 22:02:50', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(25, 1, 'wv5aMldjjok4ds1k', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-09 22:02:50', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(26, 1, 'dKzkvIgRTCuCc6OFMHL8CsiH', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-09 22:04:30', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(27, 1, 'dKzkvIgRTCuCc6OFMHL8CsiH', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-09 22:04:30', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(28, 1, '2fR8BqCBEbQ0b94mWKelXn4HNPSodCN', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-09 22:05:28', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(29, 1, '2fR8BqCBEbQ0b94mWKelXn4HNPSodCN', 'uLzk80G4zBGwJMu7uuY2VEFBDhNWhm', '0GklBk07waYoLB6pHwY', '2026-08-09 22:05:28', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(30, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-09 22:06:19', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(31, 1, '4Xghnzv8tQKcVu', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-10 01:24:07', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(32, 1, '4Xghnzv8tQKcVu', 'd6IUbns', '0GklBk07waYoLB6pHwY', '2026-08-10 01:24:07', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(33, 1, '', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-11 11:31:18', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(34, 1, '', 'V5a3XlKXuxJmG2POYGsAPAt4OSQ6', '0GklBk07waYoLB6pHwY', '2026-08-11 11:31:18', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(35, 1, '', 'BBjHzzgKfQZTP', '0GklBk07waYoLB6pHwY', '2026-08-11 11:31:18', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(36, 1, 'Il1dx', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-11 11:33:20', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(37, 1, 'Il1dx', 'V5a3XlKXuxJmG2POYGsAPAt4OSQ6', '0GklBk07waYoLB6pHwY', '2026-08-11 11:33:20', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(38, 1, 'Il1dx', 'BBjHzzgKfQZTP', '0GklBk07waYoLB6pHwY', '2026-08-11 11:33:20', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(39, 1, 'Il1dx', 'tQ0oaE7ppDJ4zETjVl9u', '0GklBk07waYoLB6pHwY', '2026-08-11 11:34:11', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(43, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'MGUlgKfYtYque', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(44, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'URFZFVbJ0Pd', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(45, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'uLzk80G4zBGwJMu7uuY2VEFBDhNWhm', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(46, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'V5a3XlKXuxJmG2POYGsAPAt4OSQ6', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(47, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'BBjHzzgKfQZTP', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(48, 1, 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', 'd6IUbns', '0GklBk07waYoLB6pHwY', '2026-08-11 11:38:08', '5454544456', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `pack_inscriptions`
--

DROP TABLE IF EXISTS `pack_inscriptions`;
CREATE TABLE IF NOT EXISTS `pack_inscriptions` (
  `id_pack_inscription` int NOT NULL AUTO_INCREMENT,
  `inscription_code` varchar(50) NOT NULL,
  `pack_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `statut_pack_inscription` enum('actif','inactif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  `created_at_pack_inscription` timestamp NOT NULL,
  `etablissement_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_pack_inscription`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `pack_inscriptions`
--

INSERT INTO `pack_inscriptions` (`id_pack_inscription`, `inscription_code`, `pack_code`, `annee_code`, `statut_pack_inscription`, `created_at_pack_inscription`, `etablissement_code`, `user_code`) VALUES
(10, 'aF4DpbPrR72MqzBcKTU56', 'itT57K3khk9', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:21:09', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(11, 'aF4DpbPrR72MqzBcKTU56', 'XMGgOdiDgzQK1', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:21:09', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(12, 'aF4DpbPrR72MqzBcKTU56', '4Xghnzv8tQKcVu', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:21:09', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(13, 'JefTHmBKIuN', '4Xghnzv8tQKcVu', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:28:15', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(14, 'JefTHmBKIuN', 'itT57K3khk9', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:28:15', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(15, 'JefTHmBKIuN', 'XMGgOdiDgzQK1', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:28:15', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(16, 'JefTHmBKIuN', 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:28:15', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(17, 'vV3ghTnI5WaB1', '4Xghnzv8tQKcVu', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:29:32', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(18, 'vV3ghTnI5WaB1', 'XMGgOdiDgzQK1', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:29:32', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(19, 'vV3ghTnI5WaB1', 'FXtY9QdsO0RxbDrnwP9YsgwLNM3ApKAG', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:29:32', '5454544456', '5wBEh2OfI00frxk8ITPf'),
(20, 'vV3ghTnI5WaB1', 'FNEF8arPTaNxhYUGlGrjhHlN', '0GklBk07waYoLB6pHwY', 'actif', '2026-08-14 12:29:32', '5454544456', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `code_paiement` varchar(50) NOT NULL,
  `montant_paiement` decimal(12,2) NOT NULL,
  `date_paiement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut_paiement` enum('en_attente','confirme','annule','rembourse','echoue') NOT NULL DEFAULT 'confirme',
  `reference_paiement` varchar(100) DEFAULT NULL,
  `observations` text,
  `type_paiement` varchar(100) NOT NULL,
  `mode_paiement` varchar(50) NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  `annee_code` varchar(50) DEFAULT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_paiement`),
  UNIQUE KEY `code_paiement` (`code_paiement`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle_role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `code_role` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `groupe` varchar(50) NOT NULL,
  `statut_role` enum('actif','inactif','null','') NOT NULL DEFAULT 'actif',
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_role` (`code_role`),
  KEY `groupe` (`groupe`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `libelle_role`, `code_role`, `module`, `groupe`, `statut_role`, `description`) VALUES
(1, 'Super administrateur', 'super_admin', 'GLOBAL', 'SUPER', 'actif', 'Accès total à tous les modules'),
(2, 'Administration - Paramétrage', 'admin_param', 'ADMIN', 'ADMIN', 'actif', 'Gestion des paramètres globaux'),
(3, 'Administration - Utilisateurs', 'admin_user', 'ADMIN', 'ADMIN', 'actif', 'Gestion des utilisateurs et rôles'),
(4, 'Comptable - Caisse', 'compt_caisse', 'FINANCE', 'COMPTABLE', 'actif', 'Gestion de la caisse et paiements'),
(5, 'Comptable - Dépenses', 'compt_depense', 'FINANCE', 'COMPTABLE', 'actif', 'Gestion des dépenses'),
(6, 'Comptable - Versements', 'compt_versement', 'FINANCE', 'COMPTABLE', 'actif', 'Suivi des versements commerciaux'),
(7, 'Gestionnaire - Validations', 'gest_valid', 'FINANCE', 'GESTION', 'actif', 'Validation des versements et cautions'),
(8, 'Gestionnaire - Distributions', 'gest_distrib', 'CLIENTS', 'GESTION', 'actif', 'Gestion des distributions articles'),
(9, 'Gestionnaire - Cautions', 'gest_caution', 'CLIENTS', 'GESTION', 'actif', 'Validation des cautions clients'),
(10, 'Commercial - Clients', 'comm_client', 'CLIENTS', 'COMMERCIAL', 'actif', 'Gestion des clients et souscriptions'),
(11, 'Commercial - Cautions', 'comm_caution', 'CLIENTS', 'COMMERCIAL', 'actif', 'Enregistrement des paiements cautions'),
(12, 'Commercial - Versements', 'comm_versement', 'FINANCE', 'COMMERCIAL', 'actif', 'Dépôts de versements au bureau');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id_service` int NOT NULL AUTO_INCREMENT,
  `libelle_service` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `code_service` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `statut_service` enum('actif','inactif','','') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'actif',
  `description_service` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `user_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `etablissement_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_at_service` datetime DEFAULT NULL,
  `updated_at_service` datetime DEFAULT NULL,
  PRIMARY KEY (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id_service`, `libelle_service`, `code_service`, `statut_service`, `description_service`, `user_code`, `etablissement_code`, `created_at_service`, `updated_at_service`) VALUES
(1, 'service a', '123', 'inactif', NULL, '123', '5454544456', '2026-07-15 23:32:31', NULL),
(2, 'service 2', '955', 'actif', NULL, '123', '5454544456', '2026-07-15 23:32:38', NULL),
(3, 'SERVICE C', 'FA02X3L3sfCSuXdmDM54UMFLE', 'actif', 'desc', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 21:18:39', NULL),
(4, 'SERVICE D', '6NTz5lQBGmVX7ZLBr0nwB6Xbp4o', 'actif', '', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 21:43:40', NULL),
(5, 'SERVICE E', 'fUDNX8pBowvYWAFC', 'actif', '', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 21:55:39', NULL),
(6, 'SERVICE R', 'uGSgaDroZ', 'inactif', '', '5wBEh2OfI00frxk8ITPf', '5454544456', '2026-07-16 21:57:55', '2026-07-17 01:09:34');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id_session` int NOT NULL AUTO_INCREMENT,
  `code_session` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `libelle_session` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `statut_session` enum('actif','inactif','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `date_debut_session` date DEFAULT NULL,
  `date_fin_session` date DEFAULT NULL,
  `created_at_session` datetime NOT NULL,
  `updated_at_session` datetime DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_session`),
  UNIQUE KEY `uq_semestre` (`code_session`,`annee_code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id_session`, `code_session`, `libelle_session`, `statut_session`, `etablissement_code`, `annee_code`, `date_debut_session`, `date_fin_session`, `created_at_session`, `updated_at_session`, `user_code`) VALUES
(1, 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', 'SESSION 1', 'actif', '5454544456', '0GklBk07waYoLB6pHwY', '2026-07-23', '2026-07-24', '2026-07-23 16:06:33', NULL, '5wBEh2OfI00frxk8ITPf'),
(2, 'l8rmIqVzNWaRYF6Nb7kuckHC', 'SESSION 2', 'actif', '5454544456', '0GklBk07waYoLB6pHwY', '2026-07-25', '2026-08-27', '2026-07-23 16:07:01', NULL, '5wBEh2OfI00frxk8ITPf'),
(3, 'TwvwmFiQn8hPkv0a4HKjk3IZTMY', 'SESSION 2', 'actif', '5454544456', 'Lv9LWUf7IdxEny', '2026-05-13', '2026-10-02', '2026-07-23 16:08:14', '2026-07-23 21:45:59', '5wBEh2OfI00frxk8ITPf'),
(4, 'R0PCytWZghb0', 'SESSION2', 'inactif', '5454544456', 'T2POn4rE', '2026-07-07', '2026-08-01', '2026-07-23 16:08:30', NULL, '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `type_depenses`
--

DROP TABLE IF EXISTS `type_depenses`;
CREATE TABLE IF NOT EXISTS `type_depenses` (
  `id_type_depense` int NOT NULL AUTO_INCREMENT,
  `code_type_depense` varchar(50) NOT NULL,
  `libelle_type_depense` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_type_depense`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type_depenses`
--

INSERT INTO `type_depenses` (`id_type_depense`, `code_type_depense`, `libelle_type_depense`, `etablissement_code`, `user_code`) VALUES
(1, 'HHArOrYitt5SIqhsaQbW3Z', 'Loyer', '5454544456', ''),
(2, '6insFl', 'Electricite', '5454544456', ''),
(3, 'tqx2gn7lOSFP6kiUuDJyjqNmvVwYDPE7', 'Eau', '5454544456', ''),
(4, 'galafcB9ipUnBcAv9Fc9xvFG0m', 'Internet', '5454544456', ''),
(5, 'OaY57VVTdIn7ayks79OLLgvrTpWmhl', 'Telephone', '5454544456', ''),
(6, 'tSGtfRFUm', 'Salaire', '5454544456', ''),
(7, 'G3Ibcx3QnK1cXI7z9fyk6lcD2i', 'Transport', '5454544456', ''),
(8, 'kzFlVwFvY', 'Alimentation', '5454544456', ''),
(9, 'JrghRg3EL', 'Entretien', '5454544456', ''),
(10, '94elkZvYZ0QCMXf', 'Fournitures', '5454544456', ''),
(11, '0S8zkrutsMy', 'Sante', '5454544456', ''),
(12, 'pnVnA4QnaFakQ9eva2n1', 'Education', '5454544456', ''),
(13, 'RE9FdmkRnanwWPS6MzcMmJGdQ', 'Imprevus', '5454544456', ''),
(14, 'HzsxO0GTdviZCfyUA', 'Frais de route', '5454544456', ''),
(15, 'I5zeVG31ACjKrR5gihA9BSBiVHcELT', 'Frais de transport', '5454544456', ''),
(16, 'QZ1A3i4PqFUzg0zC3qrSEoir', 'Emballage', '5454544456', ''),
(17, '43KFbNycCzMaCNP', 'Autres', '5454544456', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `code_user` varchar(50) NOT NULL,
  `matricule_user` varchar(50) DEFAULT NULL,
  `nom_user` varchar(100) NOT NULL,
  `prenom_user` varchar(100) NOT NULL,
  `email_user` varchar(150) NOT NULL,
  `sexe_user` varchar(20) DEFAULT NULL,
  `password_user` varchar(255) NOT NULL,
  `telephone_user` varchar(30) DEFAULT NULL,
  `photo_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_connexion` datetime DEFAULT NULL,
  `token_user` varchar(255) DEFAULT NULL,
  `service_code` varchar(50) DEFAULT NULL,
  `fonction_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `auth_uid` varchar(255) DEFAULT NULL,
  `created_at_user` datetime NOT NULL,
  `updated_at_user` datetime DEFAULT NULL,
  `statut_user` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email_user` (`email_user`),
  UNIQUE KEY `code_user` (`code_user`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `code_user`, `matricule_user`, `nom_user`, `prenom_user`, `email_user`, `sexe_user`, `password_user`, `telephone_user`, `photo_user`, `last_connexion`, `token_user`, `service_code`, `fonction_code`, `etablissement_code`, `auth_uid`, `created_at_user`, `updated_at_user`, `statut_user`) VALUES
(1, '5wBEh2OfI00frxk8ITPf', '54564777165', 'Admin', 'Admin', 'admin@gmail.com', 'Mr', '$2y$10$ik1kUCxvYJcPL2qhdMH.Iur04TxFgoDh8IhvA1vRgeT8Pfn5pl1AG', '(+225) 05 44 56 45 64', NULL, '2026-08-17 14:02:31', NULL, '123', '8875', '5454544456', NULL, '2026-07-15 11:48:46', NULL, 'actif'),
(2, 'yhveAqqunh', 'AUT VOLUPTATEM MINU', 'ID RERUM IUSTO LABOR 2', 'MAXIME EXERCITATION', 'vijasit@mailinator.com', 'Mme', '$2y$10$.CAaqXxLvPBmGENuZQuwgedqy0JMOtud/W5n1wk8v5WFJWzHdknwK', '(+225) 01 82 95 39 55', NULL, NULL, 'FEjrZldQclekdKylyairPzLZr6S6Yxy0rYQrrCkSv9zUU16UP8', '123', '8875', '5454544456', NULL, '2026-07-15 11:41:56', NULL, 'actif'),
(9, 'JwucjkPg4w', 'EAQUE DUCIMUS VOLUP', 'DOLORES SED VOLUPTAT', 'EUM EARUM UT QUAS ES', 'zoddoudep@m5ailinator.com', 'Mlle', '$2y$10$a8MD50XhdXzJ59bBkZF.y.FxK0bkAgsMnTQlkT0VQjGbnsCh65Dei', '(+225) 01 43 67 81 55', NULL, NULL, 'pYQ20JoEy3dxhgv8pmEJw6cqo7rw6o7bxxJccCRn1VJyecP9V1UAL', '955', '8875', '5454544456', NULL, '2026-07-06 03:04:15', NULL, 'inactif'),
(8, 'Xq9daapChi', 'SUNT EXPLICABO DOLO', 'UT ERROR FUGA INCID', 'APERIAM EXERCITATION', 'qenenu@mailinator.com', 'Mr', '$2y$10$7abhEN0B8mj47O8mb1vCCuG9IaFiS3sLWV1Ozb53f7difq2Oa9m.G', '(+225) 01 39 49 23 37', NULL, NULL, 'Ll9Jr8ODtxSEUnEWL5YOsQR4y7v8jjGFKHjmAsyWjEJsEt6bgNt', '955', '958', '5454544456', NULL, '2026-07-06 03:03:08', NULL, 'actif'),
(7, 'wbwyd', 'IURE EXPLICABO AUTE', 'FACILIS AD IN HIC VO 5', 'MOLESTIAE COMMODO NI', 'absano5go9@gmail.com', 'Mr', '$2y$10$iW9t.mb2sgR18q1EzkL0mO03oKiJ8CdEyY7mHv3slr70PVDYeBEIK', '(+225) 01 20 23 26 76', NULL, NULL, 'XNGmKhjcrJ5FoR2ARMP2OEZdfqCAyrCHvKDnDK4SqPHFAqXgv0ciZWAlyEFdlRHje', '955', '958', '5454544456', NULL, '2026-07-15 11:45:01', NULL, 'inactif'),
(10, 'Kjd35lpOuL9vewm5KM4yT', 'SUSCIPIT DISTINCTIO', 'EST QUI NAM DOLORES', 'QUI CONSEQUATUR AD', 'kemaduxepa@mailinator.com', 'Mlle', '$2y$10$VUh4ezknaDXwIc8fky64UeZRWjKyiXuXYEq.3bNCsIxJMev2l19za', '(+225) 01 55 43 91 85', NULL, NULL, 'urAqo1j0OLsDtprTHKPL4WhLy0rOaERHKL6Am8zhVkWBCsGi8kJojtlM3RLWkKIpPK', NULL, 'C1OapnqyN8Uf11SlLl', '5454544456', NULL, '2026-07-16 05:13:36', NULL, 'actif'),
(11, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', '54564165', 'TEST', 'TESGGS', 'abs54anogo9@gmail.com', 'Mr', '$2y$10$vBL9TS0RRWJDglM55w47QuqxpkGT223QehFn45CPcoxRqCrWENOsy', '(+225) 05 44 54 56 54', NULL, NULL, '7rvTvGGaIHvudI8ZHHikZVdHFxyWE9B0R0O8qSnj334szE1LfIG0ZlwMIbgAb', NULL, '8875', '5454544456', NULL, '2026-07-14 01:46:23', NULL, 'actif'),
(12, 'NX6ZvNYGfdO5pjn5ZTeSH0d9hZBXAG', '54564777165', 'Tytdg', 'Tesggs', 'absango9@gmail.com', 'Mme', '$2y$10$IOL0FHhuOECqft6iNC4lf.pr.f9cF8bGaRnt6fhifKcHq4AZn1t1.', '(+225) 05 44 45 46 46', NULL, NULL, 'TATRWLXdavgPFUqmrlCpGMozczYaZDtNcu31nfpZy02ZuySWrgeb', NULL, '8875', '5454544456', NULL, '2026-07-14 03:00:35', NULL, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `role_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `create_permission` int NOT NULL DEFAULT '0',
  `edit_permission` int NOT NULL DEFAULT '0',
  `show_permission` int NOT NULL DEFAULT '0',
  `delete_permission` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_role` (`user_code`,`role_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_code`, `role_code`, `create_permission`, `edit_permission`, `show_permission`, `delete_permission`) VALUES
(1, '5wBEh2OfI00frxk8ITPf', 'super_admin', 1, 1, 1, 1),
(2, '5wBEh2OfI00frxk8ITPf', 'admin_param', 1, 1, 1, 1),
(3, '5wBEh2OfI00frxk8ITPf', 'admin_user', 1, 1, 1, 1),
(4, '5wBEh2OfI00frxk8ITPf', 'compt_caisse', 1, 1, 1, 1),
(5, '5wBEh2OfI00frxk8ITPf', 'compt_depense', 1, 1, 1, 1),
(6, '5wBEh2OfI00frxk8ITPf', 'compt_versement', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `versements_commerciaux`
--

DROP TABLE IF EXISTS `versements_commerciaux`;
CREATE TABLE IF NOT EXISTS `versements_commerciaux` (
  `id_versement` int NOT NULL AUTO_INCREMENT,
  `code_versement_commercial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reference_versement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `montant_versement` int NOT NULL,
  `commercial_code` varchar(50) NOT NULL,
  `periode_versement_debut` date NOT NULL,
  `periode_versement_fin` date NOT NULL,
  `statut_versement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `created_at_versement` datetime NOT NULL,
  `zone_code` varchar(50) NOT NULL,
  `user_validate` varchar(50) NOT NULL,
  `date_validation` datetime NOT NULL,
  `commentaire_validation` text NOT NULL,
  PRIMARY KEY (`id_versement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_pack_total_articles`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_pack_total_articles`;
CREATE TABLE IF NOT EXISTS `vue_pack_total_articles` (
`annee_code` varchar(50)
,`categorie_pack_code` varchar(50)
,`code_pack` varchar(50)
,`created_at_pack` datetime
,`etablissement_code` varchar(50)
,`id_pack` int
,`libelle_pack` varchar(100)
,`montant_pack` int
,`nombre_article` bigint
,`quantite` decimal(32,0)
,`session_code` varchar(50)
,`statut_pack` enum('actif','inactif')
,`updated_at_pack` datetime
,`user_code` varchar(50)
,`zone_code` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure de la table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
  `id_zone` int NOT NULL AUTO_INCREMENT,
  `libelle_zone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code_zone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_zone` datetime NOT NULL,
  `updated_at_zone` datetime DEFAULT NULL,
  `statut_zone` enum('actif','inactif','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_zone`),
  UNIQUE KEY `uq_annee_libelle` (`libelle_zone`,`etablissement_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `zones`
--

INSERT INTO `zones` (`id_zone`, `libelle_zone`, `code_zone`, `etablissement_code`, `created_at_zone`, `updated_at_zone`, `statut_zone`, `user_code`) VALUES
(1, 'ZONE A', '6QIlVfXP0LiXE9tBzHownYLAAqDi2', '5454544456', '2026-08-01 18:09:24', NULL, 'actif', '5wBEh2OfI00frxk8ITPf'),
(2, 'ZONE B', 'l36Gct', '5454544456', '2026-08-01 18:09:30', NULL, 'actif', '5wBEh2OfI00frxk8ITPf');

-- --------------------------------------------------------

--
-- Structure de la table `zone_commercials`
--

DROP TABLE IF EXISTS `zone_commercials`;
CREATE TABLE IF NOT EXISTS `zone_commercials` (
  `id_zone_commercial` int NOT NULL AUTO_INCREMENT,
  `commercial_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `zone_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_zone_commercial` datetime NOT NULL,
  `updated_at_zone_commercial` datetime DEFAULT NULL,
  `statut_zone_commercial` enum('actif','inactif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_zone_commercial`),
  UNIQUE KEY `uq_enseignant_matiere` (`commercial_code`,`zone_code`,`etablissement_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_pack_total_articles`
--
DROP TABLE IF EXISTS `vue_pack_total_articles`;

DROP VIEW IF EXISTS `vue_pack_total_articles`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_pack_total_articles`  AS SELECT `p`.`id_pack` AS `id_pack`, `p`.`code_pack` AS `code_pack`, `p`.`libelle_pack` AS `libelle_pack`, `p`.`montant_pack` AS `montant_pack`, `p`.`annee_code` AS `annee_code`, `p`.`session_code` AS `session_code`, `p`.`zone_code` AS `zone_code`, `p`.`categorie_pack_code` AS `categorie_pack_code`, `p`.`etablissement_code` AS `etablissement_code`, `p`.`created_at_pack` AS `created_at_pack`, `p`.`updated_at_pack` AS `updated_at_pack`, `p`.`statut_pack` AS `statut_pack`, `p`.`user_code` AS `user_code`, count(`pa`.`id_pack_article`) AS `nombre_article`, sum(`pa`.`quantite_article`) AS `quantite` FROM (`pack_articles` `pa` join `packs` `p` on((`p`.`code_pack` = `pa`.`pack_code`))) GROUP BY `pa`.`pack_code` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
