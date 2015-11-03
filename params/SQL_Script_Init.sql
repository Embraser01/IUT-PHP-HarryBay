-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 03 Novembre 2015 à 13:35
-- Version du serveur: 5.5.41
-- Version de PHP: 5.4.39-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Structure de la table `Objet`
--

CREATE TABLE IF NOT EXISTS `Objet` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `prix_min` float NOT NULL,
  `date_start` date NOT NULL,
  `date_stop` date NOT NULL,
  `prix_now` float NOT NULL,
  `is_max` tinyint(1) NOT NULL,
  `proprio_id` int(11) NOT NULL,
  `best_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `Objet`
--

INSERT INTO `Objet` (`_id`, `nom`, `prix_min`, `date_start`, `date_stop`, `prix_now`, `is_max`, `proprio_id`, `best_user_id`) VALUES
  (8, '5,4m³ de terre sèche de Poudlard', 14000, '2015-10-30', '2015-11-01', 100000000, 1, 1, 7),
  (10, 'Barbe de Hagrid', 34, '2015-10-29', '2015-12-24', 42, 0, 1, 7),
  (11, 'Fond vert', 255, '2015-10-31', '2015-11-04', 300, 0, 1, 4),
  (12, 'Pr. McGonagall avec lunettes', 13337, '2015-10-31', '2015-11-16', 13337, 0, 1, NULL),
  (14, 'Daniel Radcliffe (acteur)', 1, '2015-10-31', '2015-11-11', 3, 0, 7, 1),
  (17, 'Pain croqué par Harry (2003)', 1, '2015-10-31', '2097-04-03', 1, 0, 1, NULL),
  (20, 'Nez de voldemort', 142, '2015-11-02', '2016-11-02', 142, 0, 4, NULL),
  (21, 'Baguette pas encore magique', 12, '2015-11-05', '2015-11-21', 12, 0, 1, NULL),
  (36, 'Vif d''or', 90, '2015-11-03', '2015-11-24', 90, 0, 4, NULL),
  (37, 'Cravate Gryffondor', 50.5, '2015-11-03', '2015-11-26', 50.5, 0, 4, NULL),
  (38, 'Esclave', 1, '2015-11-03', '2015-11-30', 1, 0, 1, NULL),
  (39, 'Poudlard', 750000, '2015-11-03', '2015-11-10', 750000, 0, 1, NULL),
  (40, 'Lunettes d''Harry Potter', 10001, '2015-11-03', '2015-11-29', 10001, 0, 4, NULL),
  (41, 'Nimbus 2000', 2000, '2015-11-03', '2015-11-10', 2000, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `num_tel` varchar(10) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `unique_mail` (`mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`_id`, `mail`, `nom`, `prenom`, `num_tel`, `mdp`) VALUES
  (1, 'admin@admin.com', 'MacGyver', 'Angus', '', 'c0ab95268ab2f8511ceb7d36512a6dd6c54a488f'),
  (4, 'embraser01@gmail.com', 'Fernandes', 'Marc-Antoine', '0611890940', '5d814a5b3be25189e5db4c13bc5a5ff20cf527be'),
  (7, 'nicolaicroutonalail@gmail.com', 'POURPRIX', 'Nicolas', '0615115096', 'c0ab95268ab2f8511ceb7d36512a6dd6c54a488f');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
