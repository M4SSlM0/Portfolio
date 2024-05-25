-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 25, 2024 alle 12:32
-- Versione del server: 10.4.8-MariaDB
-- Versione PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `mipiace`
--

CREATE TABLE `mipiace` (
  `ID` int(11) NOT NULL,
  `FK_ID_Utente` int(11) NOT NULL,
  `FK_ID_Progetto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `mipiace`
--

INSERT INTO `mipiace` (`ID`, `FK_ID_Utente`, `FK_ID_Progetto`) VALUES
(0, 2, 1),
(0, 2, 1),
(0, 2, 1),
(0, 2, 5),
(0, 2, 3),
(0, 6, 16),
(0, 6, 18),
(0, 6, 1),
(0, 7, 1),
(0, 7, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `progetti`
--

INSERT INTO `progetti` (`ID`, `FK_ID_Utente`, `FK_ID_Tipo`, `Descrizione`, `Immagine`, `DataInizio`, `Status`, `DataFine`, `Visibilita`, `ContatoreOre`) VALUES
(1, 1, 1, 'Primo Progetto', '..\\Immagini\\Project\\Stanza Default.jpg', '2024-04-27', 'InCorso', NULL, 0, 0),
(3, 1, 1, 'Terzo Progetto', '../Immagini/Project/Svizzera.png', '2024-03-11', 'Finito', NULL, 0, 0),
(5, 1, 1, 'Quinto Progetto', '../Immagine/Project/Islanda.png', '2024-06-14', 'InCorso', NULL, 0, 123),
(14, 4, 1, 'A-1', '../Images/Projects/6651ba8d35a65.jpeg', '2024-05-25', 'InCorso', '0000-00-00', 0, 1),
(15, 4, 2, 'A-2', '../Images/Projects/6651baaf314d6.png', '2024-05-25', 'InPausa', '0000-00-00', 1, 1),
(16, 4, 3, 'A-3', '../Images/Projects/6651bacfd612f.jpeg', '2024-05-25', 'InPausa', '0000-00-00', 0, 7),
(17, 5, 2, 'B-1', '../Images/Projects/6651bb0f79714.png', '2024-05-25', 'InPausa', '0000-00-00', 0, 11),
(18, 5, 1, 'B-2', '../Images/Projects/6651bb1e4982e.jpeg', '2024-05-25', 'InCorso', '0000-00-00', 0, 1),
(19, 6, 2, 'C-2', '../Images/Projects/6651bbbd490f2.jpeg', '2024-05-25', 'InCorso', '0000-00-00', 0, 1),
(20, 6, 1, 'C-2', '../Images/Projects/6651bbd6f0f0a.png', '2024-05-25', 'InCorso', '0000-00-00', 1, 1),
(21, 6, 2, 'C-3', '../Images/Projects/6651bbea1ab1e.jpeg', '2024-05-25', 'InCorso', '0000-00-00', 0, 1),
(22, 6, 3, 'C-4', '../Images/Projects/6651bc092b7d6.png', '2024-05-25', 'InPausa', '0000-00-00', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipi`
--

CREATE TABLE `tipi` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tipi`
--

INSERT INTO `tipi` (`ID`, `Nome`) VALUES
(1, 'Illustrazione'),
(2, 'Animazion'),
(3, 'Asset');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`ID`, `Email`, `Password`, `NomeUtente`, `Segnalazioni`, `IsBan`, `Admin`, `ContatoreOre`, `ImmagineProfilo`) VALUES
(1, 'Nino@Nino.Nino', 'f008863c9a9d56998571329e5f32277b', 'Nino', 0, 0, 0, 0, ''),
(2, 'Lino@Smeraldo.RZS', '68cac0ef739b94106c6fe4860783cf0c', 'Lino', 0, 0, 0, 0, '../Images/ProfilePictures/pfp2.jpg'),
(3, 'Antonio@Antonio.Antonio', '3dbe00a167653a1aaee01d93e77e730e', 'Antonio', 0, 0, 0, 0, '../Images/ProfilePictures/6651acf1f3d82_download.png'),
(4, 'Utente@Normale.Un', '2a5f4c2397e3a5e93c78418636addcbf', 'Antonino', 0, 0, 0, 0, ''),
(5, 'Utente@Normale.Du', '2a5f4c2397e3a5e93c78418636addcbf', 'Ezio', 0, 0, 0, 0, '../Images/ProfilePictures/6651bb3cbb614_download.png'),
(6, 'Utente@Admin.Un', '2a5f4c2397e3a5e93c78418636addcbf', 'Kratos', 0, 0, 1, 0, ''),
(7, 'Utente@Ban.Un', '2a5f4c2397e3a5e93c78418636addcbf', 'Sdrumox', 0, 1, 0, 0, '');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT per la tabella `tipi`
--
ALTER TABLE `tipi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
