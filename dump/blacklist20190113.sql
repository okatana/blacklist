-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: blacklist
-- ------------------------------------------------------
-- Server version	5.7.24-0ubuntu0.18.04.1

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
-- Table structure for table `blacklist_client`
--

DROP TABLE IF EXISTS `blacklist_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist_client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `midname` varchar(64) DEFAULT NULL,
  `bithday` date NOT NULL,
  `comment` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_client`
--

LOCK TABLES `blacklist_client` WRITE;
/*!40000 ALTER TABLE `blacklist_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklist_client` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_client_info`
--

LOCK TABLES `blacklist_client_info` WRITE;
/*!40000 ALTER TABLE `blacklist_client_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklist_client_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklist_private`
--

DROP TABLE IF EXISTS `blacklist_private`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist_private` (
  `user_id` int(5) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_private`
--

LOCK TABLES `blacklist_private` WRITE;
/*!40000 ALTER TABLE `blacklist_private` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklist_private` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklist_user`
--

DROP TABLE IF EXISTS `blacklist_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist_user` (
  `user_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `active` int(1) unsigned DEFAULT '1',
  `allow_edit` int(1) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_user`
--

LOCK TABLES `blacklist_user` WRITE;
/*!40000 ALTER TABLE `blacklist_user` DISABLE KEYS */;
INSERT INTO `blacklist_user` VALUES (1,'admin','2875121737045d7a28613b5cfa2d25da618745c7','katanugina@guideh.com',1,1);
/*!40000 ALTER TABLE `blacklist_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklist_vid_insurance`
--

DROP TABLE IF EXISTS `blacklist_vid_insurance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist_vid_insurance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `message` text,
  `alias` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `alias_UNIQUE` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist_vid_insurance`
--

LOCK TABLES `blacklist_vid_insurance` WRITE;
/*!40000 ALTER TABLE `blacklist_vid_insurance` DISABLE KEYS */;
INSERT INTO `blacklist_vid_insurance` VALUES (1,'дмс','katanugin@guideh.com','dms-blacklist-client','dms'),(2,'КВАРТИРЫ','katanugin@guideh.com','app-blacklist-client','app'),(3,'ВЗР','katanugin@guideh.com','vzr-blacklist-client','vzr');
/*!40000 ALTER TABLE `blacklist_vid_insurance` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-13 22:49:41
