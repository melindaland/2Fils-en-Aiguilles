-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 22 déc. 2024 à 10:25
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sae301`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) DEFAULT NULL,
  `titre` varchar(128) DEFAULT NULL,
  `redacteur` varchar(512) DEFAULT NULL,
  `accroche` text,
  `image` varchar(512) DEFAULT NULL,
  `id_cours` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `ordre`, `titre`, `redacteur`, `accroche`, `image`, `id_cours`) VALUES
(2, 2, 'blabbla', 'melmel', '', '67631f3b9a7236.88585628.jpg', NULL),
(3, 1, 'melida', 'wewewewe', 'wewewewe', '6761527684b6d0.71264962.jpg', NULL),
(4, 5, 'hhh', 'hhh', 'Saisir une accrocheuuuu', '', NULL),
(5, 1, 'hhhh', 'hhh', 'Saisir une accroche', '', NULL),
(6, 3, 'dada', 'dadada', 'dadadada', '6762d55e480ac5.78256809.jpg', NULL),
(7, 4, 'abzfvghafé', 'éfeéfeéfeé', 'fazfafzafafzafafa', '', NULL),
(8, 2, 'ffefefefz', 'fzefzf', 'fzfzef', '67652a11086834.54907505.jpg', NULL),
(10, 1, 'f&quot;f&quot;f', '', 'f&quot;f&quot;f&quot;f&quot;f&quot;', '676533df2316e4.50665469.jpg', NULL),
(11, 3, 'dzdadza', 'dzadada', 'Saisir unedaddadadad accroche', '676589baed17f1.56125886.jpg', NULL),
(12, 4, 'beébz dz', 'dazb dbza dbza', 'dazdba dbv az', '6766fcbc79df45.44833573.jpg', NULL),
(13, 2, 'dézdézd', 'dzdaazd', 'dzazza', '6766df9ca8e7f3.08771931.jpg', NULL),
(14, 3, 'rienrr', 'rienrrr', 'rrrrrrrrr', '6766e100a7b1d4.80034797.jpg', NULL),
(15, 1, 'Professionnel', 'Wanani Gradi', 'Passionnée de couture', '67674e5c0c1e98.65308585.jpg', 9),
(16, 2, 'Durée', 'Wanani Gradi', '5 à 7 h', '67674eb1717473.74443349.jpg', 9),
(17, 1, 'Professionnel', 'Kassim Djae', 'J&amp;#039;aime la couture', '67675113996098.25460433.jpg', 10),
(18, 2, 'Durée', 'Kassim Djae', '3-6h', '67675148edbe74.17826035.jpg', 10),
(19, 1, 'Professionnel', 'Vanessa', 'I give u some tips', '676753943f34f1.81063475.jpg', 11),
(20, 2, 'Durée', 'Vanessa', '2 à 6h', '676753af3ac5e1.22046692.jpg', 11),
(21, 1, 'Professionnel', 'Julien Mari', 'Couture sur vous', '6767542c7ea659.37626090.jpg', 12),
(22, 2, 'Durée', 'Julien Mari', 'Tous les samedis', '6767544e164447.70572951.jpg', 12),
(23, 1, 'Professionnel', 'Valentin Le Du', 'La couture c&#039;est vrm ma passion ', '676754ea22bb49.39016772.jpg', 13),
(24, 2, 'Durée', 'Valentin Le Du', 'Jeudi de 8h à 10h', '676755052c5491.62226054.jpg', 13),
(25, 1, 'Professionnel', 'Élie', 'Vive la couture enfait', '6767e8323f0831.65841325.jpg', 14),
(26, 2, 'Durée', 'Élie', 'Le cours se déroule sur 4 séances de 2 heures chacune.\r\nLes séances sont programmées chaque semaine le mardi et le jeudi, de 18h à 20h.', '6767e850edfa00.08908198.jpg', 14);

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(128) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `image` varchar(512) DEFAULT NULL,
  `niveau` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `nom`, `description`, `image`, `niveau`) VALUES
(9, 'Les bases de la couture', 'Ce cours est destiné aux personnes qui souhaitent s&#039;initier à la couture, qu&#039;elles aient déjà touché une aiguille ou qu&#039;elles partent totalement de zéro. Vous découvrirez les techniques fondamentales pour coudre à la main et à la machine, comprendre les outils essentiels et réaliser vos premiers projets simples avec confiance.', '67674cfb221c86.31397701.jpg', 'debutant'),
(10, 'Créer et Réparer ses Vêtements du Quotidien', 'Ce cours s&amp;#039;adresse aux débutants souhaitant apprendre à créer de petites pièces vestimentaires simples ou à réparer leurs vêtements abîmés. Que ce soit pour coudre un bouton, réparer un ourlet ou réaliser un tote bag, ce cours pratique vous donnera les compétences essentielles pour devenir plus autonome avec vos vêtements.', '67674fe126d286.62541670.jpg', 'debutant'),
(11, 'Techniques Avancées de Couture pour Projets Vestimentaires', 'Ce cours intermédiaire s’adresse aux personnes ayant déjà acquis les bases de la couture et souhaitant se lancer dans des projets plus complexes. Vous apprendrez à travailler différents types de tissus, à poser des fermetures éclair, et à créer des vêtements simples tels que des jupes, chemises ou pantalons décontractés.', '67675355d5e480.88277529.jpg', 'intermediaire'),
(12, 'Création de Vêtements Sur-Mesure', 'Ce cours avancé vous guide dans la création de vêtements parfaitement ajustés à la morphologie. Vous apprendrez à prendre des mesures précises, à adapter des patrons complexes et à travailler des tissus exigeants.', '676753f5603bb6.45436387.jpg', 'intermediaire'),
(13, 'Création d’Accessoires : Sacs et Chapeaux en Couture Avancée', 'Apprenez à confectionner des accessoires uniques, tels que des sacs structurés, des pochettes élégantes et même des chapeaux personnalisés. Projet final : Réalisez un sac structuré ou un chapeau élégant.', '6767549e794d49.90836722.jpg', 'avance'),
(14, 'Cours de Couture Niveau Avancé – Perfectionnez Vos Techniques !', 'Vous maîtrisez déjà les bases de la couture et souhaitez aller plus loin dans votre apprentissage ? Ce cours de couture de niveau avancé est fait pour vous ! Nous allons approfondir les techniques complexes et perfectionner vos compétences pour vous permettre de réaliser des créations plus ambitieuses. Le cours aborde des sujets comme la couture de tissus délicats, la réalisation de pièces ajustées, l’utilisation de machines à coudre professionnelles et l’assemblage de projets plus complexes.', '6767e74c71d230.76305388.jpg', 'avance');

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

CREATE TABLE `materiel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(128) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `image` varchar(512) DEFAULT NULL,
  `categorie` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `materiel`
--

INSERT INTO `materiel` (`id`, `nom`, `description`, `image`, `categorie`) VALUES
(8, ' Machine à Coudre Professionnelle', 'Je suis Camille, passionnée de couture depuis des années, et je sais à quel point avoir du bon matériel peut changer la donne pour un projet. C’est pourquoi je propose à la location une machine à coudre professionnelle pour que tu puisses réaliser tes créations sans avoir à investir une fortune.', '676755dfbe1c16.53628064.jpeg', 'machineacoudre'),
(9, 'Kit de couture ', 'Bonjour, je mets en location mon kit de couture complet. Il contient tout le nécessaire pour vos projets créatifs : aiguilles, fils de différentes couleurs, ciseaux, épingles, mètres rubans, désosses et bien plus encore. Que vous soyez débutant ou expérimenté, ce kit vous accompagnera dans la réalisation de toutes vos créations.', '6767e32b8f5321.08493114.jpg', 'kitdecouture'),
(10, 'Mètre Ruban – Prêt à l&amp;#039;Emploi !', 'Bonjour, je mets en location mon mètre ruban de couture. Pratique et précis, il est idéal pour toutes vos mesures, que ce soit pour la couture, le tricot ou toute autre activité manuelle nécessitant des mesures exactes. Il est souple, léger et facile à transporter.', '6767e40d970a43.54020878.jpeg', 'metreruban'),
(11, 'Location de Ciseaux de Couture', 'Bonjour, je mets en location une paire de ciseaux de couture professionnels. Idéals pour couper facilement tous types de tissus, ils offrent une coupe nette et précise. Leur prise en main est confortable, même pour une utilisation prolongée. Ces ciseaux sont parfaits pour vos projets de couture, qu&#039;ils soient débutants ou avancés.', '6767e55463c6c7.56330198.jpg', 'ciseaux'),
(12, 'Location de Bobine de Fil ', 'Bonjour, je propose la location d&#039;une bobine de fil de couture de haute qualité, idéale pour tous vos projets de couture. Que vous soyez débutant ou expérimenté, ce fil solide et résistant est parfait pour coudre différents types de tissus. Disponible en plusieurs couleurs, il peut être utilisé pour des coutures simples ou des finitions plus élaborées.', '6767e5fb9f1394.14543386.jpg', 'bobine'),
(13, 'Ciseaux de Couture – Pour des Coupes Précises !', 'Bonjour, je mets à disposition un ciseau de couture professionnel pour vos projets créatifs. Ce ciseau est spécialement conçu pour couper proprement et facilement tous types de tissus, du coton à la laine, en passant par la soie. Il possède des lames tranchantes qui permettent une coupe nette et précise, indispensable pour un travail de couture de qualité.', '6767e6a6558bd2.38168150.jpeg', 'ciseaux');

-- --------------------------------------------------------

--
-- Structure de la table `objet`
--

CREATE TABLE `objet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) DEFAULT NULL,
  `titre` varchar(128) DEFAULT NULL,
  `redacteur` varchar(512) DEFAULT NULL,
  `accroche` text,
  `image` varchar(512) DEFAULT NULL,
  `id_materiel` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `objet`
--

INSERT INTO `objet` (`id`, `ordre`, `titre`, `redacteur`, `accroche`, `image`, `id_materiel`) VALUES
(1, 1, 'vrvevre', 'vevever', 'veveveverver', '', NULL),
(2, 1, 'melindaaaa', 'f&amp;amp;quot;f&amp;amp;quot;', 'f&amp;amp;quot;f&amp;amp;quot;f&amp;amp;quot;f&amp;amp;quot;', '6765cb89cbb668.02055919.jpg', NULL),
(3, 1, 'cezcz', 'cezcezc', 'zeczczcz', '6765e263281086.54140780.jpg', NULL),
(19, 2, 'szsz', 'szsz', 'szszsz', '6766eff3269c74.65388932.jpg', NULL),
(21, 3, 'dzdz', 'dzdzd', 'dzdzdzdzzd', '6766f061de3369.09626262.jpg', NULL),
(23, 4, 'blabla', 'mel', 'bebbebe', '6766f10d566ee5.40406752.jpg', NULL),
(24, 5, 'test', 'gsgsgsgs', 'gsggsgsgsg', '6766f14ac2d701.56514300.jpg', NULL),
(25, 1, 'Conseil d&#039;utilisation', 'Camille', 'Utilisez un fil de bonne qualité, adapté à la matière du tissu.', '676756f546c074.55691638.jpg', 8),
(26, 2, 'Durée de l&#039;emprunt', 'Camille', '15 jours', '676757138764f2.07763766.png', 8),
(27, 1, 'Conseil d&#039;utilisation', 'Kaaris', 'Avant de commencer vos projets, je vous conseille de tester vos aiguilles et fils sur un petit morceau de tissu pour vous assurer qu&#039;ils sont adaptés. ', '6767e3520ce6f3.78509615.jpg', 9),
(28, 2, 'Durée de l&#039;emprunt', 'Kaaris', 'Vous pouvez emprunter le kit pour une période de 7 à 30 jours. Si vous avez besoin de plus de temps, contactez-moi pour prolonger la durée de l&#039;emprunt.', '6767e373002128.88936108.png', 9),
(29, 1, 'Conseil d&#039;utilisation', 'Julien Schwarzer', 'Avant de commencer, assurez-vous que le mètre ruban est bien tendu pour une mesure précise. Si vous devez mesurer des surfaces courbes, placez le mètre ruban contre la surface sans le tordre, pour obtenir une mesure fiable.', '6767e444a821b4.45408735.jpg', 10),
(30, 2, 'Durée de l&#039;emprunt', 'Julien Schwarzer', 'Vous pouvez emprunter le mètre ruban pour une période de 3 à 14 jours. Si vous avez besoin de plus de temps, contactez-moi pour prolonger l&#039;emprunt.', '6767e46b340423.30676872.png', 10),
(31, 1, 'Conseil d&#039;utilisation', 'Stephanie', 'Pour garantir une coupe optimale, utilisez ces ciseaux uniquement pour la couture. Ne les utilisez pas pour couper des matériaux trop épais ou autres que des tissus.', '6767e5786015e0.24754073.jpg', 11),
(32, 2, 'Durée de l&#039;emprunt', 'Stephanie', 'Vous pouvez emprunter les ciseaux pour une durée de 3 à 14 jours.', '6767e58cbba403.44833249.png', 11),
(33, 1, 'Conseil d&#039;utilisation', 'Nabil Arlabelek', 'Veillez à utiliser la bobine de fil pour des travaux de couture classiques, et à l&#039;enrouler soigneusement après chaque utilisation pour éviter qu&#039;il ne s&#039;emmêle. ', '6767e6262bbc93.76769304.jpg', 12),
(34, 2, 'Durée de l&#039;emprunt', 'Nabil Arlabelek', 'Disponible pour une durée de 3 à 10 jours. ', '6767e64563cde7.64077546.png', 12),
(35, 1, 'Conseil d&#039;utilisation', 'Walid', 'Pour garantir une coupe optimale, veillez à utiliser les ciseaux uniquement pour couper du tissu. Ne les utilisez pas pour couper du papier ou d&#039;autres matériaux, afin de préserver leur tranchant.', '6767e6ea8f8456.26835172.jpg', 13),
(36, 2, 'Durée de l&#039;emprunt', 'Walid', 'Les ciseaux de couture sont disponibles pour une durée de 3 à 10 jours.', '6767e6feb2f477.66192106.png', 13);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_theme` (`id_cours`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `objet`
--
ALTER TABLE `objet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_materiel` (`id_materiel`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `materiel`
--
ALTER TABLE `materiel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `objet`
--
ALTER TABLE `objet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `objet`
--
ALTER TABLE `objet`
  ADD CONSTRAINT `objet_ibfk_1` FOREIGN KEY (`id_materiel`) REFERENCES `materiel` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
