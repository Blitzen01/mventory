-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mventory
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `allocation_log`
--

DROP TABLE IF EXISTS `allocation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allocation_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `previous_assignee` varchar(255) DEFAULT NULL,
  `new_assignee` varchar(255) DEFAULT NULL,
  `allocated_by` varchar(255) DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `allocation_log_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allocation_log`
--

LOCK TABLES `allocation_log` WRITE;
/*!40000 ALTER TABLE `allocation_log` DISABLE KEYS */;
INSERT INTO `allocation_log` VALUES (1,61,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 02:52:47'),(2,62,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 02:53:01'),(3,41,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 02:56:53'),(4,42,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 02:57:13'),(5,43,'EDD','5th Floor Online','deguzman','2026-01-24','2026-01-24 02:57:25'),(6,44,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 02:57:55'),(7,81,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:02:01'),(8,82,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:02:17'),(9,83,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:02:42'),(10,84,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:02:54'),(11,115,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:08:00'),(12,104,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:08:13'),(13,106,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:08:25'),(14,107,'EDD','5th Floor Online','deguzman','2026-01-23','2026-01-24 03:08:31'),(15,107,'5th Floor Online','EDD','deguzman','2026-01-24','2026-01-24 03:55:25'),(16,106,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:57:35'),(17,104,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:57:41'),(18,115,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:58:46'),(19,62,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:58:55'),(20,61,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:59:31'),(21,84,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 03:59:52'),(22,83,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 04:00:06'),(23,82,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 04:00:16'),(24,81,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 04:00:31'),(25,41,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 04:00:42'),(26,44,'5th Floor Online','5th Floor Online','deguzman','2026-01-24','2026-01-24 04:00:59'),(27,102,'EDD','Accounting | Maam Liezel','deguzman','2026-01-27','2026-01-27 00:41:41'),(28,85,'EDD','Accounting | Maam Liezel','deguzman','2026-01-27','2026-01-27 00:42:12'),(29,63,'EDD','Accounting | Maam Liezel','deguzman','2026-01-27','2026-01-27 00:42:57'),(30,45,'EDD','Accounting | Maam Liezel','deguzman','2026-01-27','2026-01-27 00:43:14'),(31,47,'EDD','2nd Floor | Maam Rhina','deguzman','2026-02-02','2026-02-02 01:16:19'),(32,113,'EDD','HR Dept. | Maam Connie','deguzman','2026-01-27','2026-02-02 03:47:56'),(33,73,'EDD','HR Dept. | Maam Connie','deguzman','2026-01-27','2026-02-02 03:49:06'),(34,53,'EDD','HR Dept. | Maam Connie','deguzman','2026-01-27','2026-02-02 03:49:43'),(35,107,'EDD','5th Floor Online','deguzman','2026-01-24','2026-02-02 06:30:56'),(36,48,'EDD','Accounting | Maam Jenielou','deguzman','2026-02-03','2026-02-04 01:00:36'),(37,49,'EDD','Accounting | Maam Allyza','deguzman','2026-02-03','2026-02-04 01:03:44'),(38,64,'EDD','Accounting | Maam Jenielou','deguzman','2026-02-03','2026-02-04 01:10:34'),(39,218,'EDD','5th Floor Online','deguzman','2026-02-04','2026-02-04 05:45:07'),(40,218,'5th Floor Online','EDD','deguzman','2026-02-04','2026-02-04 05:46:18'),(41,218,'EDD','Accounting | Maam Jenielou','deguzman','2026-02-04','2026-02-04 05:46:26'),(42,86,'EDD','EDD | Jas Luna','admin','2026-02-03','2026-02-05 00:53:11'),(43,93,'EDD','HR Dept. | Maam Connie','deguzman','2026-02-02','2026-02-05 01:30:24'),(44,218,'Accounting | Maam Jenielou','EDD','deguzman','2026-02-05','2026-02-05 08:51:06'),(45,218,'EDD','HR Dept. | Maam Connie','deguzman','2026-02-05','2026-02-05 08:52:13'),(46,218,'HR Dept. | Maam Connie','EDD','deguzman','2026-02-05','2026-02-05 08:52:21'),(47,218,'EDD','Accounting | Maam Liezel','deguzman','2026-02-05','2026-02-05 08:54:32'),(48,218,'Accounting | Maam Liezel','EDD','deguzman','2026-02-05','2026-02-05 09:00:44'),(49,105,'EDD','Sales Dept | Sir Marvin','deguzman','2026-02-06','2026-02-06 03:08:15'),(50,87,'EDD','Sales Dept. | Sir Marvin','deguzman','2026-02-06','2026-02-06 03:09:01');
/*!40000 ALTER TABLE `allocation_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_distribution`
--

DROP TABLE IF EXISTS `asset_distribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_distribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `previous_assignee` varchar(255) DEFAULT NULL,
  `new_assignee` varchar(255) DEFAULT NULL,
  `allocated_by` varchar(255) DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_distribution`
--

LOCK TABLES `asset_distribution` WRITE;
/*!40000 ALTER TABLE `asset_distribution` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_distribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `assigned_to` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'In Stock',
  `condition_status` varchar(50) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `warranty_cost` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_id` (`asset_id`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
INSERT INTO `assets` VALUES (41,'KYBRD-1',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 12:00 PM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 10:56 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:44:12'),(42,'KYBRD-2',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'5th Floor Online','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 10:57 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:44:12'),(43,'KYBRD-3',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'5th Floor Online','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 10:57 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:44:12'),(44,'KYBRD-4',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 12:00 PM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 10:57 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:44:12'),(45,'KYBRD-5',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'Accounting | Maam Liezel','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/27/2026 08:43 AM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Liezel\' by deguzman.\r\n','2026-01-23 00:44:12'),(46,'KYBRD-6',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(47,'KYBRD-7',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'2nd Floor | Maam Rhina','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[02/02/2026 09:16 AM] ALLOCATED: From \'EDD\' to \'2nd Floor | Maam Rhina\' by deguzman.\r\n','2026-01-23 00:44:12'),(48,'KYBRD-8',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(49,'KYBRD-9',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'Accounting | Maam Allyza','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[02/04/2026 09:03 AM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Allyza\' by deguzman.\r\n','2026-01-23 00:44:12'),(50,'KYBRD-10',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(51,'KYBRD-11',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(52,'KYBRD-12',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(53,'KYBRD-13',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'HR Dept. | Maam Connie','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[02/02/2026 11:49 AM] ALLOCATED: From \'EDD\' to \'HR Dept. | Maam Connie\' by deguzman.\r\n','2026-01-23 00:44:12'),(54,'KYBRD-14',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(55,'KYBRD-15',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(56,'KYBRD-16',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(57,'KYBRD-17',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(58,'KYBRD-18',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(59,'KYBRD-19',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(60,'KYBRD-20',13,'GX30 Keyboard','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:44:12'),(61,'MOUSE-1',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 11:59 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 10:52 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:46:16'),(62,'MOUSE-2',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/24/2026 11:58 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 10:53 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 00:46:16'),(63,'MOUSE-3',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'Accounting | Maam Liezel','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[01/27/2026 08:42 AM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Liezel\' by deguzman.\r\n','2026-01-23 00:46:16'),(64,'MOUSE-4',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(65,'MOUSE-5',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(66,'MOUSE-6',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(67,'MOUSE-7',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(68,'MOUSE-8',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(69,'MOUSE-9',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(70,'MOUSE-10',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(71,'MOUSE-11',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(72,'MOUSE-12',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(73,'MOUSE-13',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'HR Dept. | Maam Connie','Deployed','New','2026-01-22','2026-01-22',0.00,0.00,'[02/02/2026 11:49 AM] ALLOCATED: From \'EDD\' to \'HR Dept. | Maam Connie\' by deguzman.\r\n','2026-01-23 00:46:16'),(74,'MOUSE-14',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(75,'MOUSE-15',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(76,'MOUSE-16',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(77,'MOUSE-17',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(78,'MOUSE-18',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(79,'MOUSE-19',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(80,'MOUSE-20',13,'GX30 Mouse','X-LSWAB','GX30','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 00:46:16'),(81,'MNTR-1',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[01/24/2026 12:00 PM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:02 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(82,'MNTR-2',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[01/24/2026 12:00 PM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:02 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(83,'MNTR-3',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[01/24/2026 12:00 PM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:02 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(84,'MNTR-4',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'5th Floor Online','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[01/24/2026 11:59 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:02 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(85,'MNTR-5',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'Accounting | Maam Liezel','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[01/27/2026 08:42 AM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Liezel\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(86,'MNTR-6',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD | Jas Luna','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[02/05/2026 08:53 AM] ALLOCATED: From \'EDD\' to \'EDD | Jas Luna\' by admin.\r\n1 Year Warranty','2026-01-23 00:50:41'),(87,'MNTR-7',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'Sales Dept. | Sir Marvin','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[02/06/2026 11:09 AM] ALLOCATED: From \'EDD\' to \'Sales Dept. | Sir Marvin\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(88,'MNTR-8',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(89,'MNTR-9',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(90,'MNTR-10',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(91,'MNTR-11',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(92,'MNTR-12',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(93,'MNTR-13',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'HR Dept. | Maam Connie','Deployed','New','2026-01-22','2026-01-22',3850.00,0.00,'[02/05/2026 09:30 AM] ALLOCATED: From \'EDD\' to \'HR Dept. | Maam Connie\' by deguzman.\r\n1 Year Warranty','2026-01-23 00:50:41'),(94,'MNTR-14',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(95,'MNTR-15',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(96,'MNTR-16',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(97,'MNTR-17',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(98,'MNTR-18',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(99,'MNTR-19',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(100,'MNTR-20',13,'Nvision N2455-PRO 24\" 100hz IPS - Black','Nvision','N2455-PRO','NQ240BKCCEP2512301334-NQ240BKCCEP2512301565','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',3850.00,0.00,'1 Year Warranty','2026-01-23 00:50:41'),(101,'SYSTMNT-1',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(102,'SYSTMNT-2',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'Accounting | Maam Liezel','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[01/27/2026 08:41 AM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Liezel\' by deguzman.\r\n','2026-01-23 02:08:13'),(103,'SYSTMNT-3',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(104,'SYSTMNT-4',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'5th Floor Online','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[01/24/2026 11:57 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:08 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 02:08:13'),(105,'SYSTMNT-5',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'Sales Dept | Sir Marvin','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[02/06/2026 11:08 AM] ALLOCATED: From \'EDD\' to \'Sales Dept | Sir Marvin\' by deguzman.\r\n','2026-01-23 02:08:13'),(106,'SYSTMNT-6',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'5th Floor Online','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[01/24/2026 11:57 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:08 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 02:08:13'),(107,'SYSTMNT-7',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'5th Floor Online','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[02/02/2026 02:30 PM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:55 AM] ALLOCATED: From \'5th Floor Online\' to \'EDD\' by deguzman.\r\n[01/24/2026 11:08 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 02:08:13'),(108,'SYSTMNT-8',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(109,'SYSTMNT-9',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(110,'SYSTMNT-10',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(111,'SYSTMNT-11',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(112,'SYSTMNT-12',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(113,'SYSTMNT-13',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'HR Dept. | Maam Connie','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[02/02/2026 11:47 AM] ALLOCATED: From \'EDD\' to \'HR Dept. | Maam Connie\' by deguzman.\r\n','2026-01-23 02:08:13'),(114,'SYSTMNT-14',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'EDD','In Stock','New',NULL,'2026-01-23',24055.00,0.00,'','2026-01-23 02:08:13'),(115,'SYSTMNT-15',14,'AMD Ryzen 5 5500 (Tray)','AMD','Ryzen 5 5500 (Tray)','','CPU: Ryzen 5 5500 (Tray)\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: Gigabyte A520M-K V2\r\nSTORAGE: Billion Reservoir 512GB SSD\r\nGPU: Biostar GT730 2GB\r\nPSU: Acer AC-550 550W 80+ Bronze Tru Rated Fully-Modular\r\nOS: Windows 10',1,'5th Floor Online','Deployed','New',NULL,'2026-01-23',24055.00,0.00,'[01/24/2026 11:58 AM] ALLOCATED: From \'5th Floor Online\' to \'5th Floor Online\' by deguzman.\r\n[01/24/2026 11:08 AM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-01-23 02:08:13'),(116,'SYSTMNT-16',14,'Intel i7-13700','Intel','i7-13700','','CPU: i7-13700\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: MSI H610M-E PRO\r\nSTORAGE: HIKSEMI Wave Pro 1TB 2.5\" SSD\r\nGPU: Biostar RX550 4GB\r\nPSU: Acer AC-750 750W 80+ Bronze Tru Rated Fully Modular\r\nOS: Windows 10',1,'EDD','In Stock','New','2026-01-22','2026-01-22',49795.00,0.00,'','2026-01-23 02:13:25'),(117,'SYSTMNT-17',14,'Intel i7-13700','Intel','i7-13700','','CPU: i7-13700\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: MSI H610M-E PRO\r\nSTORAGE: HIKSEMI Wave Pro 1TB 2.5\" SSD\r\nGPU: Biostar RX550 4GB\r\nPSU: Acer AC-750 750W 80+ Bronze Tru Rated Fully Modular\r\nOS: Windows 10',1,'EDD','In Stock','New','2026-01-22','2026-01-22',49795.00,0.00,'','2026-01-23 02:13:25'),(118,'SYSTMNT-18',14,'Intel i7-13700','Intel','i7-13700','','CPU: i7-13700\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: MSI H610M-E PRO\r\nSTORAGE: HIKSEMI Wave Pro 1TB 2.5\" SSD\r\nGPU: Biostar RX550 4GB\r\nPSU: Acer AC-750 750W 80+ Bronze Tru Rated Fully Modular\r\nOS: Windows 10',1,'EDD','In Stock','New','2026-01-22','2026-01-22',49795.00,0.00,'','2026-01-23 02:13:25'),(119,'SYSTMNT-19',14,'Intel i7-13700','Intel','i7-13700','','CPU: i7-13700\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: MSI H610M-E PRO\r\nSTORAGE: HIKSEMI Wave Pro 1TB 2.5\" SSD\r\nGPU: Biostar RX550 4GB\r\nPSU: Acer AC-750 750W 80+ Bronze Tru Rated Fully Modular\r\nOS: Windows 10',1,'EDD','In Stock','New','2026-01-22','2026-01-22',49795.00,0.00,'','2026-01-23 02:13:25'),(120,'SYSTMNT-20',14,'Intel i7-13700','Intel','i7-13700','','CPU: i7-13700\r\nRAM: 8GB (8GB x 1) HIKSEMI Armor 3200mhz\r\nMOTHERBOARD: MSI H610M-E PRO\r\nSTORAGE: HIKSEMI Wave Pro 1TB 2.5\" SSD\r\nGPU: Biostar RX550 4GB\r\nPSU: Acer AC-750 750W 80+ Bronze Tru Rated Fully Modular\r\nOS: Windows 10',1,'EDD','In Stock','New','2026-01-22','2026-01-22',49795.00,0.00,'','2026-01-23 02:13:25'),(121,'HDMI-1',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(122,'HDMI-2',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(123,'HDMI-3',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(124,'HDMI-4',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(125,'HDMI-5',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(126,'HDMI-6',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(127,'HDMI-7',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(128,'HDMI-8',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(129,'HDMI-9',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(130,'HDMI-10',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(131,'HDMI-11',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(132,'HDMI-12',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(133,'HDMI-13',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(134,'HDMI-14',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(135,'HDMI-15',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(136,'HDMI-16',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(137,'HDMI-17',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(138,'HDMI-18',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(139,'HDMI-19',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(140,'HDMI-20',1,'1.5 Meters HDMI','N/A','N/A','','',1,'EDD','In Stock','New','2026-01-22','2026-01-22',0.00,0.00,'','2026-01-23 02:22:33'),(141,'CCTVCMR-1',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(142,'CCTVCMR-2',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(143,'CCTVCMR-3',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(144,'CCTVCMR-4',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(145,'CCTVCMR-5',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(146,'CCTVCMR-6',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(147,'CCTVCMR-7',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(148,'CCTVCMR-8',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(149,'CCTVCMR-9',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(150,'CCTVCMR-10',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(151,'CCTVCMR-11',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(152,'CCTVCMR-12',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(153,'CCTVCMR-13',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(154,'CCTVCMR-14',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(155,'CCTVCMR-15',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(156,'CCTVCMR-16',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(157,'CCTVCMR-17',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(158,'CCTVCMR-18',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(159,'CCTVCMR-19',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(160,'CCTVCMR-20',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(161,'CCTVCMR-21',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(162,'CCTVCMR-22',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(163,'CCTVCMR-23',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(164,'CCTVCMR-24',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(165,'CCTVCMR-25',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(166,'CCTVCMR-26',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(167,'CCTVCMR-27',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(168,'CCTVCMR-28',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(169,'CCTVCMR-29',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(170,'CCTVCMR-30',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(171,'CCTVCMR-31',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(172,'CCTVCMR-32',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(173,'CCTVCMR-33',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(174,'CCTVCMR-34',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(175,'CCTVCMR-35',15,'Dahua IR Eyeball Camera 2MP','Dahua','DH-HAC-T1A21N-U','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',616.00,0.00,'1 Year Warranty','2026-01-24 07:18:54'),(176,'DVR-1',15,'Dahual DVR 4 Channel Penta-bird 1080N/720p','Dahua','DH-XVR1B04-I (US)','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',1680.00,0.00,'1 Year Warranty','2026-01-24 07:21:40'),(177,'DVR-2',15,'Dahua DVR 8 Channel Penta-bird 1080/720p','Dahua','DH-XVR1B08-I','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',2576.00,0.00,'[01/24/2026 03:26 PM] EDITED: Changed Name (\'Dahua 8 Channel Penta-bird 1080/720p\' → \'Dahua DVR 8 Channel Penta-bird 1080/720p\'). Note by deguzman\r\n1 Year Warranty','2026-01-24 07:23:11'),(178,'DVR-3',15,'Dahua DVR 8 Channel Penta-bird 1080/720p','Dahua','DH-XVR1B08-I','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',2576.00,0.00,'[01/24/2026 03:26 PM] EDITED: Changed Name (\'Dahua 8 Channel Penta-bird 1080/720p\' → \'Dahua DVR 8 Channel Penta-bird 1080/720p\'). Note by deguzman\r\n1 Year Warranty','2026-01-24 07:23:11'),(179,'DVR-4',15,'Dahua DVR 8 Channel Penta-bird 1080/720p','Dahua','DH-XVR1B08-I','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',2576.00,0.00,'[01/24/2026 03:26 PM] EDITED: Changed Name (\'Dahua 8 Channel Penta-bird 1080/720p\' → \'Dahua DVR 8 Channel Penta-bird 1080/720p\'). Note by deguzman\r\n1 Year Warranty','2026-01-24 07:23:11'),(180,'DVR-5',15,'Duhua DVR 16 Channels Penta-bird 1080N/750P','Dahua','DH-XR1816-I(US)','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',4144.00,0.00,'[01/24/2026 03:26 PM] EDITED: Changed Name (\'Duhua 16 Channels Penta-bird 1080N/750P\' → \'Duhua DVR 16 Channels Penta-bird 1080N/750P\'). Note by deguzman\r\n1 Year Warranty','2026-01-24 07:24:34'),(181,'CXLCBL-1',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(182,'CXLCBL-2',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(183,'CXLCBL-3',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(184,'CXLCBL-4',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(185,'CXLCBL-5',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(186,'CXLCBL-6',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(187,'CXLCBL-7',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(188,'CXLCBL-8',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(189,'CXLCBL-9',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(190,'CXLCBL-10',8,'Poscom-20M Coaxial Cable','N/A','POSCOM-20M','','',1,'EDD','In Stock','New','2026-01-16','2026-01-21',201.60,0.00,'','2026-01-24 07:46:38'),(191,'CXLCBL-11',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(192,'CXLCBL-12',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(193,'CXLCBL-13',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(194,'CXLCBL-14',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(195,'CXLCBL-15',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(196,'CXLCBL-16',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(197,'CXLCBL-17',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(198,'CXLCBL-18',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(199,'CXLCBL-19',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(200,'CXLCBL-20',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(201,'CXLCBL-21',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(202,'CXLCBL-22',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(203,'CXLCBL-23',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(204,'CXLCBL-24',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(205,'CXLCBL-25',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(206,'CXLCBL-26',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(207,'CXLCBL-27',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(208,'CXLCBL-28',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(209,'CXLCBL-29',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(210,'CXLCBL-30',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(211,'CXLCBL-31',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(212,'CXLCBL-32',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(213,'CXLCBL-33',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(214,'CXLCBL-34',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(215,'CXLCBL-35',8,'Poscom-10M Coaxial Cable','N/A','POSCOM-10M','','',1,'EDD','In Stock','New','2016-01-16','2026-01-21',168.00,0.00,'','2026-01-24 07:47:55'),(216,'MNTR-21',13,'Nvision H24V8 24\" Monitor','Nvision','H24V8','','',1,'SM 2 Sales Dept. | Maam Aileen','Deployed','Used',NULL,'2026-02-04',0.00,0.00,'','2026-02-04 01:21:06'),(217,'MOUSE-21',13,'Mouse','N/A','N/A','','',1,'Accounting | Maam Jenielou','Deployed','Used',NULL,'2026-02-04',0.00,0.00,'','2026-02-04 01:47:09'),(218,'AAAA-1',12,'Test Assets','N/A','N/A','','',1,'EDD','In Stock','Repaired',NULL,'2026-02-04',0.00,0.00,'[02/06/2026 09:03 AM] EDITED: Changed Condition (\'Warranty\' → \'Repaired\'). Note by deguzman\r\n[02/06/2026 09:01 AM] DAMAGE: (COMPONENT/PART) wqe. Action: Warranty by deguzman\r\n[02/05/2026 05:02 PM] EDITED: Changed Condition (\'New\' → \'Used\'). Note by deguzman\r\n[02/05/2026 05:01 PM] EDITED: No data changes made by deguzman\r\n[02/05/2026 05:01 PM] EDITED: Changed Condition (\'\' → \'New\'). Note by deguzman\r\n[02/05/2026 05:01 PM] EDITED: No data changes made by deguzman\r\n[02/05/2026 05:00 PM] ALLOCATED: From \'Accounting | Maam Liezel\' to \'EDD\' by deguzman.\r\n[02/05/2026 05:00 PM] EDITED: Changed Category Name (\'(POS) Hardware\' → \'Audio/Visual Devices\'), Condition (\'GOOD\' → \'\'). Note by deguzman\r\n[02/05/2026 04:54 PM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Liezel\' by deguzman.\r\n[02/05/2026 04:52 PM] ALLOCATED: From \'HR Dept. | Maam Connie\' to \'EDD\' by deguzman.\r\n[02/05/2026 04:52 PM] ALLOCATED: From \'EDD\' to \'HR Dept. | Maam Connie\' by deguzman.\r\n[02/05/2026 04:51 PM] ALLOCATED: From \'Accounting | Maam Jenielou\' to \'EDD\' by deguzman.\r\n[02/04/2026 01:47 PM] RESOLVED: Status changed from \'Repairable\' to \'GOOD\' by deguzman.\r\n[02/04/2026 01:47 PM] DAMAGE: (COMPONENT/PART) qaw. Action: Repairable by deguzman\r\n[02/04/2026 01:46 PM] ALLOCATED: From \'EDD\' to \'Accounting | Maam Jenielou\' by deguzman.\r\n[02/04/2026 01:46 PM] ALLOCATED: From \'5th Floor Online\' to \'EDD\' by deguzman.\r\n[02/04/2026 01:45 PM] ALLOCATED: From \'EDD\' to \'5th Floor Online\' by deguzman.\r\n','2026-02-04 05:44:56'),(219,'PSHRDWR-1',6,'Handheld 2D Barcode Scanner','ROHS','T=1902L','','',1,'SM Lemery','Deployed','New',NULL,'2026-02-05',0.00,0.00,'Tran. Type: USB\r\nColor: Black','2026-02-05 07:44:50');
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Peripherals','External or internal auxiliary hardware devices that connect to a computer to expand its functions.','2025-12-05 10:55:12'),(6,'(POS) Hardware','Devices used specifically in retail or transactional environments.','2025-12-05 13:15:30'),(7,'Consumables','Items that are used up or replaced periodically.','2025-12-05 13:15:57'),(8,'Cables & Connectors','Wires and connectors that connect devices to computers, power, or networks.','2025-12-05 13:16:25'),(9,'Adapters & Converters','Devices that allow compatibility between different types of connections.','2025-12-05 13:16:51'),(10,'Storage Devices','Hardware used to store digital data, either temporarily or permanently.','2025-12-05 13:17:32'),(11,'Networking Devices','Equipment that enables communication and connectivity between computers, devices, and networks.','2025-12-05 13:17:47'),(12,'Audio/Visual Devices','Devices used to capture, output, or enhance sound and visual media.','2025-12-05 13:18:04'),(13,'Computer Parts','The basic parts of a desktop computer are the computer case, monitor, keyboard, mouse, and power cord.','2026-01-20 09:00:44'),(14,'Computer Sets','Computer and all its component software and hardware, peripherals, etc.','2026-01-20 09:00:44'),(15,'Security','They are classified as fixed assets for accounting purposes. Based on technology and design, they are categorized into types like IP/network or analog, and shapes like dome, bullet, or PTZ.','2026-01-24 05:04:19');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deleted_users`
--

DROP TABLE IF EXISTS `deleted_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deleted_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` varchar(15) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'deleted',
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(999) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deleted_users`
--

LOCK TABLES `deleted_users` WRITE;
/*!40000 ALTER TABLE `deleted_users` DISABLE KEYS */;
INSERT INTO `deleted_users` VALUES (3,16,'','qweew6','qweew','wqe','','Viewer','deleted','qwe@qw.com','$2y$10$HIx/iwjYqYMT9ZJtynh2k.eoUBWpQaT3hYNvl9C4H13fxEGb0GlPm','',0,NULL,'2026-01-27 02:28:20','2026-01-27 02:28:30'),(4,18,'NVN012026018','test1989','testing','test','','Inventory Manager','deleted','test@gmail.com','$2y$10$aUYWVJAYNdB7d5VbxHNDpeHhDhGwWFttiBoG//5f7xFsx6S8zdnpa','',0,NULL,'2026-01-27 01:59:29','2026-01-27 02:28:38');
/*!40000 ALTER TABLE `deleted_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edit_log`
--

DROP TABLE IF EXISTS `edit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `admin_username` varchar(255) DEFAULT NULL,
  `change_details` text DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_id` (`asset_id`),
  CONSTRAINT `edit_log_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_log`
--

LOCK TABLES `edit_log` WRITE;
/*!40000 ALTER TABLE `edit_log` DISABLE KEYS */;
INSERT INTO `edit_log` VALUES (1,218,'deguzman','Damage Reported [COMPONENT/PART]: qaw. Recommendation: Repairable','2026-02-04 13:47:05'),(2,218,'deguzman','Damage Reported [COMPONENT/PART]: wqe. Recommendation: Warranty','2026-02-06 09:01:59');
/*!40000 ALTER TABLE `edit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator','Full access to all settings and inventory data.'),(2,'Inventory Manager','Can add, edit, and remove stock and products.'),(3,'Stock Handler','Can only update stock levels (receive/ship).'),(4,'Viewer','Read-only access to inventory data.');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security_logs`
--

DROP TABLE IF EXISTS `security_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_login` tinyint(1) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `security_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security_logs`
--

LOCK TABLES `security_logs` WRITE;
/*!40000 ALTER TABLE `security_logs` DISABLE KEYS */;
INSERT INTO `security_logs` VALUES (1,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-01-23 08:36:02','Success',0,1),(2,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-01-24 10:45:37','Success',0,1),(3,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-01-26 08:33:17','Success',0,1),(4,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-01-27 16:47:47','Success',0,1),(5,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-02 14:28:44','Success',0,1),(6,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-04 08:59:15','Success',0,1),(7,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-04 13:44:24','Success',0,1),(8,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-04 17:20:51','Logout',0,0),(9,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-04 17:21:08','Success',0,1),(10,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 08:46:00','Success',0,1),(11,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 09:11:21','Logout',0,0),(12,4,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 09:11:27','Success',0,1),(13,4,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 09:12:09','Logout',0,0),(14,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 09:12:18','Success',0,1),(15,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 16:47:25','Success',0,1),(16,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 16:48:23','Logout',0,0),(17,7,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 16:48:29','Success',0,1),(18,7,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 16:48:33','Logout',0,0),(19,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-05 16:48:39','Success',0,1),(20,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-06 08:32:36','Success',0,1),(21,9,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','::1','2026-02-07 09:06:41','Success',0,1);
/*!40000 ALTER TABLE `security_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_audit_logs`
--

DROP TABLE IF EXISTS `system_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_audit_logs` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `record_id` varchar(50) NOT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE','RESTORED') NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `timestamp` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_audit_logs`
--

LOCK TABLES `system_audit_logs` WRITE;
/*!40000 ALTER TABLE `system_audit_logs` DISABLE KEYS */;
INSERT INTO `system_audit_logs` VALUES (1,'users','16','UPDATE','User: qweew6','Updated username/status/pass',9,'2026-01-27 09:01:17.011687',NULL),(2,'users','16','UPDATE','User: qweew6','Updated username/status/pass',9,'2026-01-27 09:01:38.032493',NULL),(3,'users','18','INSERT',NULL,'User Created: test1989 | Role: Inventory Manager | Name: testing test | SysID: NVN012026018',9,'2026-01-27 09:59:29.192011',NULL),(4,'users','16','DELETE','Archived User: qweew6','Moved to archive',9,'2026-01-27 10:28:12.106448',NULL),(5,'deleted_users','16','RESTORED','Restored User: qweew6','Moved to active',9,'2026-01-27 10:28:20.474973',NULL),(6,'users','16','DELETE','Archived User: qweew6','Moved to archive',9,'2026-01-27 10:28:30.010842',NULL),(7,'users','18','DELETE','Archived User: test1989','Moved to archive',9,'2026-01-27 10:28:38.609087',NULL),(8,'users','7','UPDATE','User: viewer','Updated username/status/pass',9,'2026-02-05 16:48:15.486757',NULL);
/*!40000 ALTER TABLE `system_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `setting_type` enum('string','number','boolean','select') NOT NULL,
  `category` enum('general','inventory','notifications','security') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES ('app_logo','varay_logo.png','string','general','The Logo image of the application'),('app_name','M Ventory','string','general','The display name of the application.'),('auto_assign_sku','0','boolean','inventory','Automatically generate SKU for new products (0=No, 1=Yes).'),('dark_mode_default','on','boolean','general','Enable dark mode by default (0=No, 1=Yes).'),('date_format','m/d/Y','select','general','Default date format for reports.'),('default_unit_of_measure','Pieces','select','inventory','Default unit for new products.'),('eol_duration_years','3','','general',NULL),('last_backup_datetime','2026-02-07 09:34:58','string','general','Stores the last Excel/SQL backup date and time'),('liquidation_percentage','60','','general',NULL),('log_all_actions','1','boolean','security','Enable detailed user audit logging.'),('low_stock_threshold_percent','10','number','inventory','Global percentage for low stock warning.'),('password_min_length','10','number','security','Minimum length for user passwords.'),('trigger_low_stock','0','boolean','notifications','Enable low stock notifications.'),('trigger_new_user','0','boolean','notifications','Notify admins on new user creation.');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(15) NOT NULL,
  `username` varchar(9999) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(999) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_login` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_activity` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4,'DMN122025002','admin','ADMIN','ACCOUNT','09123456789','','Administrator','active','admin@gmail.com','$2y$10$qKfllJ6.qy8RfwQYy8ZEOe0OmhmiaLMl05/zVzm4/rjrnQWVo3KHi','../src/image/profiles/user_4_1767929939.png',0,0,'2026-02-05 09:11:27','2025-12-04 23:55:37',NULL,'2026-01-19 10:25:54'),(5,'','inventory','Inventory','Manager','09123456789',NULL,'Inventory Manager','active','inventory.manager@gmail.com','$2y$10$Nxya3FcXRnLzAv200PjIJei9TCez54bMNdXJNLVMArALK.4ZHzB5u','',1,0,NULL,'2026-01-22 03:30:21',NULL,'2026-01-22 11:30:21'),(6,'','stock','Stock','Handler','09123456789',NULL,'Stock Handler','active','stock.handler@gmail.com','$2y$10$CIekO0PYbwhb7KhpT33zP.mp6COMnchYGEKmnP5nc/rRquxJQ3vvu','',1,0,NULL,'2026-01-22 03:30:18',NULL,'2026-01-22 11:30:18'),(7,'','viewer','Viewer','Account','09123456789',NULL,'Viewer','active','viewer@gmail.com','$2y$10$EaCmBIEAovcNwgQHQ27OkeYdbuKJnivmaMi4n7EMcPLoi45KcORnC','../src/image/profile_picture/MVentory_logo_1766024528.png',1,0,'2026-02-05 16:48:29','2026-01-22 00:54:58',NULL,'2026-01-22 08:54:58'),(8,'DMN122025008','abancia','Danilo','Abancia Jr.','','','Administrator','active','akosidhandhan07@gmail.com','$2y$10$X7.2G7BGacEnUELePJur8O1YjKFy9M2O7Z1.ibUSXrkdMElRLh3G.','',1,0,NULL,'2025-12-16 00:20:59',NULL,'2026-01-19 10:25:54'),(9,'DMN122025009','deguzman','Ron Justin','De Guzman','09091231234','Tazna, Cavite','Administrator','active','ronjustin22@gmail.com','$2y$10$40XUu4uDhR6sA.CntW1HJOHgOeZl9ZiziOL0NhL8n9BATTubqjePC','../src/image/profiles/user_9_1767928291.jpg',1,1,'2026-02-07 09:06:41','2025-12-16 00:21:59',NULL,'2026-01-19 10:25:54'),(10,'DMN122025010','trinidad','Herminio','Trinidad','','','Administrator','active','itdept@michaela.com.ph','$2y$10$HLgRGedcF/rHW46s7dW0t.MNH7K3aWO3jzDYNqMuFBkdt2Hv.Pt7W','',1,0,NULL,'2025-12-16 00:23:46',NULL,'2026-01-19 10:25:54'),(12,'DMN122025011','superadmin','Super Admin','Account','09090912345','','Super Administrator','active','super.admin@gmail.com','$2y$10$SjCJmJhVXaSnhBGsT9mvTO6YZrTLvzL6kKZqvaPVb8ARurpiML.Fe','',1,0,'2026-01-03 13:52:43','2025-12-26 00:01:02',NULL,'2026-01-19 10:25:54'),(17,'DMN012026013','justine317','Justine','Feranil','','','Administrator','active','justine@gmail.com','$2y$10$bJTgh4irigBiFe6JgWUsW.fmIp0o5eA6enNiu5UeFu0ZjDZVfWROW','',1,0,NULL,'2026-01-03 00:19:26',NULL,'2026-01-19 10:25:54');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-07  9:35:58
