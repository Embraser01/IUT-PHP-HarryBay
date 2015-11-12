-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 12 Novembre 2015 à 21:13
-- Version du serveur: 5.5.41
-- Version de PHP: 5.5.30-1~dotdeb+7.1

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `Objet`
--

INSERT INTO `Objet` (`_id`, `nom`, `prix_min`, `date_start`, `date_stop`, `prix_now`, `is_max`, `proprio_id`, `best_user_id`) VALUES
  (8, '5,4m³ de terre sèche de Poudlard', 50, '2015-10-30', '2015-11-01', 1000, 1, 1, 7),
  (10, 'Nimbus 2000', 115, '2015-11-11', '2015-11-26', 5000, 0, 4, 17),
  (11, 'Le monstrueux livre des monstres', 65, '2015-11-11', '2015-12-11', 1000, 0, 7, 17),
  (16, 'Lunettes de Harry', 1001, '2015-11-12', '2015-11-28', 1001, 0, 9, NULL),
  (17, 'Vif d''or', 999.99, '2015-11-12', '2016-04-03', 999.99, 0, 7, NULL),
  (18, 'Choixpeau', 98520, '2020-11-20', '2020-12-19', 98520, 0, 7, NULL),
  (19, 'La Gazette du Sorcier', 50, '2015-11-12', '2016-11-19', 50.02, 0, 7, 1),
  (22, 'Voiture volante', 8990, '2015-11-12', '2015-12-31', 8990, 0, 7, NULL),
  (23, 'Voiture volée', 1350, '2015-11-12', '2016-03-12', 1350, 0, 7, NULL),
  (24, 'Baguette (pas) magiques', 3.5, '2015-11-13', '2015-11-24', 3.5, 0, 4, NULL),
  (25, 'Capes d''invisibilité', 427, '2015-11-12', '2016-11-12', 427, 0, 1, NULL),
  (27, 'Train à vapeur', 421337, '2015-11-12', '2016-05-09', 421337, 0, 4, NULL),
  (28, 'Phénix ', 1000, '2015-11-12', '2015-12-01', 1000, 0, 4, NULL),
  (29, 'Pendule des Weasley', 150, '2015-11-12', '2015-12-04', 150, 0, 4, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`_id`, `mail`, `nom`, `prenom`, `num_tel`, `mdp`) VALUES
  (1, 'admin@admin.com', 'MCGYVER', 'Angus', '0412698759', 'c0ab95268ab2f8511ceb7d36512a6dd6c54a488f'),
  (4, 'embraser01@gmail.com', 'Fernandes', 'Marc-Antoine', '0611890940', '5d814a5b3be25189e5db4c13bc5a5ff20cf527be'),
  (7, 'nicolaicroutonalail@gmail.com', 'POURPRIX', 'Nicolas', '0615115096', '780148f0ca073cfdd5df83101588b8cc0c7300d3'),
  (8, 'boblea@gmail.com', 'bob', 'lea', '0656897845', 'd8e816484c2f3d61ccfb737f7a50c8d361ae51ce'),
  (9, 'nicolas.pourprix@outlook.com', 'NICOLAS', 'Un autre', '0133734269', '780148f0ca073cfdd5df83101588b8cc0c7300d3'),
  (17, 'judil@gmail.com', 'DIL', 'Ju', '0678439985', '1eda92da849364c1020bfbf53f761d21ce27b66c');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
