CREATE DATABASE  IF NOT EXISTS `blacklist` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `blacklist`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: blacklist
-- ------------------------------------------------------
-- Server version	5.7.15-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blacklist_client_info`
--

DROP TABLE IF EXISTS `blacklist_client_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist_client_info` (
  `client_id` int(11) NOT NULL,
  `user_id` int(5) NOT NULL,
  `comment` text NOT NULL,
  `created` varchar(45) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `CLIENT_VID` (`client_id`,`vid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_client_info`
--

LOCK TABLES `blacklist_client_info` WRITE;
/*!40000 ALTER TABLE `blacklist_client_info` DISABLE KEYS */;
INSERT INTO `blacklist_client_info` VALUES (2,1,'не страховать по ДМС','CURRENT_TIMESTAMP',1,1),(2,1,'не страховать по ВЗР','CURRENT_TIMESTAMP',2,3),(3,1,'не страховать по всем видам страхования','CURRENT_TIMESTAMP',3,1),(3,1,'не страховать по всем видам страхования','CURRENT_TIMESTAMP',4,2),(3,1,'не страховать по всем видам страхования','CURRENT_TIMESTAMP',5,3),(4,1,'не страховать по ДМС','CURRENT_TIMESTAMP',6,1),(8,1,'комментарий','CURRENT_TIMESTAMP',7,3),(12,1,'','CURRENT_TIMESTAMP',8,1),(15,1,'ТестТестТестТест','CURRENT_TIMESTAMP',9,3),(17,1,'6567657вавркео','CURRENT_TIMESTAMP',10,1),(18,1,'6567657вавркео','CURRENT_TIMESTAMP',11,1),(19,1,'','CURRENT_TIMESTAMP',12,0),(20,1,'','CURRENT_TIMESTAMP',13,0),(21,1,'авапаыр фавпыварп фвапыварп','CURRENT_TIMESTAMP',14,0),(22,1,'еуые вфеф','CURRENT_TIMESTAMP',15,0),(23,1,'еуые вфеф','CURRENT_TIMESTAMP',16,0),(24,1,'еуые вфеф','CURRENT_TIMESTAMP',17,0),(25,1,'ыывафЧчяячвава','CURRENT_TIMESTAMP',18,3),(26,1,'ыывафЧчяячвава','CURRENT_TIMESTAMP',19,3),(27,1,'ыывафЧчяячвава','CURRENT_TIMESTAMP',20,3),(28,1,'ыывафЧчяячвава','CURRENT_TIMESTAMP',21,3),(29,1,'Цим не страховать','CURRENT_TIMESTAMP',22,1),(30,1,'Цим не страховать','CURRENT_TIMESTAMP',23,1),(31,1,'Цим не страховать','CURRENT_TIMESTAMP',24,1),(32,1,'ккееек','CURRENT_TIMESTAMP',25,3),(33,1,'ккееек','CURRENT_TIMESTAMP',26,3),(34,1,'ккееек','CURRENT_TIMESTAMP',27,3),(35,1,'ВЗР','CURRENT_TIMESTAMP',28,3),(36,1,'ВЗР','CURRENT_TIMESTAMP',29,3),(37,1,'ВЗР','CURRENT_TIMESTAMP',30,3),(38,1,'ВЗР','CURRENT_TIMESTAMP',31,3),(39,1,'ВЗР','CURRENT_TIMESTAMP',32,3),(40,1,'ВЗР','CURRENT_TIMESTAMP',33,3),(41,1,'ВЗР','CURRENT_TIMESTAMP',34,3),(42,1,'Цим не страховать','CURRENT_TIMESTAMP',35,1),(43,1,'Цим не страховать','CURRENT_TIMESTAMP',36,1),(44,1,'Цим не страховать','CURRENT_TIMESTAMP',37,1),(45,1,'Цим не страховать','CURRENT_TIMESTAMP',38,1),(46,1,'Цим не страховать','CURRENT_TIMESTAMP',39,1),(47,1,'Цим не страховать','CURRENT_TIMESTAMP',40,1),(48,1,'новый клиент дмс','CURRENT_TIMESTAMP',41,1);
/*!40000 ALTER TABLE `blacklist_client_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-16 18:38:36
