-- phpMyAdmin SQL Dump
-- version 5.2.1deb1ubuntu1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Čtv 15. úno 2024, 18:29
-- Verze serveru: 8.0.36-0ubuntu0.23.10.1
-- Verze PHP: 8.2.10-2ubuntu1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `ChefsDiary`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `UserRoleType`
--

CREATE TABLE `UserRoleType` (
  `IdUserRoleType` int UNSIGNED NOT NULL,
  `Code` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Value` smallint NOT NULL,
  `Description` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Vypisuji data pro tabulku `UserRoleType`
--

INSERT INTO `UserRoleType` (`IdUserRoleType`, `Code`, `Value`, `Description`) VALUES
(1, 'USER', 2001, 'Uživatel'),
(2, 'EDITOR', 1984, 'Editor'),
(3, 'ADMIN', 5150, 'Administrátor');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `UserRoleType`
--
ALTER TABLE `UserRoleType`
  ADD PRIMARY KEY (`IdUserRoleType`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `UserRoleType`
--
ALTER TABLE `UserRoleType`
  MODIFY `IdUserRoleType` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
