-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Ago 09, 2022 alle 16:32
-- Versione del server: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- Versione PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvignaga`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `COMMENTO`
--

CREATE TABLE `COMMENTO` (
  `id` int(11) NOT NULL,
  `post` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `POST`
--

CREATE TABLE `POST` (
  `title` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(319) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `UTENTE`
--

CREATE TABLE `UTENTE` (
  `username` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `administrator` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `UTENTE`
--

INSERT INTO `UTENTE` (`username`, `password`, `administrator`) VALUES
('user', 'ee11cbb19052e40b07aac0ca060c23ee', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `COMMENTO`
--
ALTER TABLE `COMMENTO`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post` (`post`),
  ADD KEY `user` (`user`);

--
-- Indici per le tabelle `POST`
--
ALTER TABLE `POST`
  ADD PRIMARY KEY (`title`),
  ADD KEY `user` (`user`);

--
-- Indici per le tabelle `UTENTE`
--
ALTER TABLE `UTENTE`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `COMMENTO`
--
ALTER TABLE `COMMENTO`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `COMMENTO`
--
ALTER TABLE `COMMENTO`
  ADD CONSTRAINT `COMMENTO_ibfk_1` FOREIGN KEY (`post`) REFERENCES `POST` (`title`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `COMMENTO_ibfk_2` FOREIGN KEY (`user`) REFERENCES `UTENTE` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `POST`
--
ALTER TABLE `POST`
  ADD CONSTRAINT `POST_ibfk_1` FOREIGN KEY (`user`) REFERENCES `UTENTE` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
