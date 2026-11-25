-- Base de données Atelier Lumen
-- Ce script recrée la base au complet (tables + données).
-- Il peut être relancé à tout moment : l'ancienne base est supprimée d'abord.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `atelier_lumen`;
CREATE DATABASE `atelier_lumen` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `atelier_lumen`;

START TRANSACTION;

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` tinyint(4) NOT NULL,
  `nom` varchar(50) NOT NULL COMMENT 'Nom en français',
  `nom_en` varchar(50) NOT NULL COMMENT 'Nom en anglais'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `nom_en`) VALUES
(1, 'Bougies', 'Candles'),
(2, 'Céramique', 'Ceramics'),
(3, 'Bois', 'Wood'),
(4, 'Lin & textile', 'Linen & textiles');

-- --------------------------------------------------------

--
-- Table structure for table `panier`
--

CREATE TABLE `panier` (
  `id` mediumint(9) NOT NULL,
  `numero` char(30) NOT NULL,
  `ddm` date NOT NULL COMMENT 'Date de dernière modification du panier par le visiteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cette table contient les chariots pris par les visiteurs du ';

-- --------------------------------------------------------

--
-- Table structure for table `panier_article`
--

CREATE TABLE `panier_article` (
  `id` int(11) NOT NULL,
  `id_produit` smallint(6) NOT NULL,
  `id_panier` mediumint(9) NOT NULL,
  `qte` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cette table contiendra les articles du panier pour chaque ut';

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` smallint(6) NOT NULL,
  `nom` varchar(100) NOT NULL COMMENT 'Nom en français',
  `nom_en` varchar(100) NOT NULL COMMENT 'Nom en anglais',
  `prix` decimal(5,2) NOT NULL,
  `ventes` smallint(6) NOT NULL,
  `dac` date NOT NULL,
  `image` varchar(25) NOT NULL COMMENT 'Nom complet du fichier image',
  `id_categorie` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `nom_en`, `prix`, `ventes`, `dac`, `image`, `id_categorie`) VALUES
(1, 'Bougie Feu de camp', 'Campfire Candle', 28.00, 142, '2026-09-14', 'bougie-feu-de-camp.jpg', 1),
(2, 'Bougie Bouleau', 'Birch Candle', 26.00, 87, '2026-10-02', 'bougie-bouleau.jpg', 1),
(3, 'Chandelles en cire d\'abeille (trio)', 'Beeswax Taper Candles (set of 3)', 22.00, 203, '2026-06-21', 'chandelles-abeille.jpg', 1),
(4, 'Lanterne d\'atelier', 'Studio Lantern', 64.00, 38, '2026-11-03', 'lanterne-atelier.jpg', 1),
(5, 'Gobelet Mousse en grès', 'Moss Stoneware Tumbler', 34.00, 96, '2026-08-30', 'gobelet-mousse.jpg', 2),
(6, 'Tasses mouchetées (paire)', 'Speckled Mugs (pair)', 48.00, 121, '2026-07-12', 'tasses-mouchetees.jpg', 2),
(7, 'Vase Argile', 'Clay Vase', 58.00, 64, '2026-10-19', 'vase-argile.jpg', 2),
(8, 'Assiette en grès', 'Stoneware Plate', 32.00, 77, '2026-05-25', 'assiette-gres.jpg', 2),
(9, 'Planche à servir en érable', 'Maple Serving Board', 68.00, 110, '2026-04-18', 'planche-erable.jpg', 3),
(10, 'Ustensiles en cerisier', 'Cherry Wood Utensils', 42.00, 58, '2026-09-27', 'ustensiles-cerisier.jpg', 3),
(11, 'Bol en noyer tourné', 'Turned Walnut Bowl', 78.00, 41, '2026-11-10', 'bol-noyer.jpg', 3),
(12, 'Sous-verres en chêne (4)', 'Oak Coasters (set of 4)', 30.00, 166, '2026-03-08', 'sous-verres-bois.jpg', 3),
(13, 'Jeté en lin naturel', 'Natural Linen Throw', 96.00, 52, '2026-10-25', 'jete-lin.jpg', 4),
(14, 'Torchon rayé en lin', 'Striped Linen Tea Towel', 18.00, 188, '2026-02-14', 'torchon-raye.jpg', 4),
(15, 'Couverture en laine', 'Wool Blanket', 135.00, 29, '2026-11-17', 'couverture-laine.jpg', 4),
(16, 'Serviettes de table en lin (4)', 'Linen Napkins (set of 4)', 44.00, 73, '2026-08-05', 'serviettes-lin.jpg', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Indexes for table `panier_article`
--
ALTER TABLE `panier_article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_produit_2` (`id_produit`,`id_panier`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_panier` (`id_panier`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`,`id_categorie`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panier_article`
--
ALTER TABLE `panier_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `panier_article`
--
ALTER TABLE `panier_article`
  ADD CONSTRAINT `panier_article_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `panier_article_ibfk_2` FOREIGN KEY (`id_panier`) REFERENCES `panier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`);
COMMIT;
