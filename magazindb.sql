-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 04, 2023 at 01:07 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magazindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `atribute_produs`
--

DROP TABLE IF EXISTS `atribute_produs`;
CREATE TABLE IF NOT EXISTS `atribute_produs` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `cod_produs` int NOT NULL,
  `cod_marime` int NOT NULL,
  `cod_culoare` int NOT NULL,
  `cantitate` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_culoare` (`cod_culoare`),
  KEY `cod_marime` (`cod_marime`),
  KEY `cod_produs` (`cod_produs`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `atribute_produs`
--

INSERT INTO `atribute_produs` (`ID`, `cod_produs`, `cod_marime`, `cod_culoare`, `cantitate`) VALUES
(11, 1, 8, 1, 10),
(12, 1, 9, 1, 11),
(15, 2, 8, 2, 4),
(16, 2, 9, 2, 4),
(18, 3, 5, 3, 0),
(19, 3, 2, 3, 6),
(20, 3, 3, 3, 8),
(21, 3, 4, 3, 16),
(22, 3, 6, 3, 6),
(23, 3, 7, 3, 6),
(25, 4, 11, 3, 5),
(27, 1, 10, 1, 5),
(28, 2, 10, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `branduri`
--

DROP TABLE IF EXISTS `branduri`;
CREATE TABLE IF NOT EXISTS `branduri` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `branduri`
--

INSERT INTO `branduri` (`ID`, `denumire`) VALUES
(1, 'Nike'),
(2, 'Puma'),
(3, 'Adidas'),
(4, 'Denim'),
(5, 'Calvin Klein');

-- --------------------------------------------------------

--
-- Table structure for table `categorii`
--

DROP TABLE IF EXISTS `categorii`;
CREATE TABLE IF NOT EXISTS `categorii` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorii`
--

INSERT INTO `categorii` (`ID`, `denumire`) VALUES
(1, 'Incaltaminte'),
(3, 'Haine'),
(4, 'Acesorii');

-- --------------------------------------------------------

--
-- Table structure for table `clienti`
--

DROP TABLE IF EXISTS `clienti`;
CREATE TABLE IF NOT EXISTS `clienti` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nume` varchar(32) NOT NULL,
  `prenume` varchar(64) NOT NULL,
  `adresa` varchar(100) NOT NULL,
  `cod_judet` int NOT NULL,
  `localitate` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `zipcode` varchar(64) NOT NULL,
  `telefon` varchar(12) NOT NULL,
  `date_facturare` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cod_utilizator` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_judet` (`cod_judet`),
  KEY `cod_utilizator` (`cod_utilizator`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clienti`
--

INSERT INTO `clienti` (`ID`, `nume`, `prenume`, `adresa`, `cod_judet`, `localitate`, `zipcode`, `telefon`, `date_facturare`, `cod_utilizator`) VALUES
(23, 'lexxerr', 'mere', 'Str. Tudor Vladimirescu,nr.39', 4, 'ilfov', '007327', '0728370070', 'lexxerr mere, Str. Tudor Vladimirescu,nr.39, București , ilfov , 007327', 2),
(24, 'ilie', 'andrei', 'Str.Adamanti,nr.221', 4, 'ilfov', '007327', '0739242290', 'ilie andrei, Str.Adamanti,nr.221, București , ilfov , 007327', 6),
(25, 'ilie', 'andrei', 'Str.Adamanti,nr.221', 4, 'ilfov', '007327', '0739242290', 'ilie andrei, Str.Adamanti,nr.221, București , ilfov , 007327', 6),
(26, 'Alexandru', 'loli', 'Str.Pictor nicolae grigorescu, 39', 24, 'Branesti', '077030', '0728370070', 'Alexandru loli, Str.Pictor nicolae grigorescu, 39, Ilfov , Branesti , 077030', 1),
(27, 'Alex', 'Adi', 'Str sorilor,34', 10, 'Brasov', '272271', '7286775474', 'Alex Adi, Str sorilor,34, Brașov , Brasov , 272271', 1),
(28, 'Alex', 'Adi', 'Str sorilor,34', 10, 'Brasov', '272271', '7286775474', 'Alex Adi, Str sorilor,34, Brașov , Brasov , 272271', 1),
(29, 'Alex', 'Adi', 'Str sorilor,34', 10, 'Brasov', '272271', '7286775474', 'Alex Adi, Str sorilor,34, Brașov , Brasov , 272271', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comenzi`
--

DROP TABLE IF EXISTS `comenzi`;
CREATE TABLE IF NOT EXISTS `comenzi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `cod_client` int NOT NULL,
  `pret_total` float NOT NULL,
  `cod_status` int NOT NULL,
  `data` datetime NOT NULL,
  `metoda_plata` enum('Ramburs la livrare','Card bancar') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '1(ramburs),2(card)',
  PRIMARY KEY (`ID`),
  KEY `cod_client` (`cod_client`),
  KEY `cod_status` (`cod_status`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comenzi`
--

INSERT INTO `comenzi` (`ID`, `cod_client`, `pret_total`, `cod_status`, `data`, `metoda_plata`) VALUES
(9, 23, 252, 5, '2023-04-10 12:15:20', 'Ramburs la livrare'),
(10, 24, 130, 5, '2023-04-11 13:10:22', 'Ramburs la livrare'),
(11, 25, 130, 5, '2023-04-11 13:11:40', 'Ramburs la livrare'),
(12, 26, 300, 4, '2023-05-09 11:53:24', 'Card bancar'),
(13, 27, 700, 4, '2023-05-29 08:44:19', 'Ramburs la livrare'),
(14, 28, 110, 4, '2023-05-29 08:48:56', 'Ramburs la livrare'),
(15, 29, 1325, 5, '2023-05-30 16:06:32', 'Ramburs la livrare');

-- --------------------------------------------------------

--
-- Table structure for table `comenzi_detalii`
--

DROP TABLE IF EXISTS `comenzi_detalii`;
CREATE TABLE IF NOT EXISTS `comenzi_detalii` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `cod_comanda` int NOT NULL,
  `cod_atribut_produs` int NOT NULL,
  `cantitate` int NOT NULL,
  `pret_unitar` float NOT NULL,
  `pret` float NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_atribut_produs` (`cod_atribut_produs`),
  KEY `cod_comanda` (`cod_comanda`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comenzi_detalii`
--

INSERT INTO `comenzi_detalii` (`ID`, `cod_comanda`, `cod_atribut_produs`, `cantitate`, `pret_unitar`, `pret`) VALUES
(7, 9, 11, 1, 222, 222),
(8, 9, 20, 3, 10, 30),
(9, 10, 16, 1, 100, 100),
(10, 10, 18, 3, 10, 30),
(11, 11, 16, 1, 1, 100),
(12, 11, 18, 3, 10, 30),
(13, 12, 25, 3, 100, 300),
(14, 13, 12, 1, 225, 225),
(15, 13, 11, 1, 225, 225),
(16, 13, 16, 1, 100, 100),
(17, 13, 19, 5, 10, 50),
(18, 13, 15, 1, 100, 100),
(19, 14, 20, 1, 10, 10),
(20, 14, 25, 1, 100, 100),
(21, 15, 11, 5, 225, 1125),
(22, 15, 15, 2, 100, 200);

-- --------------------------------------------------------

--
-- Table structure for table `comenzi_status`
--

DROP TABLE IF EXISTS `comenzi_status`;
CREATE TABLE IF NOT EXISTS `comenzi_status` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comenzi_status`
--

INSERT INTO `comenzi_status` (`ID`, `denumire`) VALUES
(1, 'Pending'),
(2, 'Processing'),
(3, 'Shipped'),
(4, 'Canceled'),
(5, 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `cos`
--

DROP TABLE IF EXISTS `cos`;
CREATE TABLE IF NOT EXISTS `cos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `cod_atribut_produs` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_atribut_produs` (`cod_atribut_produs`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cos`
--

INSERT INTO `cos` (`ID`, `user_id`, `cod_atribut_produs`, `quantity`) VALUES
(88, 2, 15, 1),
(91, 6, 11, 1),
(105, 1, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `culori`
--

DROP TABLE IF EXISTS `culori`;
CREATE TABLE IF NOT EXISTS `culori` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `culori`
--

INSERT INTO `culori` (`ID`, `denumire`) VALUES
(1, 'negru/rosu'),
(2, 'albastru/alb'),
(3, 'negru');

-- --------------------------------------------------------

--
-- Table structure for table `furnizori`
--

DROP TABLE IF EXISTS `furnizori`;
CREATE TABLE IF NOT EXISTS `furnizori` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(100) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `cod_judet` int NOT NULL,
  `IBAN` varchar(100) NOT NULL,
  `Banca` varchar(100) NOT NULL,
  `CIF` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_judet` (`cod_judet`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `furnizori`
--

INSERT INTO `furnizori` (`ID`, `denumire`, `adresa`, `cod_judet`, `IBAN`, `Banca`, `CIF`) VALUES
(1, 'Soft Clothers SRL', 'Str.Eroilor,nr39', 24, '12345657', 'Banca Transilvania', '123');

-- --------------------------------------------------------

--
-- Table structure for table `imagini`
--

DROP TABLE IF EXISTS `imagini`;
CREATE TABLE IF NOT EXISTS `imagini` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `cod_produs` int NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_produs` (`cod_produs`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `imagini`
--

INSERT INTO `imagini` (`ID`, `cod_produs`, `path`) VALUES
(5, 1, '444177759_png-clipart-shoe-nike-free-air-force-nike-shoes-image-file-formats-fashion-thumbnail.png'),
(8, 1, '190929617_image-removebg-preview (1).png');

-- --------------------------------------------------------

--
-- Table structure for table `intrarifacturi`
--

DROP TABLE IF EXISTS `intrarifacturi`;
CREATE TABLE IF NOT EXISTS `intrarifacturi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `serie` varchar(32) NOT NULL,
  `nr` varchar(32) NOT NULL,
  `cod_furnizor` int NOT NULL,
  `data` date NOT NULL,
  `TVA` varchar(32) NOT NULL,
  `nr_aviz` varchar(32) NOT NULL,
  `pret_total` float NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_furnizor` (`cod_furnizor`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `intrarifacturi`
--

INSERT INTO `intrarifacturi` (`ID`, `serie`, `nr`, `cod_furnizor`, `data`, `TVA`, `nr_aviz`, `pret_total`) VALUES
(24, 'IF', '001', 1, '2023-04-10', '19', '328', 3150),
(25, 'FFF', '4444', 1, '2023-04-28', '19', '1111', 3150),
(26, 'xd', '444', 1, '2023-05-23', '19', '222', 3150),
(27, 'XXX', '123', 1, '2023-05-23', '19', '222', 1450);

-- --------------------------------------------------------

--
-- Table structure for table `intrariproduse`
--

DROP TABLE IF EXISTS `intrariproduse`;
CREATE TABLE IF NOT EXISTS `intrariproduse` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `cod_factura` int NOT NULL,
  `cod_produs` int NOT NULL,
  `cantitate` int NOT NULL,
  `cod_marime` int NOT NULL,
  `cod_culoare` int NOT NULL,
  `pret_unitar` float NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_factura` (`cod_factura`),
  KEY `cod_marime` (`cod_marime`),
  KEY `cod_produs` (`cod_produs`),
  KEY `cod_culoare` (`cod_culoare`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `intrariproduse`
--

INSERT INTO `intrariproduse` (`ID`, `cod_factura`, `cod_produs`, `cantitate`, `cod_marime`, `cod_culoare`, `pret_unitar`) VALUES
(46, 24, 1, 10, 8, 1, 210),
(47, 24, 1, 10, 9, 1, 210),
(48, 24, 1, 10, 10, 1, 210),
(49, 24, 1, 10, 10, 3, 210),
(50, 24, 2, 10, 8, 2, 200),
(51, 24, 2, 10, 9, 2, 200),
(52, 24, 2, 10, 10, 2, 200),
(53, 24, 3, 10, 5, 3, 50),
(54, 24, 3, 15, 2, 3, 50),
(55, 24, 3, 20, 3, 3, 50),
(56, 24, 3, 25, 4, 3, 50),
(57, 24, 3, 15, 6, 3, 50),
(58, 24, 3, 10, 7, 3, 50),
(59, 25, 4, 5, 11, 3, 95),
(60, 26, 1, 5, 8, 1, 210),
(61, 26, 1, 5, 9, 1, 210),
(62, 26, 1, 5, 10, 1, 210),
(63, 27, 2, 5, 10, 2, 200),
(64, 27, 3, 10, 4, 3, 30),
(65, 27, 3, 5, 3, 3, 30);

-- --------------------------------------------------------

--
-- Table structure for table `judete`
--

DROP TABLE IF EXISTS `judete`;
CREATE TABLE IF NOT EXISTS `judete` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `judete`
--

INSERT INTO `judete` (`ID`, `denumire`) VALUES
(1, 'Alba'),
(2, 'Argeș'),
(3, 'Arad'),
(4, 'București'),
(5, 'Bacău'),
(6, 'Bihor'),
(7, 'Bistrița Năsăud'),
(8, 'Brăila'),
(9, 'Botoșani'),
(10, 'Brașov'),
(11, 'Buzău'),
(12, 'Cluj'),
(13, 'Călărași'),
(14, 'Caraș-Severin'),
(15, 'Constanța'),
(16, 'Covasna'),
(17, 'Dâmbovița'),
(18, 'Dolj'),
(19, 'Gorj'),
(20, 'Galați'),
(21, 'Giurgiu'),
(22, 'Hunedoara'),
(23, 'Harghita'),
(24, 'Ilfov'),
(25, 'Ialomița'),
(26, 'Iași'),
(27, 'Mehedinți'),
(28, 'Maramureș'),
(29, 'Mureș'),
(30, 'Neamț'),
(31, 'Olt'),
(32, 'Prahova'),
(33, 'Sibiu'),
(34, 'Sălaj'),
(35, 'Satu-Mare'),
(36, 'Suceava'),
(37, 'Tulcea'),
(38, 'Timiș'),
(39, 'Teleorman'),
(40, 'Vâlcea'),
(41, 'Vrancea'),
(42, 'Vaslui');

-- --------------------------------------------------------

--
-- Table structure for table `marimi`
--

DROP TABLE IF EXISTS `marimi`;
CREATE TABLE IF NOT EXISTS `marimi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `marimi`
--

INSERT INTO `marimi` (`ID`, `denumire`) VALUES
(2, 'S'),
(3, 'M'),
(4, 'L'),
(5, 'XS'),
(6, 'XL'),
(7, 'XXL'),
(8, '42'),
(9, '41'),
(10, '40'),
(11, '85');

-- --------------------------------------------------------

--
-- Table structure for table `produse`
--

DROP TABLE IF EXISTS `produse`;
CREATE TABLE IF NOT EXISTS `produse` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(64) NOT NULL,
  `cod_brand` int NOT NULL,
  `cod_categorie` int NOT NULL,
  `pret` float NOT NULL,
  `descriere` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `cod_subcategorie` int NOT NULL,
  `produs_imagine1` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `cod_brand` (`cod_brand`),
  KEY `cod_categorie` (`cod_categorie`),
  KEY `cod_subcategorie` (`cod_subcategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produse`
--

INSERT INTO `produse` (`ID`, `denumire`, `cod_brand`, `cod_categorie`, `pret`, `descriere`, `keywords`, `cod_subcategorie`, `produs_imagine1`) VALUES
(1, 'Shoes', 1, 1, 225, 'Nike running shoes.', 'shoes running red black 40 41 42 43 44 45 39 38 37 36 adidasi alergat rosu negru ', 4, 'png-clipart-nike-air-max-nike-free-air-force-shoe-nike-white-outdoor-shoe-thumbnail.png'),
(2, 'Blue Shoes', 1, 1, 100, '123', 'kkk', 4, 'png-clipart-sneakers-skate-shoe-nike-one-nike-shoe-purple-fashion-thumbnail.png'),
(3, 'Tshirt', 4, 3, 10, 'Tricou negru 100% bumbac', 'ana', 11, 'denim.png'),
(4, 'Curea Calvin Klein', 5, 4, 100, 'Material: 100% Piele', 'curea Calvin Klein negru ', 12, '315649957_ck-curea.png');

-- --------------------------------------------------------

--
-- Table structure for table `subcategorii`
--

DROP TABLE IF EXISTS `subcategorii`;
CREATE TABLE IF NOT EXISTS `subcategorii` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `denumire` varchar(32) NOT NULL,
  `cod_categorie` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `subcategorii_ibfk_1` (`cod_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subcategorii`
--

INSERT INTO `subcategorii` (`ID`, `denumire`, `cod_categorie`) VALUES
(1, 'Geci', 3),
(2, 'Pulovere', 3),
(3, 'Bocanci & cizme', 1),
(4, 'Sneaker', 1),
(6, 'Șlapi & sandale', 1),
(7, 'Pantofi', 1),
(8, 'Jeans', 3),
(9, 'Paltoane', 3),
(10, 'Pantaloni', 3),
(11, 'Tricouri', 3),
(12, 'Curele', 4);

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

DROP TABLE IF EXISTS `utilizatori`;
CREATE TABLE IF NOT EXISTS `utilizatori` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nume` varchar(32) NOT NULL,
  `prenume` varchar(32) NOT NULL,
  `telefon` varchar(10) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `cod_judet` int NOT NULL,
  `Oras` varchar(32) NOT NULL,
  `zipcode` varchar(16) NOT NULL,
  `tip` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'user(0),admin(1)',
  PRIMARY KEY (`ID`),
  KEY `cod_judet` (`cod_judet`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`ID`, `username`, `password`, `email`, `nume`, `prenume`, `telefon`, `adresa`, `cod_judet`, `Oras`, `zipcode`, `tip`) VALUES
(1, 'lexxerr', 'b5ed676d862caa35a4949a1e590d709c', 'alexeuno1@yahoo.com', 'Alex', 'Adi', '7286775474', 'Str sorilor,34', 10, 'Brasov', '272271', '1'),
(2, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'user1@gmail.com', 'Alex', 'boca', '7286775475', 'Str sorilor,34', 10, 'Brasov', '272272', '0'),
(3, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'user1@gmail.com', 'Alex', 'boca', '7286775475', 'Str sorilor,34', 10, 'Brasov', '272272', '0'),
(4, 'user2', '7e58d63b60197ceb55a1c487989a3720', 'user2@gmail.com', 'Alex', 'boca', '7286775475', 'Str sorilor,34', 10, 'Brasov', '272272', '0'),
(5, 'user3', '92877af70a45fd6a2ed7fe81e1236b78', 'user3@gmail.com', 'Alex', 'boca', '7286775475', 'Str sorilor,34', 10, 'Brasov', '272272', '0'),
(6, 'asdsa', '3f02ebe3d7929b091e3d8ccfde2f3bc6', 'user4@gmail.com', 'Alex', 'boca', '7286775475', 'Str sorilor,34', 10, 'Brasov', '272272', '0');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `atribute_produs`
--
ALTER TABLE `atribute_produs`
  ADD CONSTRAINT `atribute_produs_ibfk_1` FOREIGN KEY (`cod_culoare`) REFERENCES `culori` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `atribute_produs_ibfk_2` FOREIGN KEY (`cod_marime`) REFERENCES `marimi` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `atribute_produs_ibfk_3` FOREIGN KEY (`cod_produs`) REFERENCES `produse` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `clienti`
--
ALTER TABLE `clienti`
  ADD CONSTRAINT `clienti_ibfk_1` FOREIGN KEY (`cod_judet`) REFERENCES `judete` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clienti_ibfk_2` FOREIGN KEY (`cod_utilizator`) REFERENCES `utilizatori` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD CONSTRAINT `comenzi_ibfk_1` FOREIGN KEY (`cod_client`) REFERENCES `clienti` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comenzi_ibfk_2` FOREIGN KEY (`cod_status`) REFERENCES `comenzi_status` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comenzi_detalii`
--
ALTER TABLE `comenzi_detalii`
  ADD CONSTRAINT `comenzi_detalii_ibfk_1` FOREIGN KEY (`cod_atribut_produs`) REFERENCES `atribute_produs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comenzi_detalii_ibfk_2` FOREIGN KEY (`cod_comanda`) REFERENCES `comenzi` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cos`
--
ALTER TABLE `cos`
  ADD CONSTRAINT `cos_ibfk_1` FOREIGN KEY (`cod_atribut_produs`) REFERENCES `atribute_produs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cos_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `utilizatori` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `furnizori`
--
ALTER TABLE `furnizori`
  ADD CONSTRAINT `furnizori_ibfk_1` FOREIGN KEY (`cod_judet`) REFERENCES `judete` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `imagini`
--
ALTER TABLE `imagini`
  ADD CONSTRAINT `imagini_ibfk_1` FOREIGN KEY (`cod_produs`) REFERENCES `produse` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `intrarifacturi`
--
ALTER TABLE `intrarifacturi`
  ADD CONSTRAINT `intrarifacturi_ibfk_1` FOREIGN KEY (`cod_furnizor`) REFERENCES `furnizori` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `intrariproduse`
--
ALTER TABLE `intrariproduse`
  ADD CONSTRAINT `intrariproduse_ibfk_1` FOREIGN KEY (`cod_factura`) REFERENCES `intrarifacturi` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `intrariproduse_ibfk_2` FOREIGN KEY (`cod_marime`) REFERENCES `marimi` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `intrariproduse_ibfk_3` FOREIGN KEY (`cod_produs`) REFERENCES `produse` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `intrariproduse_ibfk_4` FOREIGN KEY (`cod_culoare`) REFERENCES `culori` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produse`
--
ALTER TABLE `produse`
  ADD CONSTRAINT `produse_ibfk_1` FOREIGN KEY (`cod_brand`) REFERENCES `branduri` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produse_ibfk_2` FOREIGN KEY (`cod_categorie`) REFERENCES `categorii` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produse_ibfk_3` FOREIGN KEY (`cod_subcategorie`) REFERENCES `subcategorii` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcategorii`
--
ALTER TABLE `subcategorii`
  ADD CONSTRAINT `subcategorii_ibfk_1` FOREIGN KEY (`cod_categorie`) REFERENCES `categorii` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD CONSTRAINT `utilizatori_ibfk_1` FOREIGN KEY (`cod_judet`) REFERENCES `judete` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
