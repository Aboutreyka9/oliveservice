-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 27 juil. 2026 à 20:57
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
-- Base de données : `db_eicg`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessoires`
--

DROP TABLE IF EXISTS `accessoires`;
CREATE TABLE IF NOT EXISTS `accessoires` (
  `id_accessoire` int NOT NULL AUTO_INCREMENT,
  `code_accessoire` varchar(50) NOT NULL,
  `libelle_accessoire` varchar(255) NOT NULL,
  `statut_accessoire` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_accessoire` timestamp NOT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_accessoire`),
  UNIQUE KEY `code_accessoire` (`code_accessoire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `accessoire_inscription`
--

DROP TABLE IF EXISTS `accessoire_inscription`;
CREATE TABLE IF NOT EXISTS `accessoire_inscription` (
  `id_accessoire_inscription` int NOT NULL AUTO_INCREMENT,
  `code_accessoire_inscription` varchar(50) NOT NULL,
  `inscription_code` varchar(50) NOT NULL,
  `accessoire_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `statut_accessoire_inscription` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_accessoire_inscription` timestamp NOT NULL,
  `etablissement_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_accessoire_inscription`),
  UNIQUE KEY `code_accessoire_inscription` (`code_accessoire_inscription`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Structure de la table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id_classe` int NOT NULL AUTO_INCREMENT,
  `code_classe` varchar(50) NOT NULL,
  `libelle_classe` varchar(150) NOT NULL,
  `capacite_max_classe` int DEFAULT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `niveau_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `statut_classe` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_classe` datetime NOT NULL,
  `updated_at_classe` datetime DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_classe`),
  UNIQUE KEY `code_classe` (`code_classe`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cycles`
--

DROP TABLE IF EXISTS `cycles`;
CREATE TABLE IF NOT EXISTS `cycles` (
  `id_cycle` int NOT NULL AUTO_INCREMENT,
  `code_cycle` varchar(50) NOT NULL,
  `libelle_cycle` varchar(100) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_cycle` datetime NOT NULL,
  `updated_at_cycle` datetime DEFAULT NULL,
  `statut_cycle` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_cycle`),
  UNIQUE KEY `code_cycle` (`code_cycle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `statut_depense` enum('actif','inactif','') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
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
(1, '6insFl', 'hs276kQkxDP7xR2Y', '5wBEh2OfI00frxk8ITPf', 9000, 'Adipisci itaque exer', '1980-04-22 00:00:00', '2026-07-25 00:25:54', '2026-07-26 02:54:52', '0GklBk07waYoLB6pHwY', '', NULL, NULL, '5454544456'),
(2, 'JrghRg3EL', 'ex9NbVtWCp', '5wBEh2OfI00frxk8ITPf', 5000, 'Perferendis sed iste', '2011-12-16 06:26:00', '2026-07-25 00:27:30', NULL, '0GklBk07waYoLB6pHwY', 'actif', '5wBEh2OfI00frxk8ITPf', '2026-07-25 00:27:30', '5454544456'),
(3, 'galafcB9ipUnBcAv9Fc9xvFG0m', 'EZwl982dJ35Y7TFe0dCbiccof87gP', '5wBEh2OfI00frxk8ITPf', 8000, '', '2023-06-25 09:45:29', '2026-07-25 14:51:22', NULL, '0GklBk07waYoLB6pHwY', '', NULL, NULL, '5454544456');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id_document` int NOT NULL AUTO_INCREMENT,
  `libelle_document` varchar(100) NOT NULL,
  `lien_document` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `filiere_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `niveaux_code` varchar(50) NOT NULL,
  `etablisement_code` varchar(100) NOT NULL,
  `statut_document` enum('actif','innactif','','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_document`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dossier_etudiant`
--

DROP TABLE IF EXISTS `dossier_etudiant`;
CREATE TABLE IF NOT EXISTS `dossier_etudiant` (
  `id_dossier_etudiant` int NOT NULL AUTO_INCREMENT,
  `code_dossier_etudiant` varchar(50) NOT NULL,
  `etudiant_code` varchar(50) NOT NULL,
  `libelle_dossier` varchar(255) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `created_at_dossier_etudiant` datetime NOT NULL,
  `updated_at_dossier_etudiant` datetime DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_dossier_etudiant`),
  UNIQUE KEY `code_dossier_etudiant` (`code_dossier_etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `emplois_temps`
--

DROP TABLE IF EXISTS `emplois_temps`;
CREATE TABLE IF NOT EXISTS `emplois_temps` (
  `id_emploi` int NOT NULL AUTO_INCREMENT,
  `code_emploi` varchar(50) NOT NULL,
  `classe_code` varchar(50) NOT NULL,
  `matiere_code` varchar(50) NOT NULL,
  `enseignant_code` varchar(50) NOT NULL,
  `salle_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `created_at_emploi` datetime DEFAULT NULL,
  `updated_at_emploi` datetime DEFAULT NULL,
  `statut_emploi` enum('actif','inactif') DEFAULT 'actif',
  PRIMARY KEY (`id_emploi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

DROP TABLE IF EXISTS `enseignants`;
CREATE TABLE IF NOT EXISTS `enseignants` (
  `id_enseignant` int NOT NULL AUTO_INCREMENT,
  `code_enseignant` varchar(50) NOT NULL,
  `statut_enseignant` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_enseignant` datetime NOT NULL,
  `updated_at_enseignant` datetime DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_enseignant`),
  UNIQUE KEY `code_enseignant` (`code_enseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant_matiere`
--

DROP TABLE IF EXISTS `enseignant_matiere`;
CREATE TABLE IF NOT EXISTS `enseignant_matiere` (
  `id_enseignant_matiere` int NOT NULL AUTO_INCREMENT,
  `enseignant_code` varchar(50) NOT NULL,
  `matiere_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_enseignant_matiere` datetime NOT NULL,
  `updated_at_enseignant_matiere` datetime DEFAULT NULL,
  `statut_enseignant_matiere` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_enseignant_matiere`),
  UNIQUE KEY `uq_enseignant_matiere` (`enseignant_code`,`matiere_code`,`etablissement_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Structure de la table `etudiants`
--

DROP TABLE IF EXISTS `etudiants`;
CREATE TABLE IF NOT EXISTS `etudiants` (
  `id_etudiant` int NOT NULL AUTO_INCREMENT,
  `code_etudiant` varchar(50) NOT NULL,
  `matricule_etudiant` varchar(50) NOT NULL,
  `nom_etudiant` varchar(100) NOT NULL,
  `prenom_etudiant` varchar(255) NOT NULL,
  `date_naissance_etudiant` date DEFAULT NULL,
  `lieu_naissance_etudiant` varchar(255) DEFAULT NULL,
  `sexe_etudiant` varchar(25) DEFAULT NULL,
  `nationalite_etudiant` varchar(100) DEFAULT NULL,
  `lieu_residence_etudiant` text,
  `telephone_etudiant` varchar(50) DEFAULT NULL,
  `email_etudiant` varchar(150) DEFAULT NULL,
  `photo_etudiant` varchar(255) DEFAULT NULL,
  `password_etudiant` varchar(255) DEFAULT NULL,
  `numero_cni` varchar(50) DEFAULT NULL,
  `statut_etudiant` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_etudiant` datetime NOT NULL,
  `updated_at_etudiant` datetime DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_etudiant`),
  UNIQUE KEY `code_etudiant` (`code_etudiant`),
  UNIQUE KEY `matricule_etudiant` (`matricule_etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre_evenement` varchar(255) NOT NULL,
  `image_evenement` varchar(255) DEFAULT NULL,
  `description_evenement` text,
  `is_principal_evenement` tinyint(1) DEFAULT '0',
  `date_creation_evenement` datetime DEFAULT NULL,
  `date_modification_evenement` datetime DEFAULT NULL,
  `statut_evenement` enum('actif','innactif') DEFAULT 'actif',
  PRIMARY KEY (`id_evenement`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

DROP TABLE IF EXISTS `filieres`;
CREATE TABLE IF NOT EXISTS `filieres` (
  `id_filiere` int NOT NULL AUTO_INCREMENT,
  `code_filiere` varchar(50) NOT NULL,
  `libelle_filiere` varchar(150) NOT NULL,
  `description_filiere` text,
  `etablissement_code` varchar(50) NOT NULL,
  `cycle_code` varchar(50) NOT NULL,
  `created_at_filiere` datetime NOT NULL,
  `updated_at_filiere` datetime DEFAULT NULL,
  `statut_filiere` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_filiere`),
  UNIQUE KEY `code_filiere` (`code_filiere`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `etudiant_code` varchar(50) NOT NULL,
  `classe_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  `created_at_inscription` datetime DEFAULT NULL,
  `updated_at_inscription` datetime DEFAULT NULL,
  `montant_scolarite_inscription` decimal(10,2) DEFAULT '0.00',
  `statut_inscription` enum('valide','solde','annule') DEFAULT 'valide',
  PRIMARY KEY (`id_inscription`),
  UNIQUE KEY `uq_inscription` (`etudiant_code`,`annee_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id_matiere` int NOT NULL AUTO_INCREMENT,
  `code_matiere` varchar(30) NOT NULL,
  `libelle_matiere` varchar(150) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  `created_at_matiere` datetime NOT NULL,
  `updated_at_matiere` datetime DEFAULT NULL,
  `statut_matiere` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_matiere`),
  UNIQUE KEY `code_matiere` (`code_matiere`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `objet_message` varchar(255) NOT NULL,
  `description_message` text NOT NULL,
  `statut_message` enum('en_attente','envoye','vue','archive') DEFAULT 'en_attente',
  `created_at_message` datetime DEFAULT NULL,
  `update_at_message` datetime DEFAULT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

DROP TABLE IF EXISTS `niveaux`;
CREATE TABLE IF NOT EXISTS `niveaux` (
  `id_niveau` int NOT NULL AUTO_INCREMENT,
  `code_niveau` varchar(50) NOT NULL,
  `libelle_niveau` varchar(100) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `filiere_code` varchar(50) NOT NULL,
  `created_at_niveau` datetime NOT NULL,
  `updated_at_niveau` datetime DEFAULT NULL,
  `statut_niveau` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_niveau`),
  UNIQUE KEY `uq_niveau_filiere` (`code_niveau`,`filiere_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `code_note` varchar(50) NOT NULL,
  `valeur_note` decimal(5,2) NOT NULL,
  `type_evaluation_code` varchar(50) NOT NULL,
  `observations` text,
  `inscription_code` varchar(50) NOT NULL,
  `matiere_code` varchar(50) NOT NULL,
  `semestre_code` varchar(50) NOT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `created_at_note` datetime NOT NULL,
  `updated_at_note` datetime DEFAULT NULL,
  `statut_note` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id_note`),
  UNIQUE KEY `code_note` (`code_note`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Structure de la table `parents`
--

DROP TABLE IF EXISTS `parents`;
CREATE TABLE IF NOT EXISTS `parents` (
  `id_parent` int NOT NULL AUTO_INCREMENT,
  `code_parent` varchar(100) NOT NULL,
  `nom_pere` varchar(255) DEFAULT NULL,
  `telephone_pere` varchar(30) DEFAULT NULL,
  `profession_pere` varchar(100) DEFAULT NULL,
  `nom_mere` varchar(255) DEFAULT NULL,
  `telephone_mere` varchar(30) DEFAULT NULL,
  `profession_mere` varchar(100) DEFAULT NULL,
  `nom_tuteur` varchar(255) DEFAULT NULL,
  `telephone_tuteur` varchar(30) DEFAULT NULL,
  `created_at_parent` datetime NOT NULL,
  `updated_at_parent` datetime DEFAULT NULL,
  `etudiant_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_parent`)
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `libelle_role`, `code_role`, `module`, `groupe`, `statut_role`, `description`) VALUES
(1, 'ADMIN_H', 'ga1', 'ADMIN', 'GADMIN', 'actif', 'SUPPER ADMINISTRATEUR'),
(3, 'DASHBOARD_H ', 'ga3', 'ADMIN', 'GADMIN', 'actif', NULL),
(5, 'COMPTABLE_H ', 'gcom1', 'COMPTABLE', 'GCOMPT', 'actif', NULL),
(7, 'MANAGER_H ', 'gh1', 'HOTEL', 'GHOT', 'actif', NULL),
(8, 'SALAIRE_H ', 'gcom2', 'COMPTABLE', 'GCOMPT', 'actif', NULL),
(9, 'DEPENSE_H ', 'gh2', 'HOTEL', 'GHOT', 'actif', NULL),
(12, 'RECEPTION_H ', 'grecp1', 'RECEPTION', 'GRECP', 'actif', NULL),
(15, 'SUPER', 'sup1', 'SUPER', 'SUPER', 'actif', NULL),
(23, 'PARAMETRE', 'para1', 'PARAMETRE', 'PARA', 'actif', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

DROP TABLE IF EXISTS `salles`;
CREATE TABLE IF NOT EXISTS `salles` (
  `id_salle` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code_salle` varchar(20) NOT NULL,
  `libelle_salle` varchar(100) NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `user_code` varchar(50) NOT NULL,
  `statut_salle` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_salle`),
  UNIQUE KEY `id_salle` (`id_salle`),
  UNIQUE KEY `code_salle` (`code_salle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scolarites`
--

DROP TABLE IF EXISTS `scolarites`;
CREATE TABLE IF NOT EXISTS `scolarites` (
  `id_scolarite` int NOT NULL AUTO_INCREMENT,
  `code_scolarite` varchar(50) NOT NULL,
  `montant_scolarite` decimal(12,2) NOT NULL,
  `niveau_code` varchar(50) DEFAULT NULL,
  `filiere_code` varchar(50) DEFAULT NULL,
  `annee_code` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  `statut_scolarite` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at_scolarite` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at_scolarite` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_scolarite`),
  UNIQUE KEY `code_scolarite` (`code_scolarite`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `semestres`
--

DROP TABLE IF EXISTS `semestres`;
CREATE TABLE IF NOT EXISTS `semestres` (
  `id_semestre` int NOT NULL AUTO_INCREMENT,
  `code_semestre` varchar(50) NOT NULL,
  `libelle_semestre` varchar(50) NOT NULL,
  `statut_semestre` enum('actif','inactif','') NOT NULL,
  `etablissement_code` varchar(50) NOT NULL,
  `annee_code` varchar(50) NOT NULL,
  `date_debut_semestre` date DEFAULT NULL,
  `date_fin_semestre` date DEFAULT NULL,
  `created_at_semestre` datetime NOT NULL,
  `updated_at_semestre` datetime DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_semestre`),
  UNIQUE KEY `uq_semestre` (`code_semestre`,`annee_code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `semestres`
--

INSERT INTO `semestres` (`id_semestre`, `code_semestre`, `libelle_semestre`, `statut_semestre`, `etablissement_code`, `annee_code`, `date_debut_semestre`, `date_fin_semestre`, `created_at_semestre`, `updated_at_semestre`, `user_code`) VALUES
(1, 'Sgg1xRhXjmssV3z1FA19lxngAsR8I', 'SEMESTRE 1', 'actif', '5454544456', '0GklBk07waYoLB6pHwY', '2026-07-23', '2026-07-24', '2026-07-23 16:06:33', NULL, '5wBEh2OfI00frxk8ITPf'),
(2, 'l8rmIqVzNWaRYF6Nb7kuckHC', 'SEMESTRE 2', 'actif', '5454544456', '0GklBk07waYoLB6pHwY', '2026-07-25', '2026-08-27', '2026-07-23 16:07:01', NULL, '5wBEh2OfI00frxk8ITPf'),
(3, 'TwvwmFiQn8hPkv0a4HKjk3IZTMY', 'SEMESTRE 2', 'actif', '5454544456', 'Lv9LWUf7IdxEny', '2026-05-13', '2026-10-02', '2026-07-23 16:08:14', '2026-07-23 21:45:59', '5wBEh2OfI00frxk8ITPf'),
(4, 'R0PCytWZghb0', 'SEMESTRE 2', 'inactif', '5454544456', 'T2POn4rE', '2026-07-07', '2026-08-01', '2026-07-23 16:08:30', NULL, '5wBEh2OfI00frxk8ITPf');

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
(1, '5wBEh2OfI00frxk8ITPf', '54564777165', 'Admin', 'Admin', 'admin@gmail.com', 'Mr', '$2y$10$ik1kUCxvYJcPL2qhdMH.Iur04TxFgoDh8IhvA1vRgeT8Pfn5pl1AG', '(+225) 05 44 56 45 64', NULL, '2026-07-27 11:40:50', NULL, '123', '8875', '5454544456', NULL, '2026-07-15 11:48:46', NULL, 'actif'),
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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_code`, `role_code`, `create_permission`, `edit_permission`, `show_permission`, `delete_permission`) VALUES
(2, '5wBEh2OfI00frxk8ITPf', 'ga1', 1, 1, 1, 1),
(3, '5wBEh2OfI00frxk8ITPf', 'ga3', 1, 1, 1, 1),
(4, '5wBEh2OfI00frxk8ITPf', 'gh1', 1, 1, 1, 0),
(5, '5wBEh2OfI00frxk8ITPf', 'gh2', 1, 0, 1, 0),
(78, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'ga1', 1, 1, 1, 1),
(79, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'ga3', 1, 1, 1, 1),
(86, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'gcom1', 1, 1, 1, 1),
(87, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'gcom2', 1, 1, 1, 1),
(90, 'Kjd35lpOuL9vewm5KM4yT', 'sup1', 1, 1, 1, 1),
(91, 'Kjd35lpOuL9vewm5KM4yT', 'para1', 1, 1, 0, 1),
(92, 'Kjd35lpOuL9vewm5KM4yT', 'grecp1', 0, 1, 1, 0),
(96, 'yhveAqqunh', 'sup1', 1, 1, 1, 1),
(97, 'yhveAqqunh', 'para1', 1, 1, 1, 1),
(100, 'Xq9daapChi', 'sup1', 1, 1, 1, 1),
(102, 'Xq9daapChi', 'para1', 1, 0, 1, 0),
(104, 'Xq9daapChi', 'grecp1', 1, 0, 0, 0),
(106, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'gh1', 1, 1, 1, 1),
(107, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'gh2', 1, 1, 1, 1),
(108, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'grecp1', 1, 1, 1, 1),
(109, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'para1', 1, 1, 1, 1),
(110, 'NoMUxkgt7GNJo7prXxhatXsIVqK5', 'sup1', 1, 1, 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
