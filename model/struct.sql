
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données :  `p1914362`
--

-- --------------------------------------------------------

--
-- Structure de la table `Player`
--
DROP TABLE IF EXISTS `Player`;

CREATE TABLE `Player` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `money` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Player`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

--
-- Structure de la table `Game`
--

DROP TABLE IF EXISTS `Game`;

CREATE TABLE `Game` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `bet` int(11) NOT NULL,
  `profit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Game`
  ADD KEY `fk_id` (`id`);

ALTER TABLE `Game`
  ADD CONSTRAINT `fk_id` FOREIGN KEY (`id`) REFERENCES `Player` (`id`);
COMMIT;
