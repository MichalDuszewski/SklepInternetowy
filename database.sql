CREATE DATABASE IF NOT EXISTS `SklepInternetowyDB` DEFAULT CHARACTER SET utf8mb4;
USE `SklepInternetowyDB`;

CREATE TABLE `Produkt` (
  `Produkt_ID` int NOT NULL AUTO_INCREMENT,
  `Nazwa` varchar(255) NOT NULL,
  `Opis` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Data_utworzenia` datetime NOT NULL,
  PRIMARY KEY (`Produkt_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Zamowienie` (
  `Zamowienie_ID` int NOT NULL AUTO_INCREMENT,
  `Klient` varchar(255) NOT NULL,
  `Klient_adres` varchar(255) NOT NULL,
  `Ilosc` int NOT NULL,
  `Produkt_ID` int NOT NULL,
  PRIMARY KEY (`Zamowienie_ID`),
  KEY `Produkt_ID` (`Produkt_ID`),
  CONSTRAINT `Produkt_ID` FOREIGN KEY (`Produkt_ID`) REFERENCES `Produkt` (`Produkt_ID`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;