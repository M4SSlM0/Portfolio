-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 15, 2024 alle 16:26
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `3dprojectdb`
--

CREATE DATABASE IF NOT EXISTS `3dprojectdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `3dprojectdb`;

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `ID` int(11) NOT NULL,
  `FK_ID_Utente` int(11) NOT NULL,
  `FK_ID_Progetto` int(11) NOT NULL,
  `Testo` text NOT NULL,
  `Data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `mipiace`
--

CREATE TABLE `mipiace` (
  `ID` int(11) NOT NULL,
  `FK_ID_Utente` int(11) NOT NULL,
  `FK_ID_Progetto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `progetti`
--

CREATE TABLE `progetti` (
  `ID` int(11) NOT NULL,
  `FK_ID_Utente` int(11) NOT NULL,
  `FK_ID_Tipo` int(11) NOT NULL,
  `Descrizione` text NOT NULL,
  `Immagine` text NOT NULL,
  `DataInizio` date NOT NULL,
  `Status` text NOT NULL,
  `DataFine` date DEFAULT NULL,
  `Visibilita` tinyint(1) NOT NULL DEFAULT 0,
  `ContatoreOre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tipi`
--

CREATE TABLE `tipi` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `ID` int(11) NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `NomeUtente` varchar(30) NOT NULL,
  `Segnalazioni` int(11) NOT NULL DEFAULT 0,
  `IsBan` tinyint(1) NOT NULL DEFAULT 0,
  `Admin` tinyint(1) NOT NULL DEFAULT 0,
  `ContatoreOre` int(11) NOT NULL DEFAULT 0,
  `ImmagineProfilo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_ID_Progetto` (`FK_ID_Progetto`),
  ADD KEY `FK_ID_Utente` (`FK_ID_Utente`);

--
-- Indici per le tabelle `mipiace`
--
ALTER TABLE `mipiace`
  ADD KEY `FK_ID_Progetto` (`FK_ID_Progetto`),
  ADD KEY `FK_ID_Utente` (`FK_ID_Utente`);

--
-- Indici per le tabelle `progetti`
--
ALTER TABLE `progetti`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_ID_Tipo` (`FK_ID_Tipo`),
  ADD KEY `FK_ID_Utente` (`FK_ID_Utente`);

--
-- Indici per le tabelle `tipi`
--
ALTER TABLE `tipi`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `commenti`
--
ALTER TABLE `commenti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `progetti`
--
ALTER TABLE `progetti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tipi`
--
ALTER TABLE `tipi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `commenti_ibfk_1` FOREIGN KEY (`FK_ID_Progetto`) REFERENCES `progetti` (`ID`),
  ADD CONSTRAINT `commenti_ibfk_2` FOREIGN KEY (`FK_ID_Utente`) REFERENCES `utenti` (`ID`);

--
-- Limiti per la tabella `mipiace`
--
ALTER TABLE `mipiace`
  ADD CONSTRAINT `mipiace_ibfk_1` FOREIGN KEY (`FK_ID_Progetto`) REFERENCES `progetti` (`ID`),
  ADD CONSTRAINT `mipiace_ibfk_2` FOREIGN KEY (`FK_ID_Utente`) REFERENCES `utenti` (`ID`);

--
-- Limiti per la tabella `progetti`
--
ALTER TABLE `progetti`
  ADD CONSTRAINT `progetti_ibfk_1` FOREIGN KEY (`FK_ID_Tipo`) REFERENCES `tipi` (`ID`),
  ADD CONSTRAINT `progetti_ibfk_2` FOREIGN KEY (`FK_ID_Utente`) REFERENCES `utenti` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
