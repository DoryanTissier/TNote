-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 03 juin 2024 à 22:38
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
-- Base de données : `tnote`
--

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `ID_groupe` int(11) NOT NULL,
  `annee` int(11) DEFAULT NULL,
  `effectif` int(11) DEFAULT NULL,
  `lettre_TP` char(1) DEFAULT NULL,
  `numero_TD` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`ID_groupe`, `annee`, `effectif`, `lettre_TP`, `numero_TD`) VALUES
(1, 1, 15, 'a', 1),
(2, 1, 15, 'b', 1),
(3, 1, 10, 'a', 2),
(4, 1, 10, 'b', 2),
(5, 1, 15, 'a', 3),
(6, 1, 15, 'b', 3);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_ressources_prof`
--

CREATE TABLE `liaison_ressources_prof` (
  `id_prof` int(11) NOT NULL,
  `num_ressource` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liaison_ressources_prof`
--

INSERT INTO `liaison_ressources_prof` (`id_prof`, `num_ressource`) VALUES
(1, 11),
(2, 10),
(4, 9),
(5, 8),
(6, 7),
(7, 6),
(9, 5),
(10, 4),
(11, 3),
(12, 2),
(13, 1);

-- --------------------------------------------------------

--
-- Structure de la table `liaison_sae_prof`
--

CREATE TABLE `liaison_sae_prof` (
  `id_prof` int(11) NOT NULL,
  `nom_SAE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liaison_sae_prof`
--

INSERT INTO `liaison_sae_prof` (`id_prof`, `nom_SAE`) VALUES
(1, '101'),
(1, '103'),
(1, '105'),
(1, '201'),
(2, '101'),
(2, '102'),
(2, '105'),
(2, '202'),
(3, '101'),
(3, '102'),
(3, '103'),
(3, '106'),
(3, '203'),
(4, '101'),
(4, '102'),
(4, '104'),
(4, '202'),
(4, '204'),
(5, '104'),
(5, '105'),
(5, '202'),
(6, '102'),
(6, '104'),
(6, '201'),
(6, '204'),
(7, '102'),
(7, '106'),
(7, '203'),
(8, '103'),
(8, '106'),
(8, '201'),
(8, '203'),
(9, '103'),
(9, '105'),
(9, '202'),
(9, '204'),
(10, '103'),
(10, '201'),
(10, '203'),
(11, '106'),
(11, '201'),
(12, '104'),
(12, '106'),
(12, '202'),
(13, '104'),
(13, '105'),
(13, '203');

-- --------------------------------------------------------

--
-- Structure de la table `liaison_sae_ue`
--

CREATE TABLE `liaison_sae_ue` (
  `nom_SAE` varchar(255) NOT NULL,
  `nom_UE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `liaison_sae_ue`
--

INSERT INTO `liaison_sae_ue` (`nom_SAE`, `nom_UE`) VALUES
('101', 'Comprendre'),
('102', 'Concevoir'),
('103', 'Entrepreneur'),
('104', 'Exprimer'),
('105', 'Développer'),
('106', 'Comprendre'),
('201', 'Concevoir'),
('202', 'Entrepreneur'),
('203', 'Exprimer'),
('204', 'Développer');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `note` float DEFAULT NULL,
  `id_evaluation` int(11) NOT NULL,
  `ID_Etudiants` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`note`, `id_evaluation`, `ID_Etudiants`) VALUES
(15, 1, 1),
(14.5, 1, 2),
(16, 1, 3),
(13, 1, 4),
(NULL, 1, 5),
(12, 1, 6),
(11.5, 1, 7),
(18, 1, 8),
(16.5, 1, 9),
(14, 1, 10),
(13.5, 1, 11),
(17, 1, 12),
(15.5, 1, 13),
(19, 1, 14),
(10, 1, 15),
(18, 1, 16),
(14, 1, 17),
(15.5, 1, 18),
(16.5, 1, 19),
(NULL, 1, 20),
(13, 1, 21),
(12.5, 1, 22),
(11, 1, 23),
(17, 1, 24),
(15, 1, 25),
(14.5, 1, 26),
(16, 1, 27),
(13.5, 1, 28),
(17.5, 1, 29),
(19, 1, 30),
(18.5, 1, 31),
(16, 1, 32),
(14, 1, 33),
(15, 1, 34),
(NULL, 1, 35),
(12, 1, 36),
(11.5, 1, 37),
(10.5, 1, 38),
(18, 1, 39),
(16.5, 1, 40),
(14.5, 1, 41),
(15.5, 1, 42),
(13, 1, 43),
(17, 1, 44),
(18.5, 1, 45),
(17, 1, 46),
(15, 1, 47),
(14.5, 1, 48),
(16, 1, 49),
(13.5, 1, 50),
(NULL, 1, 51),
(12, 1, 52),
(11.5, 1, 53),
(10.5, 1, 54),
(18, 1, 55),
(16.5, 1, 56),
(14.5, 1, 57),
(15.5, 1, 58),
(13, 1, 59),
(17, 1, 60),
(18.5, 1, 61),
(16, 1, 62),
(14, 1, 63),
(15, 1, 64),
(NULL, 1, 65),
(12, 1, 66),
(11.5, 1, 67),
(10.5, 1, 68),
(18, 1, 69),
(16.5, 1, 70),
(14.5, 1, 71),
(15.5, 1, 72),
(13, 1, 73),
(17, 1, 74),
(18.5, 1, 75),
(17, 1, 76),
(15, 1, 77),
(14.5, 1, 78),
(16, 1, 79),
(13.5, 1, 80),
(14, 2, 1),
(16.5, 2, 2),
(18, 2, 3),
(15, 2, 4),
(12, 2, 5),
(13.5, 2, 6),
(11, 2, 7),
(17, 2, 8),
(14.5, 2, 9),
(16, 2, 10),
(18.5, 2, 11),
(19, 2, 12),
(17.5, 2, 13),
(13, 2, 14),
(NULL, 2, 15),
(16, 2, 16),
(14, 2, 17),
(15, 2, 18),
(13.5, 2, 19),
(12, 2, 20),
(11.5, 2, 21),
(10.5, 2, 22),
(18, 2, 23),
(16.5, 2, 24),
(14.5, 2, 25),
(15.5, 2, 26),
(13, 2, 27),
(17, 2, 28),
(18.5, 2, 29),
(16, 2, 30),
(NULL, 2, 31),
(14, 2, 32),
(16.5, 2, 33),
(18, 2, 34),
(15, 2, 35),
(12, 2, 36),
(13.5, 2, 37),
(11, 2, 38),
(17, 2, 39),
(14.5, 2, 40),
(16, 2, 41),
(18.5, 2, 42),
(19, 2, 43),
(17.5, 2, 44),
(13, 2, 45),
(NULL, 2, 46),
(16, 2, 47),
(14, 2, 48),
(15, 2, 49),
(13.5, 2, 50),
(12, 2, 51),
(11.5, 2, 52),
(10.5, 2, 53),
(18, 2, 54),
(16.5, 2, 55),
(14.5, 2, 56),
(15.5, 2, 57),
(13, 2, 58),
(17, 2, 59),
(18.5, 2, 60),
(16, 2, 61),
(NULL, 2, 62),
(14, 2, 63),
(16.5, 2, 64),
(18, 2, 65),
(15, 2, 66),
(12, 2, 67),
(13.5, 2, 68),
(11, 2, 69),
(17, 2, 70),
(14.5, 2, 71),
(16, 2, 72),
(18.5, 2, 73),
(19, 2, 74),
(17.5, 2, 75),
(13, 2, 76),
(NULL, 2, 77),
(16, 2, 78),
(14, 2, 79),
(15, 2, 80),
(14, 3, 1),
(12.5, 3, 2),
(11, 3, 3),
(9.5, 3, 4),
(16, 3, 5),
(15.5, 3, 6),
(13, 3, 7),
(17.5, 3, 8),
(18, 3, 9),
(16.5, 3, 10),
(14.5, 3, 11),
(13, 3, 12),
(15.5, 3, 13),
(17, 3, 14),
(18, 3, 15),
(10.5, 3, 16),
(9, 3, 17),
(13.5, 3, 18),
(12, 3, 19),
(11, 3, 20),
(14, 3, 21),
(15.5, 3, 22),
(10, 3, 23),
(11.5, 3, 24),
(9.5, 3, 25),
(16.5, 3, 26),
(15, 3, 27),
(13, 3, 28),
(17, 3, 29),
(18.5, 3, 30),
(16, 3, 31),
(14.5, 3, 32),
(13, 3, 33),
(15.5, 3, 34),
(17, 3, 35),
(18, 3, 36),
(10.5, 3, 37),
(9, 3, 38),
(13.5, 3, 39),
(12, 3, 40),
(11, 3, 41),
(14, 3, 42),
(15.5, 3, 43),
(10, 3, 44),
(11.5, 3, 45),
(9.5, 3, 46),
(16.5, 3, 47),
(15, 3, 48),
(13, 3, 49),
(17, 3, 50),
(18.5, 3, 51),
(16, 3, 52),
(14.5, 3, 53),
(13, 3, 54),
(15.5, 3, 55),
(17, 3, 56),
(18, 3, 57),
(10.5, 3, 58),
(9, 3, 59),
(13.5, 3, 60),
(12, 3, 61),
(11, 3, 62),
(14, 3, 63),
(15.5, 3, 64),
(10, 3, 65),
(11.5, 3, 66),
(9.5, 3, 67),
(16.5, 3, 68),
(15, 3, 69),
(13, 3, 70),
(17, 3, 71),
(18.5, 3, 72),
(16, 3, 73),
(14.5, 3, 74),
(13, 3, 75),
(15.5, 3, 76),
(17, 3, 77),
(18, 3, 78),
(10.5, 3, 79),
(9, 3, 80),
(16, 4, 1),
(14, 4, 2),
(15, 4, 3),
(13.5, 4, 4),
(NULL, 4, 5),
(12, 4, 6),
(11.5, 4, 7),
(10.5, 4, 8),
(18, 4, 9),
(16.5, 4, 10),
(14.5, 4, 11),
(15.5, 4, 12),
(13, 4, 13),
(17, 4, 14),
(18.5, 4, 15),
(8, 4, 16),
(9, 4, 17),
(7.5, 4, 18),
(6, 4, 19),
(5.5, 4, 20),
(4, 4, 21),
(1, 4, 22),
(2.5, 4, 23),
(3.5, 4, 24),
(1.5, 4, 25),
(10, 4, 26),
(8.5, 4, 27),
(9.5, 4, 28),
(7, 4, 29),
(6.5, 4, 30),
(11, 4, 31),
(12.5, 4, 32),
(2, 4, 33),
(4.5, 4, 34),
(3, 4, 35),
(15, 4, 36),
(13.5, 4, 37),
(14.5, 4, 38),
(16.5, 4, 39),
(18, 4, 40),
(17.5, 4, 41),
(19.5, 4, 42),
(20, 4, 43),
(19, 4, 44),
(1.5, 4, 45),
(2, 4, 46),
(3, 4, 47),
(1, 4, 48),
(4, 4, 49),
(5.5, 4, 50),
(6, 4, 51),
(7, 4, 52),
(8.5, 4, 53),
(9.5, 4, 54),
(10, 4, 55),
(11.5, 4, 56),
(12.5, 4, 57),
(13.5, 4, 58),
(14, 4, 59),
(15.5, 4, 60),
(16, 4, 61),
(17, 4, 62),
(18, 4, 63),
(19, 4, 64),
(20, 4, 65),
(18.5, 4, 66),
(19.5, 4, 67),
(20, 4, 68),
(10, 4, 69),
(9, 4, 70),
(8, 4, 71),
(7, 4, 72),
(6, 4, 73),
(5, 4, 74),
(4, 4, 75),
(3, 4, 76),
(2, 4, 77),
(1, 4, 78),
(NULL, 4, 79),
(15, 4, 80),
(18.5, 5, 1),
(17, 5, 2),
(16.5, 5, 3),
(14, 5, 4),
(12, 5, 5),
(11.5, 5, 6),
(10, 5, 7),
(9.5, 5, 8),
(8, 5, 9),
(7.5, 5, 10),
(6, 5, 11),
(5.5, 5, 12),
(4, 5, 13),
(3.5, 5, 14),
(2, 5, 15),
(1, 5, 16),
(19, 5, 17),
(18, 5, 18),
(17.5, 5, 19),
(16, 5, 20),
(15.5, 5, 21),
(14, 5, 22),
(13.5, 5, 23),
(12, 5, 24),
(11.5, 5, 25),
(10, 5, 26),
(9.5, 5, 27),
(8, 5, 28),
(7.5, 5, 29),
(6, 5, 30),
(5.5, 5, 31),
(4, 5, 32),
(3.5, 5, 33),
(2, 5, 34),
(1, 5, 35),
(19, 5, 36),
(18, 5, 37),
(17.5, 5, 38),
(16, 5, 39),
(15.5, 5, 40),
(14, 5, 41),
(13.5, 5, 42),
(12, 5, 43),
(11.5, 5, 44),
(10, 5, 45),
(9.5, 5, 46),
(8, 5, 47),
(7.5, 5, 48),
(6, 5, 49),
(5.5, 5, 50),
(4, 5, 51),
(3.5, 5, 52),
(2, 5, 53),
(1, 5, 54),
(19, 5, 55),
(18, 5, 56),
(17.5, 5, 57),
(16, 5, 58),
(15.5, 5, 59),
(14, 5, 60),
(13.5, 5, 61),
(12, 5, 62),
(11.5, 5, 63),
(10, 5, 64),
(9.5, 5, 65),
(8, 5, 66),
(7.5, 5, 67),
(6, 5, 68),
(5.5, 5, 69),
(4, 5, 70),
(3.5, 5, 71),
(2, 5, 72),
(1, 5, 73),
(19, 5, 74),
(18, 5, 75),
(17.5, 5, 76),
(16, 5, 77),
(15.5, 5, 78),
(14, 5, 79),
(13.5, 5, 80);

-- --------------------------------------------------------

--
-- Structure de la table `profil_admin`
--

CREATE TABLE `profil_admin` (
  `ID_Admin` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `mot_de_Passe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profil_admin`
--

INSERT INTO `profil_admin` (`ID_Admin`, `nom`, `prenom`, `mot_de_Passe`) VALUES
(1, 'Admin1', 'Alice', 'admin_pwd1'),
(2, 'Admin2', 'Bob', 'admin_pwd2'),
(3, 'Admin3', 'Charlie', 'admin_pwd3'),
(4, 'Admin4', 'David', 'admin_pwd4'),
(5, 'Admin5', 'Eve', 'admin_pwd5');

-- --------------------------------------------------------

--
-- Structure de la table `profil_etudiant`
--

CREATE TABLE `profil_etudiant` (
  `ID_Etudiants` int(11) NOT NULL,
  `mot_de_Passe` varchar(255) DEFAULT NULL,
  `ID_groupe` int(11) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `annee_univ` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profil_etudiant`
--

INSERT INTO `profil_etudiant` (`ID_Etudiants`, `mot_de_Passe`, `ID_groupe`, `nom`, `prenom`, `annee_univ`) VALUES
(1, 'pwd1', 1, 'Durand', 'Alice', '2023-2024'),
(2, 'pwd2', 1, 'Martin', 'Bob', '2023-2024'),
(3, 'pwd3', 1, 'Bernard', 'Charlie', '2023-2024'),
(4, 'pwd4', 1, 'Dubois', 'Diana', '2023-2024'),
(5, 'pwd5', 1, 'Thomas', 'Eve', '2023-2024'),
(6, 'pwd6', 1, 'Robert', 'Frank', '2023-2024'),
(7, 'pwd7', 1, 'Richard', 'Grace', '2023-2024'),
(8, 'pwd8', 1, 'Petit', 'Henry', '2023-2024'),
(9, 'pwd9', 1, 'Leroy', 'Ivy', '2023-2024'),
(10, 'pwd10', 1, 'Moreau', 'Jack', '2023-2024'),
(11, 'pwd11', 1, 'Simon', 'Kelly', '2023-2024'),
(12, 'pwd12', 1, 'Laurent', 'Liam', '2023-2024'),
(13, 'pwd13', 1, 'Lefevre', 'Mia', '2023-2024'),
(14, 'pwd14', 1, 'Michel', 'Noah', '2023-2024'),
(15, 'pwd15', 1, 'Garcia', 'Olivia', '2023-2024'),
(16, 'pwd16', 2, 'Lopez', 'Paul', '2023-2024'),
(17, 'pwd17', 2, 'Muller', 'Quinn', '2023-2024'),
(18, 'pwd18', 2, 'Gonzalez', 'Ryan', '2023-2024'),
(19, 'pwd19', 2, 'Perez', 'Sara', '2023-2024'),
(20, 'pwd20', 2, 'Sanchez', 'Tom', '2023-2024'),
(21, 'pwd21', 2, 'Roux', 'Uma', '2023-2024'),
(22, 'pwd22', 2, 'Brun', 'Victor', '2023-2024'),
(23, 'pwd23', 2, 'Blanc', 'Wendy', '2023-2024'),
(24, 'pwd24', 2, 'Guerin', 'Xavier', '2023-2024'),
(25, 'pwd25', 2, 'Boyer', 'Yasmine', '2023-2024'),
(26, 'pwd26', 2, 'Dumont', 'Zach', '2023-2024'),
(27, 'pwd27', 2, 'Fontaine', 'Anna', '2023-2024'),
(28, 'pwd28', 2, 'Chevalier', 'Ben', '2023-2024'),
(29, 'pwd29', 2, 'Robin', 'Cathy', '2023-2024'),
(30, 'pwd30', 2, 'Masson', 'David', '2023-2024'),
(31, 'pwd31', 3, 'Girard', 'Eli', '2023-2024'),
(32, 'pwd32', 3, 'Andre', 'Fay', '2023-2024'),
(33, 'pwd33', 3, 'Mercier', 'Gus', '2023-2024'),
(34, 'pwd34', 3, 'Dupuis', 'Hana', '2023-2024'),
(35, 'pwd35', 3, 'Fournier', 'Ian', '2023-2024'),
(36, 'pwd36', 3, 'Lucas', 'Jill', '2023-2024'),
(37, 'pwd37', 3, 'Perez', 'Ken', '2023-2024'),
(38, 'pwd38', 3, 'Martinez', 'Lara', '2023-2024'),
(39, 'pwd39', 3, 'Lemoine', 'Mason', '2023-2024'),
(40, 'pwd40', 3, 'Jacquet', 'Nina', '2023-2024'),
(41, 'pwd41', 4, 'Rodriguez', 'Omar', '2023-2024'),
(42, 'pwd42', 4, 'Morin', 'Pia', '2023-2024'),
(43, 'pwd43', 4, 'Nguyen', 'Quincy', '2023-2024'),
(44, 'pwd44', 4, 'Morel', 'Rita', '2023-2024'),
(45, 'pwd45', 4, 'Renaud', 'Sam', '2023-2024'),
(46, 'pwd46', 4, 'Renard', 'Tina', '2023-2024'),
(47, 'pwd47', 4, 'Marchand', 'Uri', '2023-2024'),
(48, 'pwd48', 4, 'Sauvage', 'Vera', '2023-2024'),
(49, 'pwd49', 4, 'Prevost', 'Will', '2023-2024'),
(50, 'pwd50', 4, 'Lemoine', 'Xena', '2023-2024'),
(51, 'pwd51', 5, 'Mathieu', 'Yann', '2023-2024'),
(52, 'pwd52', 5, 'Lemoine', 'Zara', '2023-2024'),
(53, 'pwd53', 5, 'Clement', 'Adam', '2023-2024'),
(54, 'pwd54', 5, 'Pierre', 'Bella', '2023-2024'),
(55, 'pwd55', 5, 'Nicolas', 'Caleb', '2023-2024'),
(56, 'pwd56', 5, 'Arnaud', 'Dana', '2023-2024'),
(57, 'pwd57', 5, 'Marchal', 'Evan', '2023-2024'),
(58, 'pwd58', 5, 'Caron', 'Fiona', '2023-2024'),
(59, 'pwd59', 5, 'Pires', 'George', '2023-2024'),
(60, 'pwd60', 5, 'Lemoine', 'Holly', '2023-2024'),
(61, 'pwd61', 5, 'Joly', 'Isaac', '2023-2024'),
(62, 'pwd62', 5, 'Perret', 'Jade', '2023-2024'),
(63, 'pwd63', 5, 'Leblanc', 'Kyle', '2023-2024'),
(64, 'pwd64', 5, 'Vidal', 'Lila', '2023-2024'),
(65, 'pwd65', 5, 'Giraud', 'Manny', '2023-2024'),
(66, 'pwd66', 6, 'Maillard', 'Nancy', '2023-2024'),
(67, 'pwd67', 6, 'Rossi', 'Owen', '2023-2024'),
(68, 'pwd68', 6, 'Baron', 'Paula', '2023-2024'),
(69, 'pwd69', 6, 'Pires', 'Quentin', '2023-2024'),
(70, 'pwd70', 6, 'Gomez', 'Rene', '2023-2024'),
(71, 'pwd71', 6, 'Berger', 'Sophie', '2023-2024'),
(72, 'pwd72', 6, 'Benoit', 'Theo', '2023-2024'),
(73, 'pwd73', 6, 'Pascal', 'Ursula', '2023-2024'),
(74, 'pwd74', 6, 'Vallet', 'Victor', '2023-2024'),
(75, 'pwd75', 6, 'Chauvin', 'Wanda', '2023-2024'),
(76, 'pwd76', 6, 'Delcourt', 'Xavier', '2023-2024'),
(77, 'pwd77', 6, 'Besson', 'Yara', '2023-2024'),
(78, 'pwd78', 6, 'Noel', 'Zane', '2023-2024'),
(79, 'pwd79', 6, 'Guillet', 'Amy', '2023-2024'),
(80, 'pwd80', 6, 'Pottier', 'Blake', '2023-2024');

-- --------------------------------------------------------

--
-- Structure de la table `profil_prof`
--

CREATE TABLE `profil_prof` (
  `ID_prof` int(11) NOT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profil_prof`
--

INSERT INTO `profil_prof` (`ID_prof`, `mot_de_passe`, `nom`, `prenom`) VALUES
(1, 'PassNadia123', 'AL SALTI', 'Nadia'),
(2, 'PassFatima456', 'AMGHAR', 'Fatima'),
(3, 'PassThierry789', 'CARON', 'Thierry'),
(4, 'PassAurelien123', 'CHAMBON', 'Aurélien'),
(5, 'PassJulien456', 'GINESTE', 'Julien'),
(6, 'PassEric789', 'JACOBEE', 'Eric'),
(7, 'PassReda123', 'LAROUSSI', 'Reda'),
(8, 'PassPatrick456', 'LOUISE ALEXANDRINE', 'Patrick'),
(9, 'PassAbdel789', 'MENAA', 'Abdel'),
(10, 'PassIsabelle123', 'MORVAN', 'Isabelle'),
(11, 'PassAbderrezak456', 'RACHEDI', 'Abderrezak'),
(12, 'PassClément789', 'SIGALAS', 'Clément'),
(13, 'PassFares123', 'ZAIDI', 'Fares');

-- --------------------------------------------------------

--
-- Structure de la table `ressources`
--

CREATE TABLE `ressources` (
  `num_ressource` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `coefficient` float DEFAULT NULL,
  `nom_UE` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ressources`
--

INSERT INTO `ressources` (`num_ressource`, `nom`, `coefficient`, `nom_UE`) VALUES
(1, 'Hébergement', 1, 'Développer'),
(2, 'Culture Numérique', 1, 'Comprendre'),
(3, 'Développement web', 1, 'Développer'),
(4, 'Mathématiques', 1, 'Concevoir'),
(5, 'Conception 3D', 1, 'Concevoir'),
(6, 'Intégration', 1, 'Développer'),
(7, 'Expression com. et rhétorique', 1, 'Exprimer'),
(8, 'Culture artistique', 1, 'Exprimer'),
(9, 'Python', 1, 'Développer'),
(10, 'PPP', 1, 'Entrepreneur'),
(11, 'Anglais', 1, 'Exprimer');

-- --------------------------------------------------------

--
-- Structure de la table `sae`
--

CREATE TABLE `sae` (
  `nom_SAE` varchar(255) NOT NULL,
  `coefficient` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sae`
--

INSERT INTO `sae` (`nom_SAE`, `coefficient`) VALUES
('101', 1),
('102', 1),
('103', 1),
('104', 1),
('105', 1),
('106', 1),
('201', 1),
('202', 1),
('203', 1),
('204', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `nom_UE` varchar(255) NOT NULL,
  `coefficient` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`nom_UE`, `coefficient`) VALUES
('Comprendre', 1),
('Concevoir', 1),
('Développer', 1),
('Entrepreneur', 1),
('Exprimer', 1);

-- --------------------------------------------------------

--
-- Structure de la table `évalutation`
--

CREATE TABLE `évalutation` (
  `id_evaluation` int(11) NOT NULL,
  `type_evaluation` varchar(255) DEFAULT NULL,
  `coefficient` float DEFAULT NULL,
  `nom_evaluation` varchar(255) DEFAULT NULL,
  `date_jour` date DEFAULT NULL,
  `ID_prof` int(11) DEFAULT NULL,
  `num_ressource` int(11) DEFAULT NULL,
  `nom_SAE` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `évalutation`
--

INSERT INTO `évalutation` (`id_evaluation`, `type_evaluation`, `coefficient`, `nom_evaluation`, `date_jour`, `ID_prof`, `num_ressource`, `nom_SAE`) VALUES
(1, 'SAE', 1.5, 'Evaluation SAE 101', '2023-09-15', 1, NULL, '101'),
(2, 'Ressource', 2, 'Evaluation Ressource Hébergement', '2023-10-10', 13, 1, NULL),
(3, 'SAE', 1, 'Evaluation SAE 102', '2023-11-20', 2, NULL, '102'),
(4, 'Ressource', 1.8, 'Evaluation Ressource Culture Numérique', '2023-12-05', 12, 2, NULL),
(5, 'SAE', 2.5, 'Evaluation SAE 103', '2024-01-10', 3, NULL, '103');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`ID_groupe`);

--
-- Index pour la table `liaison_ressources_prof`
--
ALTER TABLE `liaison_ressources_prof`
  ADD PRIMARY KEY (`id_prof`,`num_ressource`),
  ADD KEY `num_ressource` (`num_ressource`);

--
-- Index pour la table `liaison_sae_prof`
--
ALTER TABLE `liaison_sae_prof`
  ADD PRIMARY KEY (`id_prof`,`nom_SAE`),
  ADD KEY `nom_SAE` (`nom_SAE`);

--
-- Index pour la table `liaison_sae_ue`
--
ALTER TABLE `liaison_sae_ue`
  ADD PRIMARY KEY (`nom_SAE`,`nom_UE`),
  ADD KEY `nom_UE` (`nom_UE`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id_evaluation`,`ID_Etudiants`),
  ADD KEY `ID_Etudiants` (`ID_Etudiants`);

--
-- Index pour la table `profil_admin`
--
ALTER TABLE `profil_admin`
  ADD PRIMARY KEY (`ID_Admin`);

--
-- Index pour la table `profil_etudiant`
--
ALTER TABLE `profil_etudiant`
  ADD PRIMARY KEY (`ID_Etudiants`),
  ADD KEY `ID_groupe` (`ID_groupe`);

--
-- Index pour la table `profil_prof`
--
ALTER TABLE `profil_prof`
  ADD PRIMARY KEY (`ID_prof`);

--
-- Index pour la table `ressources`
--
ALTER TABLE `ressources`
  ADD PRIMARY KEY (`num_ressource`),
  ADD KEY `nom_UE` (`nom_UE`);

--
-- Index pour la table `sae`
--
ALTER TABLE `sae`
  ADD PRIMARY KEY (`nom_SAE`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`nom_UE`);

--
-- Index pour la table `évalutation`
--
ALTER TABLE `évalutation`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `ID_prof` (`ID_prof`),
  ADD KEY `num_ressource` (`num_ressource`),
  ADD KEY `nom_SAE` (`nom_SAE`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `ID_groupe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `profil_admin`
--
ALTER TABLE `profil_admin`
  MODIFY `ID_Admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `profil_etudiant`
--
ALTER TABLE `profil_etudiant`
  MODIFY `ID_Etudiants` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `profil_prof`
--
ALTER TABLE `profil_prof`
  MODIFY `ID_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `ressources`
--
ALTER TABLE `ressources`
  MODIFY `num_ressource` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `évalutation`
--
ALTER TABLE `évalutation`
  MODIFY `id_evaluation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `liaison_ressources_prof`
--
ALTER TABLE `liaison_ressources_prof`
  ADD CONSTRAINT `liaison_ressources_prof_ibfk_1` FOREIGN KEY (`id_prof`) REFERENCES `profil_prof` (`ID_prof`),
  ADD CONSTRAINT `liaison_ressources_prof_ibfk_2` FOREIGN KEY (`num_ressource`) REFERENCES `ressources` (`num_ressource`);

--
-- Contraintes pour la table `liaison_sae_prof`
--
ALTER TABLE `liaison_sae_prof`
  ADD CONSTRAINT `liaison_sae_prof_ibfk_1` FOREIGN KEY (`id_prof`) REFERENCES `profil_prof` (`ID_prof`),
  ADD CONSTRAINT `liaison_sae_prof_ibfk_2` FOREIGN KEY (`nom_SAE`) REFERENCES `sae` (`nom_SAE`);

--
-- Contraintes pour la table `liaison_sae_ue`
--
ALTER TABLE `liaison_sae_ue`
  ADD CONSTRAINT `liaison_sae_ue_ibfk_1` FOREIGN KEY (`nom_SAE`) REFERENCES `sae` (`nom_SAE`),
  ADD CONSTRAINT `liaison_sae_ue_ibfk_2` FOREIGN KEY (`nom_UE`) REFERENCES `ue` (`nom_UE`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_evaluation`) REFERENCES `évalutation` (`id_evaluation`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`ID_Etudiants`) REFERENCES `profil_etudiant` (`ID_Etudiants`);

--
-- Contraintes pour la table `profil_etudiant`
--
ALTER TABLE `profil_etudiant`
  ADD CONSTRAINT `profil_etudiant_ibfk_1` FOREIGN KEY (`ID_groupe`) REFERENCES `groupe` (`ID_groupe`);

--
-- Contraintes pour la table `ressources`
--
ALTER TABLE `ressources`
  ADD CONSTRAINT `ressources_ibfk_1` FOREIGN KEY (`nom_UE`) REFERENCES `ue` (`nom_UE`);

--
-- Contraintes pour la table `évalutation`
--
ALTER TABLE `évalutation`
  ADD CONSTRAINT `évalutation_ibfk_1` FOREIGN KEY (`ID_prof`) REFERENCES `profil_prof` (`ID_prof`),
  ADD CONSTRAINT `évalutation_ibfk_2` FOREIGN KEY (`num_ressource`) REFERENCES `ressources` (`num_ressource`),
  ADD CONSTRAINT `évalutation_ibfk_3` FOREIGN KEY (`nom_SAE`) REFERENCES `sae` (`nom_SAE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
