-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 04 Avril 2013 à 14:32
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `conges`
--

-- --------------------------------------------------------

--
-- Structure de la table `conge`
--

CREATE TABLE IF NOT EXISTS `conge` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_proposition` smallint(5) unsigned DEFAULT NULL,
  `id_personne` smallint(5) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `mi_debut_journee` tinyint(1) NOT NULL,
  `date_fin` date NOT NULL,
  `mi_fin_journee` tinyint(1) NOT NULL,
  `annee_reference` smallint(4) NOT NULL,
  `nombre_jours` float NOT NULL,
  `id_type_conge` smallint(5) unsigned NOT NULL,
  `ferme` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_proposition` (`id_proposition`),
  KEY `id_personne` (`id_personne`),
  KEY `id_type_conge` (`id_type_conge`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `conge`
--

INSERT INTO `conge` (`id`, `id_proposition`, `id_personne`, `date_debut`, `mi_debut_journee`, `date_fin`, `mi_fin_journee`, `annee_reference`, `nombre_jours`, `id_type_conge`, `ferme`) VALUES
(14, NULL, 5, '2012-10-03', 0, '2012-10-25', 0, 2012, 17, 1, 0),
(15, NULL, 9, '2012-10-11', 1, '2012-10-25', 0, 2012, 10.5, 2, 0),
(16, 1, 6, '2012-10-16', 0, '2012-10-17', 0, 0, 2, 1, 1),
(17, 3, 6, '2012-10-20', 0, '2012-10-21', 0, 0, 0, 1, 1),
(18, 4, 2, '2012-12-10', 1, '2012-12-11', 1, 0, 1.5, 1, 1),
(19, 5, 2, '2012-12-17', 1, '2012-12-18', 1, 0, 1.5, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `entite`
--

CREATE TABLE IF NOT EXISTS `entite` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` char(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `entite`
--

INSERT INTO `entite` (`id`, `libelle`) VALUES
(1, 'ITSP'),
(2, 'CSM'),
(3, 'SS-T');

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

CREATE TABLE IF NOT EXISTS `fonction` (
  `id` smallint(5) unsigned NOT NULL,
  `libelle` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `fonction`
--

INSERT INTO `fonction` (`id`, `libelle`) VALUES
(1, 'Analyste'),
(2, 'Developpeur'),
(3, 'Expert'),
(4, 'Manager');

-- --------------------------------------------------------

--
-- Structure de la table `jours_feries_csm`
--

CREATE TABLE IF NOT EXISTS `jours_feries_csm` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `annee_reference` int(4) NOT NULL,
  `libelle` varchar(250) NOT NULL,
  PRIMARY KEY (`id`,`libelle`,`annee_reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Contenu de la table `jours_feries_csm`
--

INSERT INTO `jours_feries_csm` (`id`, `id_type`, `date_debut`, `annee_reference`, `libelle`) VALUES
(1, 1, '2012-01-01', 2012, 'Nouvel an'),
(2, 1, '2012-01-11', 2012, 'Anniversaire du manifeste de l''indépendance 1944'),
(3, 1, '2012-05-01', 2012, 'Fête du travail'),
(4, 1, '2012-07-30', 2012, 'Fête du trône'),
(5, 1, '2012-08-14', 2012, 'Journée de Oued Ed-Dahab'),
(6, 1, '2012-08-20', 2012, 'Fête de la révolution du roi et du peuple 1953'),
(7, 1, '2012-08-21', 2012, 'Anniversaire de sa majesté le roi Mohammed VI'),
(8, 1, '2012-11-06', 2012, 'Anniversaire de la marche verte'),
(9, 1, '2012-11-18', 2012, 'Fête de l''indépendance'),
(10, 2, '2012-02-05', 2012, 'Naissance du prophète'),
(11, 2, '2012-08-20', 2012, 'Aïd al fitr fin du mois de ramadan'),
(12, 2, '2012-10-26', 2012, 'Aïd al adha fête du sacrifice'),
(13, 2, '2012-11-15', 2012, 'Jour de l''an de l''hégire'),
(14, 1, '2013-01-01', 2013, 'Nouvel an'),
(15, 1, '2013-01-11', 2013, 'Anniversaire du manifeste de l''indépendance 1944'),
(16, 1, '2013-05-01', 2013, 'Fête du travail'),
(17, 1, '2013-07-30', 2013, 'Fête du trône'),
(18, 1, '2013-08-14', 2013, 'Journée de Oued Ed-Dahab'),
(19, 1, '2013-08-20', 2013, 'Fête de la révolution du roi et du peuple 1953'),
(20, 1, '2013-08-21', 2013, 'Anniversaire de sa majesté le roi Mohammed VI'),
(21, 1, '2013-11-06', 2013, 'Anniversaire de la marche verte'),
(22, 1, '2013-11-18', 2013, 'Fête de l''indépendance'),
(23, 2, '2013-01-24', 2013, 'Naissance du prophète'),
(24, 2, '2013-08-08', 2013, 'Aïd al fitr fin du mois de ramadan'),
(25, 2, '2013-10-15', 2013, 'Aïd al adha fête du sacrifice'),
(26, 2, '2013-11-05', 2013, 'Jour de l''an de l''hégire'),
(27, 1, '2014-01-01', 2014, 'Nouvel an'),
(28, 1, '2014-01-11', 2014, 'Anniversaire du manifeste de l''indépendance 1944'),
(29, 1, '2014-05-01', 2014, 'Fête du travail'),
(30, 1, '2014-07-30', 2014, 'Fête du trône'),
(31, 1, '2014-08-14', 2014, 'Journée de Oued Ed-Dahab'),
(32, 1, '2014-08-20', 2014, 'Fête de la révolution du roi et du peuple 1953'),
(33, 1, '2014-08-21', 2014, 'Anniversaire de sa majesté le roi Mohammed VI'),
(34, 1, '2014-11-06', 2014, 'Anniversaire de la marche verte'),
(35, 1, '2014-11-18', 2014, 'Fête de l''indépendance'),
(36, 2, '2014-01-14', 2014, 'Naissance du prophète'),
(37, 2, '2014-07-29', 2014, 'Aïd al fitr fin du mois de ramadan'),
(38, 2, '2014-10-05', 2014, 'Aïd al adha fête du sacrifice'),
(39, 2, '2014-10-25', 2014, 'Jour de l''an de l''hégire'),
(40, 1, '2015-01-01', 2015, 'Nouvel an'),
(41, 1, '2015-01-11', 2015, 'Anniversaire du manifeste de l''indépendance 1944'),
(42, 1, '2015-05-01', 2015, 'Fête du travail'),
(43, 1, '2015-07-30', 2015, 'Fête du trône'),
(44, 1, '2015-08-14', 2015, 'Journée de Oued Ed-Dahab'),
(45, 1, '2015-08-20', 2015, 'Fête de la révolution du roi et du peuple 1953'),
(46, 1, '2015-08-21', 2015, 'Anniversaire de sa majesté le roi Mohammed VI'),
(47, 1, '2015-11-06', 2015, 'Anniversaire de la marche verte'),
(48, 1, '2015-11-18', 2015, 'Fête de l''indépendance'),
(49, 2, '2015-01-03', 2015, 'Naissance du prophète'),
(50, 2, '2015-07-18', 2015, 'Aïd al fitr fin du mois de ramadan'),
(51, 2, '2015-09-24', 2015, 'Aïd al adha fête du sacrifice'),
(52, 2, '2015-10-15', 2015, 'Jour de l''an de l''hégire'),
(53, 2, '2015-12-24', 2015, 'Naissance du prophète');

-- --------------------------------------------------------

--
-- Structure de la table `modalite`
--

CREATE TABLE IF NOT EXISTS `modalite` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `libelle` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `modalite`
--

INSERT INTO `modalite` (`id`, `code`, `libelle`) VALUES
(1, '1', 'Ancienne modalite 1'),
(2, '2', 'Ancienne modalite 2'),
(3, '3', 'Ancienne modalite 3'),
(4, 'MS', 'Modalite Standard'),
(5, 'RM', 'Realisation Mission'),
(6, 'AC', 'Autonomie Complete'),
(7, 'NO', 'Aucune modalite');

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `date_entree` date NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_entite` smallint(5) unsigned NOT NULL,
  `id_pole` smallint(5) unsigned NOT NULL,
  `id_modalite` smallint(5) unsigned NOT NULL,
  `id_fonction` smallint(5) unsigned NOT NULL,
  `pourcent` smallint(5) unsigned NOT NULL,
  `centre_service` tinyint(1) NOT NULL,
  `stage` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_entite` (`id_entite`),
  KEY `id_pole` (`id_pole`),
  KEY `id_modalite` (`id_modalite`),
  KEY `id_fonction` (`id_fonction`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id`, `nom`, `prenom`, `date_entree`, `date_debut`, `date_fin`, `id_entite`, `id_pole`, `id_modalite`, `id_fonction`, `pourcent`, `centre_service`, `stage`) VALUES
(1, 'AUDET', 'Hubert', '0000-00-00', '0000-00-00', '0000-00-00', 3, 1, 7, 1, 100, 0, 0),
(2, 'TAKHZANT', 'Samira', '0000-00-00', '2012-09-21', '0000-00-00', 2, 1, 7, 1, 100, 1, 0),
(3, 'SAHTOUTI', 'Hamid', '0000-00-00', '0000-00-00', '0000-00-00', 2, 1, 7, 2, 100, 1, 0),
(4, 'MOUMEN', 'Aniss', '0000-00-00', '0000-00-00', '0000-00-00', 2, 1, 7, 2, 100, 1, 0),
(5, 'TRIFOL', 'Pierre', '2007-10-08', '2011-01-02', '0000-00-00', 1, 2, 4, 1, 100, 0, 0),
(6, 'SOROURI', 'Salma', '0000-00-00', '2012-01-02', '0000-00-00', 2, 2, 7, 1, 100, 1, 0),
(7, 'BENNAI', 'Mohamed', '0000-00-00', '2011-07-01', '0000-00-00', 2, 2, 7, 2, 100, 1, 0),
(8, 'HAFID', 'El Mehdi', '0000-00-00', '2012-07-09', '0000-00-00', 2, 2, 7, 2, 100, 1, 0),
(9, 'TARTERAT', 'Angelique', '2007-08-02', '0000-00-00', '0000-00-00', 1, 3, 4, 1, 100, 0, 0),
(10, 'LEFRANC', 'Didier', '0000-00-00', '0000-00-00', '0000-00-00', 1, 3, 5, 1, 100, 0, 0),
(11, 'ZITOUNI', 'Youssef', '0000-00-00', '0000-00-00', '0000-00-00', 2, 3, 7, 2, 100, 1, 0),
(12, 'OUBEL', 'Hanane', '0000-00-00', '0000-00-00', '0000-00-00', 2, 3, 7, 2, 100, 1, 0),
(13, 'JOUID', 'Idriss', '0000-00-00', '0000-00-00', '0000-00-00', 2, 3, 7, 2, 100, 1, 0),
(14, 'MIOT', 'Guillaume', '2007-07-02', '0000-00-00', '0000-00-00', 1, 4, 5, 1, 100, 0, 0),
(15, 'SAGHROUCHNI', 'Mohamed', '0000-00-00', '0000-00-00', '0000-00-00', 2, 4, 7, 1, 100, 1, 0),
(16, 'OUAKROUCH', 'Mohamed', '0000-00-00', '0000-00-00', '0000-00-00', 2, 4, 7, 2, 100, 1, 0),
(17, 'SLIMANE', 'Merieme', '0000-00-00', '0000-00-00', '0000-00-00', 2, 4, 7, 2, 100, 1, 0),
(18, 'BOUHADDIOUI', 'Abdelali', '0000-00-00', '0000-00-00', '0000-00-00', 2, 4, 7, 2, 100, 1, 0),
(20, 'GOMBERT', 'Loic', '0000-00-00', '0000-00-00', '0000-00-00', 1, 5, 5, 1, 100, 0, 0),
(21, 'TURQUET', 'Nathalie', '1995-03-27', '0000-00-00', '0000-00-00', 1, 5, 5, 1, 80, 0, 0),
(22, 'MORIN', 'Guillaume', '2001-08-16', '0000-00-00', '0000-00-00', 1, 5, 5, 1, 100, 0, 0),
(23, 'ARZINI', 'Fatine', '0000-00-00', '0000-00-00', '0000-00-00', 2, 5, 7, 1, 100, 1, 0),
(24, 'ALLA', 'Aymane', '0000-00-00', '0000-00-00', '0000-00-00', 2, 5, 7, 2, 100, 1, 1),
(25, 'EL FEDDAOUI', 'Badr-Eddine', '0000-00-00', '0000-00-00', '0000-00-00', 2, 6, 7, 2, 100, 1, 0),
(26, 'TALBAOUI', 'Hanane', '0000-00-00', '0000-00-00', '0000-00-00', 2, 6, 7, 2, 100, 1, 0),
(27, 'EL HAYL', 'Hajiba', '0000-00-00', '0000-00-00', '0000-00-00', 2, 6, 7, 2, 100, 1, 0),
(28, 'FARISSI', 'Jaafar', '0000-00-00', '0000-00-00', '0000-00-00', 2, 6, 7, 2, 100, 1, 0),
(29, 'LARAQUI', 'Youssef', '0000-00-00', '0000-00-00', '0000-00-00', 2, 6, 7, 2, 100, 1, 0),
(30, 'ANGLADE', 'Patrick', '1995-03-13', '0000-00-00', '0000-00-00', 1, 7, 6, 4, 100, 0, 0),
(31, 'HUMBERT', 'Thomas', '2000-01-01', '0000-00-00', '0000-00-00', 1, 6, 5, 1, 100, 0, 0),
(32, 'JEAN-CHARLES', 'Johan', '0000-00-00', '0000-00-00', '0000-00-00', 3, 6, 7, 1, 100, 0, 0),
(33, 'JU', 'Ling', '0000-00-00', '0000-00-00', '0000-00-00', 3, 6, 7, 1, 100, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pole`
--

CREATE TABLE IF NOT EXISTS `pole` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `pole`
--

INSERT INTO `pole` (`id`, `libelle`) VALUES
(1, 'Pole 1'),
(2, 'Pole 2'),
(3, 'Pole 3'),
(4, 'Pole 4'),
(5, 'P2000'),
(6, 'Projets'),
(7, 'Pilotes');

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE IF NOT EXISTS `profil` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `mot_passe` char(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `profil`
--

INSERT INTO `profil` (`id`, `login`, `mot_passe`) VALUES
(1, 'admin', 'pass'),
(2, 'csm', 'pass'),
(3, 'equipe', 'pass');

-- --------------------------------------------------------

--
-- Structure de la table `proposition`
--

CREATE TABLE IF NOT EXISTS `proposition` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_personne` smallint(5) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `mi_debut_journee` tinyint(1) NOT NULL,
  `date_fin` date NOT NULL,
  `mi_fin_journee` tinyint(1) NOT NULL,
  `nombre_jours` float(4,1) NOT NULL,
  `etat` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_personne` (`id_personne`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `proposition`
--

INSERT INTO `proposition` (`id`, `id_personne`, `date_debut`, `mi_debut_journee`, `date_fin`, `mi_fin_journee`, `nombre_jours`, `etat`) VALUES
(1, 6, '2012-10-16', 0, '2012-10-17', 0, 2.0, 'OK'),
(2, 7, '2012-10-17', 0, '2012-10-18', 0, 2.0, 'NC'),
(3, 6, '2012-10-20', 0, '2012-10-21', 0, 0.0, 'OK'),
(4, 2, '2012-12-10', 1, '2012-12-11', 1, 2.0, 'OK'),
(5, 2, '2012-12-17', 1, '2012-12-18', 1, 2.0, 'OK');

-- --------------------------------------------------------

--
-- Structure de la table `solde`
--

CREATE TABLE IF NOT EXISTS `solde` (
  `id_personne` smallint(5) unsigned NOT NULL,
  `total_cp` float NOT NULL,
  `total_q1` float DEFAULT NULL,
  `total_q2` float DEFAULT NULL,
  `annee_reference` int(4) NOT NULL,
  PRIMARY KEY (`id_personne`,`annee_reference`),
  KEY `id_personne` (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `type_conge`
--

CREATE TABLE IF NOT EXISTS `type_conge` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `libelle` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `type_conge`
--

INSERT INTO `type_conge` (`id`, `code`, `libelle`) VALUES
(1, 'CP', 'Conges payes '),
(2, 'Q1', 'RTT '),
(3, 'Q2', 'RTT facultatives '),
(4, 'P', 'Prévisions '),
(5, 'EX', 'Conges exceptionnels '),
(6, '45', '4 sur 5 '),
(7, 'R', 'Reliquat '),
(8, 'F', 'Formations '),
(9, 'AP', 'Autre projet '),
(10, 'M', 'Maladies '),
(11, 'DF', 'Formations DIF '),
(12, 'AS', 'Astreintes CSM '),
(13, 'PC', 'Contraintes personnelles');

-- --------------------------------------------------------

--
-- Structure de la table `vacances`
--

CREATE TABLE IF NOT EXISTS `vacances` (
  `zone` char(1) DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `personne`
--
ALTER TABLE `personne`
  ADD CONSTRAINT `personne_ibfk_1` FOREIGN KEY (`id_entite`) REFERENCES `entite` (`id`),
  ADD CONSTRAINT `personne_ibfk_2` FOREIGN KEY (`id_pole`) REFERENCES `pole` (`id`),
  ADD CONSTRAINT `personne_ibfk_3` FOREIGN KEY (`id_modalite`) REFERENCES `modalite` (`id`),
  ADD CONSTRAINT `personne_ibfk_4` FOREIGN KEY (`id_fonction`) REFERENCES `fonction` (`id`);

--
-- Contraintes pour la table `proposition`
--
ALTER TABLE `proposition`
  ADD CONSTRAINT `proposition_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`);

--
-- Contraintes pour la table `solde`
--
ALTER TABLE `solde`
  ADD CONSTRAINT `solde_ibfk_2` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
