-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 21 Mai 2020 à 12:26
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `global`
--

-- --------------------------------------------------------

--
-- Structure de la table `profclasse`
--

CREATE TABLE `profclasse` (
  `IdProf` int(11) NOT NULL,
  `nomClasse` varchar(11) NOT NULL,
  `nbrColonne` int(25) NOT NULL DEFAULT '0',
  `nbrLigne` int(25) NOT NULL DEFAULT '0',
  `nbrEleve` int(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `profs`
--

CREATE TABLE `profs` (
  `idProf` int(25) NOT NULL,
  `pseudo` varchar(25) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `mail` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `profs`
--
ALTER TABLE `profs`
  ADD PRIMARY KEY (`idProf`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `profs`
--
ALTER TABLE `profs`
  MODIFY `idProf` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
