-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 17 avr. 2025 à 09:32
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ticket_233`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `Id` int NOT NULL,
  `Ticket_id` int DEFAULT NULL,
  `Message` varchar(255) DEFAULT NULL,
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` timestamp NULL DEFAULT NULL,
  `Updated_by` int DEFAULT NULL,
  `Created_by` int DEFAULT NULL,
  `Deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`Id`, `Ticket_id`, `Message`, `Created_at`, `Updated_at`, `Updated_by`, `Created_by`, `Deleted_at`) VALUES
(8, 8, 'test', '2025-04-16 00:22:36', NULL, NULL, 1, NULL),
(9, 8, 'hey !', '2025-04-16 00:22:41', NULL, NULL, 1, NULL),
(10, 9, 'ceci est un test avec un test de taille en écrivant beaucoup ! j\'espère que ça ne dérange personne', '2025-04-16 00:23:31', NULL, NULL, 6, NULL),
(11, 9, 'salut Adrien !', '2025-04-16 00:23:52', NULL, NULL, 1, NULL),
(12, 9, 'tu vas bien ?', '2025-04-16 00:35:19', NULL, NULL, 1, NULL),
(13, 10, 'bonjour', '2025-04-16 18:51:49', NULL, NULL, 1, NULL),
(14, 10, 'salut', '2025-04-16 18:52:00', NULL, NULL, 1, NULL),
(15, 11, 'nico', '2025-04-16 18:52:16', NULL, NULL, 2, NULL),
(16, 11, 'bonjour', '2025-04-16 18:52:21', NULL, NULL, 2, NULL),
(17, 11, 'salut', '2025-04-16 18:52:44', NULL, NULL, 1, NULL),
(18, 11, 'salut', '2025-04-16 18:54:05', NULL, NULL, 1, NULL),
(19, 12, 'j\'aime les bougnoul', '2025-04-17 07:33:48', NULL, NULL, 3, NULL),
(20, 12, 'nique tes morts', '2025-04-17 07:34:15', NULL, NULL, 1, NULL),
(21, 13, 'jean est beau', '2025-04-17 07:45:46', NULL, NULL, 6, NULL),
(22, 13, 'salut', '2025-04-17 09:32:15', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Permissions`
--

CREATE TABLE `Permissions` (
  `Id` int NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Status` varchar(1) NOT NULL DEFAULT 'N',
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` timestamp NULL DEFAULT NULL,
  `Created_by` int DEFAULT NULL,
  `Updated_by` int DEFAULT NULL,
  `Deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Permissions`
--

INSERT INTO `Permissions` (`Id`, `Name`, `Status`, `Created_at`, `Updated_at`, `Created_by`, `Updated_by`, `Deleted_at`) VALUES
(1, 'View Tickets', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(2, 'Create Tickets', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(3, 'Edit Tickets', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(4, 'Delete Tickets', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(5, 'Manage Users', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(6, 'Access Admin Panel', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(7, 'Manage Roles', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL),
(8, 'Assign Permissions', 'Y', '2025-04-08 01:51:00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Permission_Roles`
--

CREATE TABLE `Permission_Roles` (
  `Id` int NOT NULL,
  `Role_id` int DEFAULT NULL,
  `Permission_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Permission_Roles`
--

INSERT INTO `Permission_Roles` (`Id`, `Role_id`, `Permission_id`) VALUES
(11, 1, 1),
(12, 1, 2),
(13, 1, 3),
(14, 1, 4),
(15, 1, 5),
(16, 1, 6),
(17, 1, 7),
(18, 1, 8),
(27, 3, 1),
(28, 3, 2),
(29, 3, 3),
(30, 3, 4),
(31, 3, 6),
(37, 4, 1),
(38, 4, 2),
(39, 4, 3),
(40, 2, 1),
(41, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `Roles`
--

CREATE TABLE `Roles` (
  `Id` int NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Status` varchar(1) NOT NULL DEFAULT 'N',
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` timestamp NULL DEFAULT NULL,
  `Created_by` int DEFAULT NULL,
  `Updated_by` int DEFAULT NULL,
  `Deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Roles`
--

INSERT INTO `Roles` (`Id`, `Name`, `Status`, `Created_at`, `Updated_at`, `Created_by`, `Updated_by`, `Deleted_at`) VALUES
(1, 'Admin', 'Y', '2025-04-07 07:57:22', NULL, NULL, NULL, NULL),
(2, 'Users', 'Y', '2025-04-07 08:26:02', NULL, NULL, NULL, NULL),
(3, 'Dev', 'Y', '2025-04-07 08:26:22', NULL, NULL, NULL, NULL),
(4, 'Helper', 'Y', '2025-04-07 08:26:41', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Ticket`
--

CREATE TABLE `Ticket` (
  `Id` int NOT NULL,
  `Title` varchar(255) NOT NULL,
  `User_id` int DEFAULT NULL,
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` timestamp NULL DEFAULT NULL,
  `Updated_by` int DEFAULT NULL,
  `Created_by` int DEFAULT NULL,
  `Deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Ticket`
--

INSERT INTO `Ticket` (`Id`, `Title`, `User_id`, `Created_at`, `Updated_at`, `Updated_by`, `Created_by`, `Deleted_at`) VALUES
(8, 'test', 1, '2025-04-16 00:22:36', NULL, NULL, 1, NULL),
(9, 'salut', 6, '2025-04-16 00:23:31', NULL, NULL, 6, NULL),
(10, 'bonjour', 1, '2025-04-16 18:51:49', NULL, NULL, 1, NULL),
(11, 'nico', 2, '2025-04-16 18:52:16', NULL, NULL, 2, NULL),
(12, 'bougnoul', 3, '2025-04-17 07:33:48', NULL, NULL, 3, NULL),
(13, 'immigré', 6, '2025-04-17 07:45:46', NULL, NULL, 6, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `Id` int NOT NULL,
  `Role_id` int DEFAULT NULL,
  `Username` varchar(255) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Y',
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Deleted_at` timestamp NULL DEFAULT NULL,
  `Updated_at` timestamp NULL DEFAULT NULL,
  `Created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`Id`, `Role_id`, `Username`, `Firstname`, `Lastname`, `Password`, `mail`, `Image`, `Status`, `Created_at`, `Deleted_at`, `Updated_at`, `Created_by`) VALUES
(1, 1, 'nico', 'nico', 'nico', '$2y$10$aDRmRyyK.qMu2s.UMSj21OfcXHrO1JQpQwPwmWSQ.UGpoGq/KK8s.', 'nico281107@gmail.com', '6800aea221f06.jpg', 'Y', '2025-04-07 18:24:21', NULL, NULL, NULL),
(2, 2, 'ilyas', 'ilyas', 'Ilyas', '$2y$10$uOwDw/yqwcb26xFoVpGM4eFvmdZ6/eDwDVtc0a3AET7rjQRLWTNkK', 'ilyas@gmail.com', NULL, 'Y', '2025-04-07 18:35:23', NULL, NULL, NULL),
(3, 2, 'maxence', 'maxence', 'Maxence', '$2y$10$D5r8O2v3v3wazrr.xN0/futbOYo2WUhq6GCdbSrBvEMNOwWZej9yq', 'maxence@gmail.com', NULL, 'Y', '2025-04-07 19:08:42', NULL, NULL, NULL),
(5, 2, 'max', 'max', 'max', '$2y$10$Edyr0m2Q1xZLyx6jHGIaruV.UQdu7ZbxCwctLPbLLCUo1lXEQNxPi', 'max@gmail.com', NULL, 'N', '2025-04-07 23:40:26', NULL, NULL, NULL),
(6, 2, 'adrien', 'adrien', 'adrien', '$2y$10$DAlTbfMh.wJXyKtMvr17UOFKrf26kR.u3W5Z5dpBustfzDXuxh4sS', 'adrien@gmail.com', '6800b170a5278.jpg', 'Y', '2025-04-15 23:57:31', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Created_by` (`Created_by`),
  ADD KEY `messages_ibfk_1` (`Ticket_id`);

--
-- Index pour la table `Permissions`
--
ALTER TABLE `Permissions`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Index pour la table `Permission_Roles`
--
ALTER TABLE `Permission_Roles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Role_id` (`Role_id`),
  ADD KEY `Permission_id` (`Permission_id`);

--
-- Index pour la table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Index pour la table `Ticket`
--
ALTER TABLE `Ticket`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Created_by` (`Created_by`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Role_id` (`Role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `Permissions`
--
ALTER TABLE `Permissions`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `Permission_Roles`
--
ALTER TABLE `Permission_Roles`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `Ticket`
--
ALTER TABLE `Ticket`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`Ticket_id`) REFERENCES `ticket` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`Created_by`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Permission_Roles`
--
ALTER TABLE `Permission_Roles`
  ADD CONSTRAINT `permission_roles_ibfk_1` FOREIGN KEY (`Role_id`) REFERENCES `Roles` (`Id`),
  ADD CONSTRAINT `permission_roles_ibfk_2` FOREIGN KEY (`Permission_id`) REFERENCES `Permissions` (`Id`);

--
-- Contraintes pour la table `Ticket`
--
ALTER TABLE `Ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Role_id`) REFERENCES `Roles` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
