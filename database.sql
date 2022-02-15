-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 23 déc. 2021 à 18:29
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ppe`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `get_infos_about_to_all`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_infos_about_to_all` (IN `p_id_hebergement` INT)  BEGIN
	SELECT SUM(nbJours) as nuitees, hebergement.dateEnregistrement, COUNT(*) as nbReservation FROM reservations_hebergement 
                INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
                INNER JOIN hebergement USING(idHebergement) 
                WHERE idHebergement = p_id_hebergement  
                AND is_building = 0 
                AND dateFin BETWEEN (SELECT dateEnregistrement FROM hebergement WHERE idHebergement = p_id_hebergement) AND NOW();
  END$$

DROP PROCEDURE IF EXISTS `get_infos_about_to_year`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_infos_about_to_year` (IN `p_id_hebergement` INT, IN `p_date` DATETIME)  BEGIN
	SELECT SUM(nbJours) as nuitees, hebergement.dateEnregistrement, COUNT(*) as nbReservation FROM reservations_hebergement 
                INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
                INNER JOIN hebergement USING(idHebergement) 
                where idHebergement = p_id_hebergement  
                AND is_building = 0 
                AND dateFin BETWEEN 
                (CASE 
                WHEN (SELECT dateEnregistrement FROM hebergement WHERE idHebergement = p_id_hebergement) < p_date THEN p_date 
                ELSE (SELECT dateEnregistrement FROM hebergement WHERE idHebergement = p_id_hebergement) 
                END)
                AND NOW();
       END$$

DROP PROCEDURE IF EXISTS `obtenir_infos_gains`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenir_infos_gains` (IN `p_id_hebergement` INT, IN `p_1_mois` DATETIME, IN `p_3_mois` DATETIME, IN `p_6_mois` DATETIME, IN `p_1_an` DATETIME)  BEGIN
	SELECT 
    	( 
         SELECT SUM(reservations_hebergement.prix) FROM reservations_hebergement
         INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
         WHERE reservations_hebergement.idHebergement = hebergement.idHebergement 
         AND is_building = 0 
         AND dateDebut > p_1_mois 
         AND dateFin < NOW() 
        ) as gainsDuMois,
        ( 
         SELECT SUM(reservations_hebergement.prix) FROM reservations_hebergement
         INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
         WHERE reservations_hebergement.idHebergement = hebergement.idHebergement 
         AND is_building = 0 
         AND dateDebut > p_3_mois 
         AND dateFin < NOW() 
        ) as gainsDuTrimestre,
        ( 
         SELECT SUM(reservations_hebergement.prix) FROM reservations_hebergement
         INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
         WHERE reservations_hebergement.idHebergement = hebergement.idHebergement 
         AND is_building = 0 
         AND dateDebut > p_6_mois 
         AND dateFin < NOW() 
        ) as gainsDuSemestre,
        ( 
         SELECT SUM(reservations_hebergement.prix) FROM reservations_hebergement
         INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
         WHERE reservations_hebergement.idHebergement = hebergement.idHebergement 
         AND is_building = 0 
         AND dateDebut > p_1_an 
         AND dateFin < NOW() 
        ) as gainsAnnee,
        ( 
         SELECT SUM(reservations_hebergement.prix) FROM reservations_hebergement
         INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage 
         WHERE reservations_hebergement.idHebergement = hebergement.idHebergement 
         AND is_building = 0 
         AND dateFin < NOW() 
        ) as gainsALL
    FROM hebergement WHERE idHebergement = p_id_hebergement;
END$$

DELIMITER ;

CREATE DEFINER=`root`@`localhost` PROCEDURE `est_reserver` (`p_idHebergement` INT, OUT `nbr` INT)  BEGIN
	SELECT COUNT(*) into nbr
    FROM reservations_voyages
    INNER JOIN reservations_hebergement on reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage
    where reservations_voyages.is_building = 1 and reservations_hebergement.idHebergement = p_idHebergement;
    
    IF nbr > 1 THEN
    	set nbr = 1;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_avis` (IN `p_idAvis` INT)  BEGIN
	DELETE
    FROM avis
    where idAvis = p_idAvis;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_favoris` (`p_idHebergement` INT, `p_idUtilisateur` INT)  BEGIN
	DELETE
    from favoris
    where idHebergement = p_idHebergement and idUtilisateur = p_idUtilisateur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_hebergement` (IN `p_idHebergement` INT)  BEGIN
	DELETE
    from hebergement
    where idHebergement = p_idHebergement;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_option_by_herbergement` (IN `p_idHebergement` INT)  BEGIN
	DELETE
    from options_by_hebergement
    where idHebergement = p_idHebergement;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_reservations_hebergement` (IN `p_idReservationHebergement` INT)  BEGIN
	DELETE
    from reservations_hebergement
    where idReservationHebergement = p_idReservationHebergement;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_reservation_voyage` (`p_idReservationVoyage` INT, `p_is_building` INT)  BEGIN
	DELETE
    FROM reservations_voyages
    WHERE idReservationVoyage = p_idReservationVoyage and is_building = p_is_building;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_user` (IN `p_idUser` INT)  BEGIN
	DELETE
    from utilisateurs
    where idUtilisateur = p_idUser;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sup_villes` (`p_idVille` INT)  BEGIN
	DELETE
    FROM reservations_voyages
    WHERE idVilles = p_idVille;
END$$

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

DROP TABLE IF EXISTS `activites`;
CREATE TABLE IF NOT EXISTS `activites` (
  `idActivite` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  PRIMARY KEY (`idActivite`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `activites`
--

INSERT INTO `activites` (`idActivite`, `libelle`, `icon`) VALUES
(1, 'airport', 'airport.png'),
(2, 'apple', 'apple.png'),
(3, 'arch', 'arch.png'),
(4, 'binoculars', 'binoculars.png'),
(5, 'cinema', 'cinema.png'),
(6, 'flowers', 'flowers.png'),
(7, 'gifts', 'gifts.png'),
(8, 'home', 'home.png'),
(9, 'museum_painting', 'museum_painting.png'),
(10, 'restaurant', 'restaurant.png'),
(11, 'watercraft', 'watercraft.png'),
(12, 'waterskiing', 'waterskiing.png'),
(13, 'weights', 'weights.png'),
(14, 'zoo', 'zoo.png'),
(15, 'gondola', 'gondola.png'),
(16, 'grass', 'grass.png'),
(17, 'grocery', 'grocery.png'),
(18, 'hiking', 'hiking.png'),
(19, 'jazzclub', 'jazzclub.png'),
(20, 'museum_naval', 'museum_naval.png'),
(21, 'palace', 'palace.png'),
(22, 'parking', 'parking.png'),
(23, 'winebar', 'winebar.png'),
(24, 'beer', 'beer.png'),
(25, 'bicycle', 'bicycle.png'),
(26, 'castle', 'castle.png'),
(27, 'chapel', 'chapel.png'),
(28, 'coffee', 'coffee.png'),
(29, 'factory', 'factory.png');

-- --------------------------------------------------------

--
-- Structure de la table `activites_by_ville`
--

DROP TABLE IF EXISTS `activites_by_ville`;
CREATE TABLE IF NOT EXISTS `activites_by_ville` (
  `idActivite` int(11) NOT NULL,
  `idVille` int(11) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `activites_by_ville`
--

INSERT INTO `activites_by_ville` (`idActivite`, `idVille`, `latitude`, `longitude`, `description`) VALUES
(1, 13, 47.1568, -1.6089, 'L\'aéroport de Nantes'),
(11, 13, 47.1173, -1.66125, 'Jet-ski'),
(12, 13, 47.1057, -1.65473, 'Ski nautique'),
(10, 13, 47.2042, -1.5665, 'De nombreux restaurant sur les berges du Hangar à banane'),
(4, 13, 47.1878, -1.5562, 'Découvrez le village de Rezé et ses sculptures contemporaines'),
(4, 13, 47.2324, -1.60512, 'Découvrez le Parc du val de Chézine et son calme reposant'),
(7, 13, 47.2492, -1.515, 'Découvrez les centres commerciaux de Nantes, un lieu parfait pour ramener des souvenirs'),
(5, 13, 47.2234, -1.63216, 'Le cinéma où tous les bons films sont'),
(16, 13, 47.2882, -1.49174, 'Golf de Carquefou'),
(18, 13, 47.3024, -1.59353, 'Randonnée de la Frosnière'),
(18, 13, 47.2306, -1.75575, 'Randonnée du Bois des Prés'),
(18, 13, 47.1259, -1.58529, 'Randonnée du Lac de Grand-Lieu'),
(19, 13, 47.1973, -1.50272, 'Club de Jazz, concert tous les soirs.'),
(18, 13, 47.1507, -1.42393, 'Randonnée du Moulin de la Bourchinière'),
(6, 13, 47.1562, -1.48564, 'Parc de la Mésangère'),
(22, 13, 47.2175, -1.60267, 'Parking Maurice Garin. 388 places.'),
(22, 13, 47.2354, -1.53062, 'Parking Donatien Bahaud. 256 places.'),
(22, 13, 47.201, -1.5238, 'Parking de la Libération. 178 places.'),
(18, 17, 47.0879, -0.905712, 'Randonnée de la Vendée'),
(18, 17, 47.0301, -0.838936, 'Randonnée des deux lacs'),
(18, 17, 47.0797, -0.802029, 'Randonnée du Rond des Dames'),
(10, 17, 47.058, -0.858634, 'Restaurant l\'autre usine'),
(10, 17, 47.0501, -0.908202, 'Restaurant la Grange'),
(10, 17, 47.0618, -0.880564, 'Brasserie le Grand Café'),
(16, 17, 47.0783, -0.897859, 'Golf de Cholet'),
(1, 17, 47.0808, -0.87859, 'Aérodrome de Cholet'),
(4, 17, 47.063, -0.940474, 'Panorama d\'un village vieux de 500 ans'),
(4, 17, 47.022, -0.81868, 'Panorama des deux lacs'),
(4, 17, 47.0774, -0.799368, 'Panorama des cultures régionales'),
(6, 14, 47.4747, -0.544306, 'Jardin des plantes. Plus de 1000 espèces s\'y développent. Chaque visiteur peut y planter une graine'),
(6, 14, 47.4767, -0.57851, 'Parc de la Garenne, profitez d\'espaces aménagés pour la marche'),
(6, 14, 47.4696, -0.47444, 'Parc des Pignerolles, un lieu reposant pour toute la famille'),
(5, 14, 47.4794, -0.550443, 'Cinéma Pathé. On y voit, parait-il, les meilleurs films de France'),
(10, 14, 47.4716, -0.553147, 'Bistrot des Ducs, un restaurant plébiscité par les guides locaux'),
(19, 14, 47.4473, -0.573102, 'Spectacle de guitare tous les jours à 15h00'),
(23, 14, 47.4451, -0.539199, 'Jean René, Caviste local. Elu meilleur caviste de la ville 2020.'),
(15, 14, 47.4555, -0.588895, 'Envie de faire un petit tour de gondole ? Ce petit lac est le lieu que nous vous recommandons pour cela !'),
(18, 14, 47.4729, -0.447789, 'Randonnée de la Pie Hardie'),
(4, 14, 47.4026, -0.485641, 'Visiter un village plein de charme et ne louper pas son église vieille de 800 ans !'),
(4, 14, 47.414, -0.611898, 'L\'embouche des noyés. Un panorama unique sur la Loire où de nombreux tourbillons se forment'),
(16, 14, 47.5108, -0.577136, 'Golf d\'Avrillé. Le repère de tout bon hipster'),
(6, 15, 46.9801, -1.31853, 'Parc des Rochettes. La recommandation du coin pour se reposer au soleil'),
(10, 15, 46.9754, -1.31441, 'Le restaurant La Digue, tenu par Roger, est noté parmi les deux meilleurs restaurants de Montaigu'),
(4, 15, 46.964, -1.33935, 'Visiter le petit village de Boufféré vous permettra d\'observer l\'architecture unique des maisons du village'),
(18, 15, 46.9747, -1.28707, 'La randonnée de la Gouraudière fait 15 km. Seuls les plus aguerris s\'y aventurent.'),
(29, 15, 46.9663, -1.32222, 'La carrière de Montaigu existe depuis maintenant des dizaines d\'années. La légende veut qu\'elle contienne de l\'or. Il est d\'ailleurs possible d\'en chercher tous les samedis'),
(27, 15, 46.948, -1.29557, 'L\'église du village est célèbre pour la beauté de ses vitraux'),
(20, 21, 47.1071, -2.11139, 'Au porc de Pornic, vous pouvez réserver un petit bateau chez Fabrice pour faire la visite des côtes.'),
(10, 21, 47.1066, -2.0983, 'La crêperie de la Source est réputé pour faire les meilleurs crêpes de la ville. Nous conseillons d\'ailleurs la Seguin'),
(16, 21, 47.117, -2.12238, 'Un golf de 9 trous où le mythic \'One Pack\' à réussir un score de 27'),
(20, 21, 47.0952, -2.05615, 'Le port de la Boutinardière est connu pour son marché de poisson frais tous les mercredi matin'),
(4, 21, 47.1234, -2.09, 'L\'étang de Pornic est le lieu parfait pour une après-midi pêche'),
(7, 21, 47.1179, -2.1095, 'Spécialisédans la faïence, cette boutique vous proposera de super objets souvenirs'),
(11, 21, 47.1133, -2.15911, 'Il est possible de jouer un Jet-ski ici'),
(12, 21, 47.1125, -2.15388, 'Il est possible de faire du ski nautique ici'),
(14, 22, 47.6809, -0.0505918, 'Le célèbre parc zoologique de la Flèche ! Le lieu à ne pas manquer '),
(10, 22, 47.6989, -0.0771564, 'Le restaurant la Laurène, spécialités locales uniquement.'),
(4, 22, 47.6999, -0.0442403, 'Point de vue sur les étangs de la Flèche.'),
(18, 22, 47.7027, -0.0334257, 'Randonnée des 3 étangs de la Flèche. 6 km. '),
(27, 22, 47.6902, -0.0725215, 'Eglise catholique de la période médiévale. St Jacques y est représenté.'),
(7, 22, 47.7045, -0.0921338, 'La boutique de Marjorie. Sculpture locale et dessin artistique pour des souvenirs uniques'),
(6, 22, 47.7138, -0.0735944, 'Parc de Bethete, spécialisé dans les iris'),
(10, 22, 47.6965, -0.12269, 'Restaurant La pause Café, Routier 1 étoile Michelin'),
(26, 19, 46.6693, -1.42538, 'Domaine privé autorisé à la visite. Château du 16ième siècle.'),
(10, 19, 46.6495, -1.43761, 'Restaurant la Duchesne de Denant, spécialité locale à base de fromage'),
(1, 19, 46.6967, -1.37607, 'Aérodrome de La Roche sur Yon, possibilité de faire un trajet en biplace selon le temps'),
(9, 19, 46.668, -1.45306, 'Musée d\'art contemporain, œuvres de Pati Pastoré exposés pour la première fois. '),
(28, 19, 46.6661, -1.40697, 'Bar à café célèbre pour ses 500 cafés différents et de toutes origines'),
(4, 19, 46.7034, -1.41204, 'Panorama du lac du lézard, l\'endroit parfait pour se reposer au soleil'),
(4, 19, 46.7187, -1.46259, 'Vous pourrez visiter le cachot de Val de térinsse, le terrible pilleur'),
(26, 20, 47.257, -0.072295, 'Le château de Saumur et sa place classé au patrimoine mondial'),
(10, 20, 47.2589, -0.0755136, 'Le bistrot de la place, juste en face de la gare, est l\'alliance de la cuisine rapide mais quand même gastronomique'),
(23, 20, 47.2724, -0.0727241, 'Chez Bertrand, c\'est de père en fils qu\'on est caviste, vigneron et passionné.'),
(5, 20, 47.2795, -0.062167, 'Le cinéma du Grand Palace est le lieu parfait pour prendre une petite pause en dehors de tout'),
(3, 20, 47.2426, -0.0723379, 'Un bâtiment qui a résisté non seulement à la guerre, mais aussi à l\'usure du temps. C\'est aujourd\'hui un musée des blindés.'),
(16, 20, 47.2751, -0.127098, 'Un gold de 18 trous, il faudra bien la journée pour en venir à bout ! On vous conseille aussi le restaurant sur les bords du terrain'),
(14, 20, 47.2635, -0.137269, 'La visite du cadre noir, école nationale d\'équitation, est un incontournable de Saumur. Réjouira les petits et les grands'),
(18, 20, 47.2665, -0.0356024, 'La visite de Villerbernier et ses cépages sont indispensable à tout amateur de vin (et confirmé)'),
(10, 20, 47.2714, -0.054056, 'La Métairie, le restaurant spécialisé dans les produits de la mer et qui vous veut du bien'),
(25, 20, 47.2347, -0.10873, 'La location de vélo pour faire le tour de la ville et de ses alentours se fait ici ! Electrique ou manuel, il y en a pour tous');

-- --------------------------------------------------------

--
-- Structure de la table `admin_valid_hebergements`
--

DROP TABLE IF EXISTS `admin_valid_hebergements`;
CREATE TABLE IF NOT EXISTS `admin_valid_hebergements` (
  `id_admin_valid_hebergement` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `nomVille` varchar(100) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `prix` decimal(7,2) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `dateEnregistrement` date NOT NULL,
  `is_actif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_admin_valid_hebergement`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin_valid_hebergements`
--

INSERT INTO `admin_valid_hebergements` (`id_admin_valid_hebergement`, `libelle`, `description`, `nomVille`, `latitude`, `longitude`, `prix`, `idUtilisateur`, `dateEnregistrement`, `is_actif`) VALUES
(1, 'maison de luxe', 'azerty', 'Saint-Lumine-de-Coutais', -1.72637, 47.0541, '45.36', 1, '2021-12-13', 1),
(2, 'aze', 'azer', 'Nantes', -1.51623, 47.2423, '10.00', 1, '2021-12-13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `agences`
--

DROP TABLE IF EXISTS `agences`;
CREATE TABLE IF NOT EXISTS `agences` (
  `idAgence` int(11) NOT NULL AUTO_INCREMENT,
  `adresse` varchar(255) NOT NULL,
  `code_postal` int(5) NOT NULL,
  `idVille` int(11) DEFAULT NULL,
  `idRegion` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAgence`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `agences`
--

INSERT INTO `agences` (`idAgence`, `adresse`, `code_postal`, `idVille`, `idRegion`) VALUES
(1, '1 rue anjela duval', 29860, NULL, NULL),
(2, '1 rue caponière', 36110, NULL, NULL),
(3, '1 rue saint-georges', 28220, NULL, NULL),
(4, '1 rue lamartine', 33000, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `idAvis` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `note` int(2) NOT NULL,
  `commentaire` text,
  `idUtilisateur` int(11) NOT NULL,
  `idHebergement` int(11) NOT NULL,
  PRIMARY KEY (`idAvis`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `date`, `note`, `commentaire`, `idUtilisateur`, `idHebergement`) VALUES
(1, '2021-10-19', 5, 'C\'était vraiment magnifique !!!', 1, 1),
(52, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 2),
(4, '2021-10-13', 3, 'Pas mal', 2, 3),
(5, '2021-10-13', 4, 'Pas mal du tout', 2, 4),
(6, '2021-10-14', 5, 'C\'était vraiment magnifique !', 3, 3),
(7, '2021-10-13', 3, 'Pas mal peut être', 2, 5),
(8, '2021-10-13', 3, 'Pas mal', 4, 5),
(9, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 6),
(10, '2021-10-13', 3, 'Pas mal', 2, 7),
(11, '2021-10-13', 4, 'Pas mal du tout', 3, 8),
(12, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 9),
(13, '2021-10-13', 3, 'Pas mal peut être', 2, 10),
(14, '2021-10-13', 3, 'Pas mal', 3, 11),
(15, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 12),
(16, '2021-10-13', 3, 'Pas mal', 2, 13),
(17, '2021-10-13', 4, 'Pas mal du tout', 2, 14),
(18, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 15),
(19, '2021-10-13', 3, 'Pas mal peut être', 2, 16),
(20, '2021-10-13', 3, 'Pas mal', 2, 17),
(21, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 18),
(22, '2021-10-13', 3, 'Pas mal', 2, 19),
(23, '2021-10-13', 4, 'Pas mal du tout', 2, 20),
(24, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 21),
(25, '2021-10-13', 3, 'Pas mal peut être', 2, 22),
(26, '2021-10-13', 3, 'Pas mal', 2, 23),
(27, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 24),
(28, '2021-10-13', 3, 'Pas mal', 2, 25),
(29, '2021-10-13', 4, 'Pas mal du tout', 4, 25),
(30, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 22),
(31, '2021-10-13', 3, 'Pas mal peut être', 3, 23),
(32, '2021-10-13', 3, 'Pas mal', 3, 21),
(33, '2021-10-14', 5, 'C\'était vraiment magnifique !', 3, 15),
(34, '2021-10-13', 3, 'Pas mal', 4, 16),
(35, '2021-10-13', 4, 'Pas mal du tout', 4, 19),
(36, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 8),
(37, '2021-10-13', 3, 'Pas mal peut être', 2, 9),
(38, '2021-10-13', 3, 'Pas mal', 3, 7),
(39, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 6),
(40, '2021-10-13', 3, 'Pas mal', 3, 4),
(41, '2021-10-13', 4, 'Pas mal du tout', 2, 16),
(51, '2021-10-14', 5, 'C\'était vraiment magnifique !', 3, 2),
(53, '2021-10-18', 3, 'C\'était vraiment pas mal !', 3, 1),
(44, '2021-10-13', 3, 'Pas mal', 4, 7),
(45, '2021-10-14', 5, 'C\'était vraiment magnifique !', 4, 3),
(50, '2021-10-13', 3, 'Pas mal', 2, 2),
(47, '2021-10-13', 4, 'Pas mal du tout', 2, 12),
(48, '2021-10-14', 5, 'C\'était vraiment magnifique !', 2, 11),
(49, '2021-10-13', 3, 'Pas mal peut être', 3, 13),
(54, '2021-10-19', 5, 'C\'était vraiment magnifique 2 !!!', 1, 1),
(55, '2021-10-18', 3, 'C\'était vraiment pas mal 2 !', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `avis_response`
--

CREATE TABLE `avis_response` (
  `idResponse` int(11) NOT NULL,
  `idAvis` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `reponse` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idResponse`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `avis_response`
--

INSERT INTO `avis_response` (`idResponse`, `idAvis`, `idUtilisateur`, `reponse`, `date`) VALUES
(1, 26, 1, 'Test', '2021-11-18 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `consulter_messages`
--

DROP TABLE IF EXISTS `consulter_messages`;
CREATE TABLE IF NOT EXISTS `consulter_messages` (
  `idUtilisateur` int(11) NOT NULL,
  `idMessage` int(11) NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idMessage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `consulter_messages`
--

INSERT INTO `consulter_messages` (`idUtilisateur`, `idMessage`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 10),
(7, 11),
(7, 12),
(7, 13),
(7, 14),
(7, 15),
(7, 16),
(7, 17),
(7, 18),
(7, 19),
(7, 20),
(7, 21),
(7, 22),
(7, 23),
(7, 24),
(7, 25),
(7, 26),
(8, 27),
(8, 28),
(8, 29),
(8, 30),
(8, 31),
(8, 32),
(8, 36),
(8, 38),
(9, 33),
(9, 34),
(9, 35),
(9, 37);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `idHebergement` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  PRIMARY KEY (`idHebergement`,`idUtilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`idHebergement`, `idUtilisateur`) VALUES
(2, 1),
(5, 1),
(7, 1),
(12, 1);

-- --------------------------------------------------------

--
-- Structure de la table `hebergement`
--

DROP TABLE IF EXISTS `hebergement`;
CREATE TABLE IF NOT EXISTS `hebergement` (
  `idHebergement` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `idVille` int(11) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `prix` decimal(10,0) DEFAULT NULL,
  `uuid` text NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `dateEnregistrement` date DEFAULT NULL,
  PRIMARY KEY (`idHebergement`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `hebergement`
--

INSERT INTO `hebergement` (`idHebergement`, `libelle`, `description`, `idVille`, `latitude`, `longitude`, `prix`, `uuid`, `idUtilisateur`, `dateEnregistrement`) VALUES
(1, 'Hotel de Nantes', 'un hôtel basé en bretagne', 13, 47.2184, -1.55362, '70', 'a68686210e9bae009424e9b7c7200227feccc185c5d45b439e932dd18c739a09', 1, '2020-08-02'),
(2, 'Hotel d\'Anger', 'Hôtel en plein milieu du centre ville, à 100 mètres des meilleurs restaurant.', 14, 47.4711, -0.547307, '89', 'fed294478907c1a2d2cd743cf46c673ace7853b0604b3b002c88c90d80f27c6e', 0, '2021-08-15'),
(3, 'Hôtel de traverse', 'En plein centre de Montaigu, à 100 mètres du meilleur ELerclec du coin.', 15, 46.9833, -1.3167, '36', '3a33f82391ef817b7c9e1c87014aabd601205876ee14de5c9ff095230dca1364', 0, '2021-08-15'),
(5, 'Studio sur l\'île de Nantes', 'A 500 mètres du hangar à banane, un lieu rempli de d\'activité, votre séjour sera merveilleux', 13, 47.204, -1.54562, '58', '27452debd8f1d167a1f1fe3dcd81d3e7c8e0e2c9599db140c36a188c03a80ae6', 1, '2021-08-15'),
(6, 'Studio de 40 m² ', 'Un bel espace à vivre cosy et confortable', 17, 47.0594, -0.879787, '63', 'cb2e08fd69c01edf91165270f17920670e392806275a902b5fd771b76d4cc122', 0, '2021-08-15'),
(7, 'Château des Ducs de Bretagne', 'Au cœur même de Nantes, ce château vous ravira par l\'espace qu\'il vous offre', 13, 47.2159, -1.54931, '100', '89cc8aaaf79ecf5731e75fe872aec9a227671aa0fafba342b478995ed1e49736', 0, '2021-08-15'),
(8, 'Chambre d\'hôpital', 'Au cœur même du service de médecine interne, cette chambre vous assura du repos et de bons soins.', 14, 47.4826, -0.557617, '103', '', 0, '2021-08-15'),
(9, 'Cabane 2 pièces 25m²', 'Au sein du parc Henri Joyau, dans un espace naturel et classé Parc d\'or 1991, profitez d\'un séjour paisible.', 15, 46.98, -1.32003, '24', 'eee7049f923c0b79d3d0eb7c2311e9a5b68d4df45fee46d73d3c7bc01b55073e', 0, '2021-08-15'),
(10, 'Maison 60m² 3 pièces', 'Située en face du lac du Maine, cet hébergement vous offrira une vue imprenable sur la nature.', 14, 47.4642, -0.593099, '75', '', 0, '2021-08-15'),
(11, 'Appartement Jules Ferry', 'En plein centre du collège Jules Ferry. Vous serez au milieu de la future élite  Montaigusienne. Avec une réussite nationale de plus de 50% au brevet, l\'inspiration pour le futur prix Nobel de physique n\'est plus qu\'à quelques pas.', 15, 46.9716, -1.30607, '66', '7d379de6d89e7428e04a8d0bfe149ea4961080896207dbc650c0386e2b4799c3', 1, '2021-08-15'),
(12, 'Ibis budget chambre lit double', 'A deux pas du Mc Donald, vous êtes en plein milieu d\'un centre commercial. Un vrai régal pour tout acheteur compulsif qui se respecte.', 17, 47.0456, -0.902766, '45', 'f2e5a72627e7e830a2e6bb4b67c558a844b56cb7832192f3d0d5e050d5d52335', 0, '2021-08-15'),
(13, 'Maison de 150m²', 'Au cœur de Mon Plaisir, lieu dit d\'Anger, vous profiterez de notre maison familiale équipé tout confort !', 14, 47.4887, -0.529603, '150', 'd0511559f470c90b11d4e0eb45a13fe01ed3e939c8863d4ecb5272a716bc7c9d', 0, '2021-08-15'),
(14, 'Hôtel de Pornic', 'Au cœur de Pornic, cette chambre rustique et cosy est parfait pour une visite calme et agréable', 21, 47.1064, -2.08561, '56', '04ec6c277a909141c68154f1f642d37de3d58000102d8a4df812dd0b95755ea0', 0, '2021-08-15'),
(15, 'Appartement vue golf', 'La baie vitrée de l\'hébergement donne directement sur le Golf de Pornic qui est extrêmement réputé pour sa qualité.', 21, 47.1138, -2.11957, '156', '73d3ef7852f55d084427a4821d2c11519cecdef48eced7d8e12381bea44cd386', 0, '2021-08-15'),
(16, 'Maison 2ième étage vue mer', 'Sur le port de Pornic, à deux pas du casino et à 100 mètres des premiers commerces. Confortable et réputé.', 21, 47.1083, -2.11533, '86', '73a4a0c3b878a885fcd8b0a266a5df3c9ff6c6e73e8f44b205b1deb21e58ebbc', 0, '2021-08-15'),
(17, 'Auberge du château', 'Au cœur même du château de Saumur, en plein centre historique, cette hébergement sera le parfait voyage dans le temps que vous recherchez', 20, 47.2565, -0.0739807, '257', 'a9ea06db063dcf34e0effbc976ee052db26de44e8523d152ebfc0043e72aeaf0', 0, '2021-08-15'),
(18, 'Maison sur l\'île', 'Sur l\'île même de Saumur, aussi proche des vignobles que des rues piétonnes, ce logement vous ravira.', 20, 47.2665, -0.0758752, '68', '4afcc134e6e134571a110f2a1562d7054acf0e172260527a598ed10640f0af85', 0, '2021-08-15'),
(19, 'Cabane du golf', 'En plein centre du golf de Saumur, vous pourrez déranger les Hipsters que vous trouverez en train de jouer.', 20, 47.2755, -0.120697, '78', 'd41ffb85a65361f73f5a65289379b974f6166fe36200ae32ab25a52b5d9f3b7f', 0, '2021-08-15'),
(20, 'Maison de 60 m²', 'A deux pas du centre ville, cette hébergement sera vous séduire.', 19, 46.6708, -1.40399, '49', '276583c25bad5c4377a6325f52d8ce71a3c3e97abba8b90f4f9e5481ff29b2e0', 0, '2021-08-15'),
(21, 'Appartement restaurant', 'Proche des meilleurs restaurants du coin, les gambas et les huitres seront dans le frigo.', 19, 46.6651, -1.43794, '87', '036ff34cc84e24ecaa1fca25be5f381379420ab7f1df18f3ea0d9dd1cc0db85b', 0, '2021-08-15'),
(22, 'Jolie maisonnette ', 'Espace calme et cosy de 68 m². Petit déjeuner offert par la maison.', 19, 46.6852, -1.41685, '65', '7e11083f4d73bbddd87392ada864860365cf35f8a48270095261dfeec5f7fb15', 0, '2021-08-15'),
(23, 'Hébergement zootastique', 'A deux pas du zoo de la flèche, un moment souvenir incroyable, des lions sous la fenêtre', 22, 47.6827, -0.0479397, '99', '295df0eb2338119662af7115705dd28d31922b0b3c0cd1796f79a2de3a0e635e', 0, '2021-08-15'),
(24, 'Appart cool', 'Un smiley est caché dans le frigo, ça donne la pêche.', 22, 47.6999, -0.0724185, '43', '46e04703fa5948b033336330e5ddd3582e81c0194dc9ac16bf168df2e842dc32', 0, '2021-08-15'),
(25, 'La maison fermée', 'Une maison aux allures sympathiques et chaleureuses.', 22, 47.6931, -0.0781164, '69', 'da972073c123397ea9390fd42e315a83ebe83e5e5d65e7a6b93d48540b4436c3', 0, '2021-08-15'),
(26, 'La cabanette des beaux bois', 'Une bien belle cabanette. Mais qui peut y vivre ?', 17, 47.0564, -0.912572, '88', 'a31d42130b2a0dc4119d45ddab4862f04c5d0926e0aa71e27206b07c7a593d53', 0, '2021-08-15'),
(28, 'azerty', 'azerty', 1, 47, -0.5, '666', '8d17e6a5a33f1224dbae8dfbf9ecf4e041f66e431f50b0ac3a9867ba064fb308', 1, '2021-11-10');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `idMessage` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `contenu` text NOT NULL,
  `expediteur` int(11) NOT NULL,
  `destinataire` int(11) DEFAULT NULL,
  `idRole` int(11) NOT NULL,
  PRIMARY KEY (`idMessage`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`idMessage`, `date`, `contenu`, `expediteur`, `destinataire`, `idRole`) VALUES
(1, '2021-04-04', 'Bonjour numéro 1', 1, NULL, 2),
(2, '2021-04-04', 'Bonjour numéro 2', 1, NULL, 2),
(3, '2021-04-04', 'Bonjour numéro 3', 1, NULL, 2),
(4, '2021-04-04', 'Bonjour numéro 4', 1, NULL, 2),
(5, '2021-04-04', 'Bonjour numéro 5', 1, NULL, 2),
(6, '2021-04-04', 'Bonjour numéro 6', 1, NULL, 2),
(7, '2021-04-04', 'Bonjour numéro 7', 1, NULL, 2),
(8, '2021-04-04', 'Bonjour numéro 8', 1, NULL, 2),
(9, '2021-04-04', 'Bonjour numéro 9', 7, 1, 1),
(10, '2021-04-04', 'Bonjour numéro 10', 7, 1, 1),
(11, '2021-04-04', 'Bonjour numéro 11', 7, 1, 1),
(12, '2021-04-04', 'Bonjour numéro 12 probablement', 7, 1, 1),
(26, '2021-04-04', 'Bonjour numéro 4', 4, NULL, 2),
(25, '2021-04-04', 'Bonjour numéro 3', 3, NULL, 2),
(24, '2021-04-04', 'Bonjour numéro 2', 2, NULL, 2),
(17, '2021-04-04', 'nouvel échange', 7, 1, 1),
(23, '2021-04-04', 'Bonjour numéro 1', 2, NULL, 2),
(19, '2021-04-04', 'si j\'écris un truc', 7, 1, 1),
(20, '2021-04-04', 'si j\'écris un truc', 7, 1, 1),
(21, '2021-04-04', 'si j\'écris un truc', 7, 1, 1),
(22, '2021-04-04', 'test', 7, 1, 1),
(28, '2021-04-04', 'Me voila', 8, 4, 1),
(29, '2021-04-04', 'test', 8, 2, 1),
(30, '2021-04-04', 'blablabla', 8, 3, 1),
(31, '2021-04-04', 'toi tu écris beaucoup trop', 8, 1, 1),
(32, '2021-04-04', 'Bonjour Mr l\'admin, je test un nouveau message', 9, NULL, 2),
(33, '2021-04-04', 'Très bien, à votre guise', 8, 9, 1),
(34, '2021-04-05', 'bla', 8, 9, 1),
(35, '2021-04-05', 'blabla', 8, 9, 1),
(36, '2021-04-05', 'bonjour', 9, NULL, 2),
(37, '2021-04-05', 'voila un message', 8, 9, 1),
(38, '2021-04-06', 'zesdrfghijklm', 9, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `idOption` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idOption`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`idOption`, `libelle`, `icon`) VALUES
(1, 'tétévision', 'fas fa-tv'),
(2, 'lave linge', 'fas fa-sink'),
(3, 'sèche linge', 'fas fa-sink'),
(4, 'cuisine', 'fas fa-utensils'),
(5, 'réfrégirateur', 'fas fa-icicles'),
(6, 'four', 'fab fa-hotjar'),
(7, 'parking gratuit', 'fas fa-car'),
(8, 'linge de maison', 'fas fa-tshirt'),
(9, 'cafetière', 'fas fa-coffee'),
(10, 'climatisation', 'fas fa-temperature-low'),
(11, 'vaiselle', 'fas fa-utensils');

-- --------------------------------------------------------

--
-- Structure de la table `options_by_hebergement`
--

DROP TABLE IF EXISTS `options_by_hebergement`;
CREATE TABLE IF NOT EXISTS `options_by_hebergement` (
  `idHebergement` int(11) NOT NULL,
  `idOption` int(11) NOT NULL,
  PRIMARY KEY (`idHebergement`,`idOption`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `options_by_hebergement`
--

INSERT INTO `options_by_hebergement` (`idHebergement`, `idOption`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 10),
(7, 11),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(9, 10),
(9, 11),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(10, 8),
(10, 9),
(10, 10),
(10, 11),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10),
(11, 11),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(12, 6),
(12, 7),
(12, 8),
(12, 9),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(13, 6),
(13, 7),
(13, 8),
(13, 9),
(13, 10),
(13, 11),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(14, 6),
(14, 7),
(14, 8),
(14, 9),
(14, 10),
(14, 11),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(15, 5),
(15, 6),
(15, 7),
(15, 8),
(15, 9),
(15, 10),
(15, 11),
(16, 1),
(16, 3),
(16, 4),
(16, 5),
(16, 6),
(16, 9),
(16, 10),
(16, 11),
(17, 4),
(17, 5),
(17, 6),
(17, 9),
(17, 11),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(18, 8),
(18, 9),
(18, 10),
(18, 11),
(19, 1),
(19, 4),
(19, 5),
(19, 6),
(19, 9),
(19, 10),
(19, 11),
(20, 1),
(20, 2),
(20, 4),
(20, 5),
(20, 6),
(20, 9),
(20, 10),
(21, 1),
(21, 2),
(21, 3),
(21, 5),
(21, 6),
(21, 8),
(21, 9),
(21, 10),
(22, 2),
(22, 3),
(22, 4),
(22, 5),
(22, 7),
(22, 8),
(22, 9),
(22, 10),
(22, 11),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(23, 5),
(23, 6),
(23, 7),
(23, 8),
(23, 9),
(23, 10),
(23, 11),
(24, 1),
(24, 3),
(24, 5),
(24, 6),
(24, 10),
(24, 11),
(25, 1),
(25, 2),
(25, 4),
(25, 5),
(25, 6),
(25, 7),
(25, 10),
(25, 11),
(26, 9),
(27, 1),
(28, 6),
(29, 1),
(29, 2),
(29, 3),
(29, 4);

-- --------------------------------------------------------

--
-- Structure de la table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `idRegion` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `lv_zoom` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`idRegion`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `regions`
--

INSERT INTO `regions` (`idRegion`, `libelle`, `latitude`, `longitude`, `lv_zoom`, `description`) VALUES
(2, 'Pays de la Loire', 47.2633, -0.329969, 8, 'La région des Pays de la Loire est située à l\'ouest de la France et bordée par le golfe de Gascogne. Elle comprend une partie de la vallée de la Loire, renommée pour ses vignobles. Parmi les imposants châteaux de la vallée, citons le château de Saumur, un fort médiéval perché sur une colline abritant un musée dédié à la ville, et celui de Brissac, doté de 7 étages et à la décoration d\'époque. Nantes, la préfecture de la région, compte le château à douves des ducs de Bretagne, transformé en musée.'),
(1, 'Bretagne', 48.2347, -2.87842, 8, 'La Bretagne, une région située à l’extrême ouest de la France, est une péninsule vallonnée qui s’avance dans l’océan Atlantique. Sa côte sauvage s’étend sur des kilomètres : on y trouve des stations balnéaires comme la ville chic de Dinard ou la ville fortifiée de Saint-Malo, construite sur la Manche. La côte de granit rose est un lieu convoité pour les teintes uniques que prennent le sable et les roches. La Bretagne dispose également d’un grand nombre de menhirs (sorte de mégalithe) datant de la préhistoire.'),
(3, 'Centre Val de Loire', 47.4035, 1.7688, 8, 'La région Centre-Val de Loire est traversée par la vallée de la Loire caractérisée par ses terres agricoles et ses châteaux somptueux. C\'est également une grande région vinicole connue surtout pour ses vins blancs tels que le Sancerre et le Pouilly-Fumé. La vallée de la Loire est relativement plate et sillonnée de pistes cyclables dont l\'itinéraire cyclotouristique de 800 km de long : La Loire à Vélo.'),
(22, 'Nouvelle Aquitaine', 44.7002, -0.299578, 8, 'Première région en termes d’emplois touristiques, elle bénéficie de la présence d’une vaste façade océanique allant des îles charentaises à l’embouchure de la Bidassoa, aux portes de l’Espagne, en passant par l’estuaire de la Gironde (plus grand estuaire sauvage d’Europe) et le Bassin d\'Arcachon. Ses plages, exposées à la houle, sont fréquentées chaque été par des millions de vacanciers, et sont également un haut-lieu du surf, dont certains spots, sur la côte basque ou sur l’île de Ré, jouissent d’une solide réputation.');

-- --------------------------------------------------------

--
-- Structure de la table `reservations_hebergement`
--

DROP TABLE IF EXISTS `reservations_hebergement`;
CREATE TABLE IF NOT EXISTS `reservations_hebergement` (
  `idReservationHebergement` int(11) NOT NULL AUTO_INCREMENT,
  `code_reservation` varchar(100) NOT NULL,
  `prix` decimal(7,2) NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `nbJours` int(11) DEFAULT NULL,
  `idVoyage` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `idHebergement` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReservationHebergement`)
) ENGINE=MyISAM AUTO_INCREMENT=171 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reservations_hebergement`
--

INSERT INTO `reservations_hebergement` (`idReservationHebergement`, `code_reservation`, `prix`, `dateDebut`, `dateFin`, `nbJours`, `idVoyage`, `idUtilisateur`, `idHebergement`) VALUES
(96, 'azerty', '135.00', '2021-10-23', '2021-10-26', 3, 54, 1, 12),
(95, 'azerty', '330.00', '2021-11-03', '2021-11-09', 6, 53, 5, 11),
(94, 'azerty', '120.00', '2021-10-27', '2021-10-29', 2, 52, 4, 13),
(93, 'azerty', '90.00', '2021-10-13', '2021-10-15', 2, 51, 3, 12),
(92, 'azerty', '100.00', '2021-10-11', '2021-10-13', 2, 51, 3, 9),
(136, 'HEB12254', '49.00', '2021-10-21', '2021-10-22', 1, 67, 13, 20),
(89, 'azerty', '270.00', '2021-10-21', '2021-10-27', 6, 50, 2, 1),
(88, 'azerty', '180.00', '2021-10-17', '2021-10-21', 4, 50, 2, 6),
(87, 'azerty', '100.00', '2021-10-13', '2021-10-17', 4, 50, 2, 3),
(86, 'azerty', '250.00', '2021-10-08', '2021-10-13', 5, 50, 2, 2),
(85, 'azerty', '45.00', '2021-10-04', '2021-10-05', 1, 50, 2, 1),
(97, 'azerty', '90.00', '2021-10-26', '2021-10-28', 2, 54, 1, 6),
(135, 'HEB24537', '225.00', '2021-10-28', '2021-11-02', 5, 66, 1, 12),
(134, 'HEB15903', '580.00', '2021-10-18', '2021-10-28', 10, 66, 1, 5),
(111, 'azerty', '469.00', '2021-10-14', '2021-10-18', 4, 59, 5, 1),
(112, 'azerty', '200.00', '2021-10-19', '2021-10-21', 2, 59, 5, 11),
(137, 'HEB51245', '174.00', '2021-10-22', '2021-10-24', 2, 67, 13, 21),
(138, 'HEB64299', '65.00', '2021-10-24', '2021-10-25', 1, 67, 13, 22),
(139, 'HEB91356', '514.00', '2021-10-25', '2021-10-27', 2, 67, 13, 17),
(140, 'HEB75788', '136.00', '2021-10-27', '2021-10-29', 2, 67, 13, 18),
(141, 'HEB75544', '156.00', '2021-10-29', '2021-10-30', 1, 67, 13, 15),
(142, 'HEB51383', '172.00', '2021-10-30', '2021-11-01', 2, 67, 13, 16),
(143, 'HEB81508', '99.00', '2021-11-01', '2021-11-02', 1, 67, 13, 23),
(144, 'HEB41567', '43.00', '2021-11-02', '2021-11-03', 1, 67, 13, 24),
(145, 'HEB37447', '198.00', '2021-11-02', '2021-11-04', 2, 66, 1, 23),
(146, 'HEB28670', '340.00', '2021-11-04', '2021-11-09', 5, 66, 1, 18),
(147, 'HEB12345', '330.00', '2021-10-12', '2021-10-15', 3, 68, 1, 1),
(151, 'azerty', '270.00', '2022-02-08', '2022-02-14', 6, 70, 1, 1),
(150, 'azerty', '270.00', '2021-12-08', '2021-12-14', 6, 70, 1, 1),
(152, 'HEB99523', '58.00', '2021-11-07', '2021-11-08', 1, 71, 1, 5),
(153, 'HEB20306', '264.00', '2021-11-08', '2021-11-19', 11, 71, 1, 9),
(154, 'HEB53267', '348.00', '2021-11-19', '2021-11-23', 4, 71, 1, 21),
(155, 'HEB36141', '504.00', '2021-11-23', '2021-12-01', 8, 71, 1, 6),
(156, 'HEB56234', '398.00', '2022-01-04', '2022-01-12', 8, 50, 2, 1),
(157, 'azerty', '45.00', '2022-02-09', '2022-02-10', 1, 50, 2, 1),
(158, 'HEB12345', '330.00', '2022-04-13', '2022-04-16', 3, 68, 1, 1),
(159, 'azerty', '469.00', '2022-06-14', '2022-06-18', 4, 59, 5, 1),
(160, 'HEB56234', '750.00', '2021-05-10', '2021-05-25', 15, 72, 2, 1),
(161, 'HEB56234', '500.00', '2021-06-15', '2021-06-25', 10, 72, 2, 1),
(162, 'HEB56234', '600.00', '2020-08-05', '2020-08-18', 13, 72, 2, 1),
(163, 'HEB56234', '350.00', '2021-08-17', '2021-08-23', 6, 72, 2, 1),
(164, 'HEB90191', '801.00', '2021-12-01', '2021-12-10', 9, 71, 1, 2),
(166, 'HEB38936', '490.00', '2021-11-30', '2021-12-07', 7, 74, 1, 1),
(167, 'HEB93994', '464.00', '2021-12-06', '2021-12-14', 8, 75, 1, 5),
(168, 'HEB31888', '792.00', '2021-12-14', '2021-12-26', 12, 75, 1, 11),
(169, 'HEB52897', '264.00', '2021-12-26', '2021-12-29', 3, 75, 1, 26),
(170, 'HEB31487', '445.00', '2021-12-29', '2022-01-03', 5, 75, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `reservations_voyages`
--

DROP TABLE IF EXISTS `reservations_voyages`;
CREATE TABLE IF NOT EXISTS `reservations_voyages` (
  `idReservationVoyage` int(11) NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int(11) NOT NULL,
  `prix` decimal(7,2) NOT NULL,
  `code_reservation` char(255) DEFAULT NULL,
  `is_building` tinyint(1) NOT NULL,
  PRIMARY KEY (`idReservationVoyage`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reservations_voyages`
--

INSERT INTO `reservations_voyages` (`idReservationVoyage`, `idUtilisateur`, `prix`, `code_reservation`, `is_building`) VALUES
(54, 1, '225.00', 'azerty', 0),
(53, 5, '330.00', 'azerty', 0),
(52, 4, '120.00', 'azerty', 0),
(51, 3, '370.00', 'azerty', 0),
(50, 2, '980.00', 'azerty', 0),
(66, 1, '1343.00', 'VOY79744', 0),
(59, 5, '330.00', 'azerty', 0),
(67, 13, '1408.00', 'VOY34177', 0),
(68, 1, '225.00', 'VOY12345', 0),
(70, 1, '225.00', 'VOY15634', 0),
(71, 1, '1975.00', 'VOY47837', 0),
(72, 2, '2250.00', 'VOY15659', 0),
(74, 1, '490.00', 'VOY95622', 0),
(75, 1, '1965.00', 'VOY69747', 1);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`idRole`, `libelle`) VALUES
(1, 'User'),
(2, 'admin'),
(3, 'proprietaire');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `idRole` int(11) NOT NULL,
  `acceptRGPD` tinyint(1) NOT NULL,
  `dateAcceptRGPD` date DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `age` int(3) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`idUtilisateur`, `email`, `mdp`, `nom`, `prenom`, `idRole`, `acceptRGPD`, `dateAcceptRGPD`, `DoB`, `age`, `token`) VALUES
(14, 'mrtreflestremy@outlook.yopmail', '$2y$10$ouClRgiNZfKfDgUjekRhoeirM0GGYHgxZdGrVkvPrT2V6zMo6vHhG', 'test', 'test', 1, 1, '2021-10-19', '1991-06-18', 23, NULL),
(4, 'jacobLoubi@hotmail.fr', '001e8d5e7f1d00929d3f5944cabdd66f', 'Jacob', 'Lacroix', 1, 1, '2021-10-19', '1991-06-18', 54, NULL),
(2, 'keitaro-negi@hotmail.fr', '$2y$10$IsucYi.732Pu4drRmKpegupo4SoPvPUaJgGsiD7TuUIb/lsZI6aXy', 'Le Tavernier', 'Roger', 1, 1, '2021-10-18', '1991-06-18', 29, NULL),
(3, 'mrtreflestremy@outlook.fr', '$2y$10$TfWFjpwFg2PCvQda1lL1quZadA7I9EjxpXEukd2r02nX5.Z9IsptW', ' Lacroix', 'Bertrand', 1, 1, '2021-10-09', '1991-06-18', 30, NULL),
(1, 'mrtreflestremy@outlook.com', '$2y$10$TfWFjpwFg2PCvQda1lL1quZadA7I9EjxpXEukd2r02nX5.Z9IsptW', 'Treflest', 'Rémy', 2, 1, '2021-10-09', '1991-06-18', 30, NULL),
(15, 'yopmail@yopmail.com', 'azerty', 'Balkani', 'Eric', 1, 1, '2021-10-02', '1991-06-18', 55, NULL),
(16, 'test@test.fr', '$2a$11$tBUvC3yEuFzqTv/uHVtlA.8.lUGSLrF3QtmKW06bxj9H6pZVgXF/S', 'tiesto', 'tiesta', 3, 1, '2021-11-01', '1991-06-18', 23, NULL),
(17, 'clémentCheckSesFormulaire@validation.fr', '$2a$11$Gj/Zy52sQdduelJfUFgwCubsOha8F0rw4JSCRgwITb56eTDkNFrWK', 'Julia', 'Clément', 3, 1, '2021-11-02', '1991-06-18', 19, NULL),
(18, 'juliaTest@denouveau.fr', '$2a$11$a7./HMiH9CbF016os8Rdl.GuNCcJ9MnuHiT2wYzpmWFmKwUa2Q4ny', 'JuliaTest', 'ClementTest', 3, 1, '2021-11-02', '2002-07-17', 19, NULL),
(19, 'nouveau@outlook.com', '$2y$10$Cv.wwF5N9XTnGxtZSnQK2OVHcCv99Ftdlu2x6gRjptND6Jpz7YMey', 'nouveau', 'utilisateur', 1, 1, '2021-11-29', NULL, 23, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `villes`
--

DROP TABLE IF EXISTS `villes`;
CREATE TABLE IF NOT EXISTS `villes` (
  `idVille` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `idRegion` int(11) DEFAULT NULL,
  `description` text,
  `uuid` text,
  PRIMARY KEY (`idVille`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `villes`
--

INSERT INTO `villes` (`idVille`, `libelle`, `latitude`, `longitude`, `idRegion`, `description`, `uuid`) VALUES
(1, 'Brest', 47, -0.5, 1, NULL, '2a8000ebe83f3608cea331d8f68d047753639b9c099e1c8fb088338c8fd6daa6'),
(2, 'Quimper', 47, 0.5, 1, NULL, '9415fe0be41a62d97a7fe805c88bd184e27e2c7c03f81c026aa051ef2992c0ea'),
(3, 'Lorient', NULL, NULL, 1, NULL, ''),
(4, 'Saint-Brieux', NULL, NULL, 1, NULL, ''),
(5, 'Caen', NULL, NULL, NULL, NULL, ''),
(6, 'Lisieux', NULL, NULL, NULL, NULL, ''),
(7, 'Alençon', NULL, NULL, NULL, NULL, ''),
(8, 'Granville', NULL, NULL, NULL, NULL, ''),
(9, 'Orléans', NULL, NULL, 3, NULL, ''),
(10, 'Chartres', NULL, NULL, 3, NULL, ''),
(11, 'Le Mans', NULL, NULL, 3, NULL, ''),
(12, 'Tours', NULL, NULL, 3, NULL, ''),
(13, 'Nantes', 47.2184, -1.55362, 2, NULL, '9b1d786adc42678cd5fe1ecd5e79032a259c52747e4d14c2248ca9c7cb70d0ad'),
(14, 'Angers', 47.4711, -0.547307, 2, NULL, '11539904062a636cdce19cebbbddd21cc90ef526074f308a57a46efe6e15569c'),
(15, 'Montaigu', 46.9833, -1.3167, 2, NULL, '0bc741d18209d385b105da6436ee86d40d8c5a7479aeb9b1ba29cef211f3a747'),
(16, 'Agen', NULL, NULL, NULL, NULL, ''),
(17, 'Cholet', 47.0594, -0.879787, 2, NULL, '298832a6b17b152006d9704a7e8c26533d4d20ab584eadda0fdaacb82e0879a7'),
(19, 'La Roche sur Yon', 46.6705, -1.42697, 2, NULL, '8157a057441e8921ad3b474ab3e4201bfa9970708dfe68db36bb2d3120914be1'),
(20, 'Saumur', 47.2596, -0.0785177, 2, NULL, 'fd40cce8c261f9db2fb7c0fac9403adf29baea97106d2900a73da2fe0eae7b15'),
(21, 'Pornic', 47.1153, -2.10401, 2, NULL, '2cb6bcb101e86f3ad4b88e2750d4dc191e77bd169bf4b079fa8bf67b0ddd901a'),
(22, 'La Flèche', 47.6968, -0.0746149, 2, NULL, '49aabb5dfc7ddedcf980566f1f28da2c40a16acc73d48a8c177c0195cfe31545');
COMMIT;
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activites`
--
ALTER TABLE `activites`
  ADD PRIMARY KEY (`idActivite`);

--
-- Index pour la table `agences`
--
ALTER TABLE `agences`
  ADD PRIMARY KEY (`idAgence`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`idAvis`);

--
-- Index pour la table `avis_response`
--
ALTER TABLE `avis_response`
  ADD PRIMARY KEY (`idResponse`),
  ADD UNIQUE KEY `idAvis` (`idAvis`);

--
-- Index pour la table `consulter_messages`
--
ALTER TABLE `consulter_messages`
  ADD PRIMARY KEY (`idUtilisateur`,`idMessage`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`idHebergement`,`idUtilisateur`);

--
-- Index pour la table `hebergement`
--
ALTER TABLE `hebergement`
  ADD PRIMARY KEY (`idHebergement`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idMessage`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`idOption`);

--
-- Index pour la table `options_by_hebergement`
--
ALTER TABLE `options_by_hebergement`
  ADD PRIMARY KEY (`idHebergement`,`idOption`);

--
-- Index pour la table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`idRegion`);

--
-- Index pour la table `reservations_hebergement`
--
ALTER TABLE `reservations_hebergement`
  ADD PRIMARY KEY (`idReservationHebergement`);

--
-- Index pour la table `reservations_voyages`
--
ALTER TABLE `reservations_voyages`
  ADD PRIMARY KEY (`idReservationVoyage`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRole`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `villes`
--
ALTER TABLE `villes`
  ADD PRIMARY KEY (`idVille`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activites`
--
ALTER TABLE `activites`
  MODIFY `idActivite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `agences`
--
ALTER TABLE `agences`
  MODIFY `idAgence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `idAvis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `avis_response`
--
ALTER TABLE `avis_response`
  MODIFY `idResponse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `hebergement`
--
ALTER TABLE `hebergement`
  MODIFY `idHebergement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `idOption` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `regions`
--
ALTER TABLE `regions`
  MODIFY `idRegion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `reservations_hebergement`
--
ALTER TABLE `reservations_hebergement`
  MODIFY `idReservationHebergement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT pour la table `reservations_voyages`
--
ALTER TABLE `reservations_voyages`
  MODIFY `idReservationVoyage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `villes`
--
ALTER TABLE `villes`
  MODIFY `idVille` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
