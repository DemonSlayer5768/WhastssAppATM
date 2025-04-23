-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	11.5.2-MariaDB-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema coparmex
--

CREATE DATABASE IF NOT EXISTS coparmex;
USE coparmex;

--
-- Definition of table `whats_cuentas`
--

DROP TABLE IF EXISTS `whats_cuentas`;
CREATE TABLE `whats_cuentas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `numero_telefono` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `whats_cuentas`
--

/*!40000 ALTER TABLE `whats_cuentas` DISABLE KEYS */;
INSERT INTO `whats_cuentas` (`id`,`nombre`,`numero_telefono`,`fecha_creacion`) VALUES 
 (1,'Juan','3325921540','2025-04-23 08:38:15'),
 (2,'PEMEX','33325921540','2025-04-23 09:03:48');
/*!40000 ALTER TABLE `whats_cuentas` ENABLE KEYS */;


--
-- Definition of table `whats_mensajes_enviados`
--

DROP TABLE IF EXISTS `whats_mensajes_enviados`;
CREATE TABLE `whats_mensajes_enviados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `num_destino` int(11) NOT NULL,
  `adjunto` longblob DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `id_pantilla` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_whats_mensajes_enviados_whats_plantillas_idx` (`id_pantilla`),
  CONSTRAINT `fk_whats_mensajes_enviados_whats_plantillas` FOREIGN KEY (`id_pantilla`) REFERENCES `whats_plantillas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `whats_mensajes_enviados`
--

/*!40000 ALTER TABLE `whats_mensajes_enviados` DISABLE KEYS */;
/*!40000 ALTER TABLE `whats_mensajes_enviados` ENABLE KEYS */;


--
-- Definition of table `whats_plantillas`
--

DROP TABLE IF EXISTS `whats_plantillas`;
CREATE TABLE `whats_plantillas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `cuerpo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `whats_plantillas`
--

/*!40000 ALTER TABLE `whats_plantillas` DISABLE KEYS */;
INSERT INTO `whats_plantillas` (`id`,`nombre`,`cuerpo`) VALUES 
 (1,'plantilla1','prueba de la plantilla 1'),
 (2,'Plantilla2','mensaje de prueba de plantilla 2'),
 (3,'PLANTILLA 3','USUARIOIS 23');
/*!40000 ALTER TABLE `whats_plantillas` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
