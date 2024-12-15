USE `e_ecommerce_app`;
-- MySQL dump 10.13  Distrib 8.0.40, for macos14 (x86_64)
--
-- Host: 127.0.0.1    Database: e_ecommerce_app_2
-- ------------------------------------------------------
-- Server version	9.0.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `customer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (61,12,1),(61,21,2),(64,19,1);
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Iphone',1,'2024-03-09 07:40:16','2024-03-09 07:40:16',NULL,NULL,NULL),(2,'Huawei 1',0,'2024-03-09 07:40:35','2024-03-09 07:40:35',NULL,1,18),(3,'Samsung 2',0,'2024-03-09 07:40:45','2024-03-09 07:40:45',NULL,3,18),(4,'Xiaomi',1,'2024-03-09 07:40:54','2024-03-09 07:40:54',NULL,NULL,NULL),(5,'Oppo',1,'2024-03-09 07:41:01','2024-03-09 07:41:01',NULL,NULL,NULL),(6,'Realmi',1,'2024-03-09 07:41:11','2024-03-09 07:41:11',NULL,NULL,NULL),(7,'Vivo',1,'2024-03-09 07:41:20','2024-03-09 07:41:20',NULL,NULL,NULL),(25,'dmeo',1,'2024-08-17 21:23:56','2024-08-17 21:23:56',NULL,NULL,NULL),(26,'demo',1,'2024-09-15 19:24:47','2024-09-15 19:24:47',NULL,NULL,NULL),(27,'abc',0,'2024-09-15 20:23:57','2024-09-15 20:23:57',NULL,NULL,NULL),(28,'Test created 3',1,'2024-09-15 21:22:49','2024-09-15 21:22:49',1,NULL,NULL),(29,'test 7',1,'2024-09-15 21:31:35','2024-09-15 21:31:35',1,1,NULL),(30,'demo 24 09',1,'2024-09-24 08:53:30','2024-09-24 08:53:30',3,NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Phạm Thị Thu Thảo','phamthithuthao@gmail.com','Cho hỏi điện thoại iphone 16',1,'2024-12-03 02:22:13','2024-12-03 02:22:13',NULL,NULL,NULL),(2,'Nguyễn Thái Tú','nguyenthaitu@gmail.com','Cho hỏi về điện thoại iphone 16 ',1,'2024-12-04 05:30:43','2024-12-04 05:30:43',NULL,NULL,NULL),(3,'Nguyễn Văn C','nguyenvanc@gmail.com','Cho tôi hỏi iphone 16 ? ',1,'2024-12-05 06:45:34','2024-12-05 06:45:34',NULL,NULL,NULL);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `conversation_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`conversation_id`),
  UNIQUE KEY `idx_customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customer_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `customer_points` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT '0',
  `last_activity` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (21,'Miss Thalia Prosacco','jbradtke@example.org','$2y$12$lQiLwamyZP0EbdBn3BtR0ub.INJoofeBkpFEqyX/J8wH2gzucFqB6','2003-12-27','488 Ernesto Forks Suite 942\nDixietown, FL 37568','229.914.1209',0,0,'2024-03-11 02:36:05','2024-03-11 02:36:05',NULL,NULL,NULL,0,NULL),(22,'Nathanial Bins','jack.lubowitz@example.org','$2y$12$YmsovUdrTEaw6ILEy5cd0utdWmwCZ5.5ldSgjFP1fTETei7MX.AI.','1974-08-20','517 Celestine Centers\nPort Salvatore, NJ 77211','+1-279-940-7519',0,0,'2024-03-11 02:36:05','2024-03-11 02:36:05',NULL,NULL,NULL,0,NULL),(23,'Prof. Keagan Jerde','estrella.hodkiewicz@example.org','$2y$12$xpnSOekiSRoiA4R/UFlsYec5oF/G.O5y4wxm8VZwVFTWILYYF5cu.','2006-12-10','7050 Yesenia Branch Suite 363\nPort Leo, DE 58605-5413','(917) 568-1354',0,0,'2024-03-11 02:36:06','2024-03-11 02:36:06',NULL,NULL,NULL,0,NULL),(24,'Ms. Katrina Roberts','rosemary59@example.org','$2y$12$boUfWz.bq0vsOc4El5KNB.T9YbtqBPVSIxGku/ikZQztxffspIFUm','1992-06-14','9319 Littel Station\nPort Percival, VT 49275-0332','740.576.5045',0,0,'2024-03-11 02:36:06','2024-03-11 02:36:06',NULL,NULL,NULL,0,NULL),(25,'Aurelia Berge','berge.virginia@example.com','$2y$12$t4ejxKCfvtoICxsZTAk4/eOXqROIYAbnsk6kNEjUsB.wKM.f3HlmG','2015-11-11','91164 Oberbrunner Parkways\nEast Abdullah, AL 98847-9991','818-381-1987',0,0,'2024-03-11 02:36:06','2024-03-11 02:36:06',NULL,NULL,NULL,0,NULL),(26,'Julie West','udamore@example.com','$2y$12$lQJ7g/TW5Cbp9vFYGaVjQurgHPQDWe8pfkrzDW0ve4JGxusk4175u','1998-01-03','58228 Jaiden Place Apt. 177\nPort Bertville, FL 76666-1428','+1 (870) 432-4190',0,1,'2024-03-11 02:36:07','2024-03-11 02:36:07',NULL,NULL,NULL,0,NULL),(27,'Dr. Bryana Medhurst','bulah47@example.org','$2y$12$p22Mh7Zsw300tWUNX7qmbOo7cTZYvY90AcFSQJr/06bs2l25fi0Rq','1984-03-05','483 Dare Port\nMedhurstburgh, IA 84099-1647','+1-979-452-7321',0,0,'2024-03-11 02:36:07','2024-03-11 02:36:07',NULL,NULL,18,0,NULL),(28,'Emmitt Lind','einar.emard@example.net','$2y$12$j9Uk5RWfTmE6OAWZmL4nJ.tNFznMF2k3erJncN./aDt3RpD.JipAu','2004-12-25','53797 Toy Lights Apt. 176\nAlejandrinside, VT 36633-6235','+12342434002',0,1,'2024-03-11 02:36:08','2024-03-11 02:36:08',NULL,NULL,NULL,0,NULL),(29,'Ricardo Luettgen','metz.johnson@example.org','$2y$12$/jrDtETCCXV78DKzqkT./uG/4Lle7FL/X6O/fz90wyjZtlHhe6Sva','1987-04-14','39683 Dagmar Stravenue\nNew Damionfort, OH 31316-3281','928.281.6641',0,1,'2024-03-11 02:36:08','2024-03-11 02:36:08',NULL,NULL,NULL,0,NULL),(30,'Melany Nolan MD','wolff.doyle@example.net','$2y$12$p2VJRdLkGczNfaWu90lGT.EQmkiHCtlhB6eoJ9/2V3hLWHadNBM6C','1987-05-28','185 Veronica Land\nEast Malcolmshire, UT 64096-2139','1-445-607-3874',0,0,'2024-03-11 02:36:09','2024-03-11 02:36:09',NULL,NULL,18,0,NULL),(56,'Nguyễn Văn An','annv@gmail.com','$2y$10$moyo4yzP3KtNilPaADV2FeM7EKe3n/O/wS7MQvPx92VQp04hhMWDW','2024-04-04','Disney, Australia','0739485930',0,1,'2024-04-22 01:03:22','2024-04-22 01:30:53',NULL,NULL,NULL,0,NULL),(57,'Nguyễn Văn Teò','teonv@gmail.com','$2y$10$fmsQyz5lxXLI1vzHBztfxeLd7PfXo9K8Af4BKkS4gTxC4kfEjOiSC',NULL,NULL,NULL,0,0,'2024-05-06 20:21:31','2024-05-06 20:21:31',NULL,NULL,18,0,NULL),(58,'Tuan pham ','tuan@gmail.com','$2y$10$MvytQ0LQcPSS95BHww54uOdiM8XxJz1xavHtrXLLe/hAV8akTtC6e',NULL,NULL,NULL,0,0,'2024-05-07 02:39:08','2024-05-07 02:39:08',NULL,NULL,18,0,NULL),(59,'Vũ Đức Tiến','vuductien@gmail.com','$2y$10$JfgnCNEh49tb8pR1XVcHu.fhDWXC2jOi5PYYXwfdTzQ1dukt1BwY.',NULL,NULL,NULL,100,1,'2024-08-28 20:02:49','2024-08-28 20:02:49',NULL,NULL,NULL,0,NULL),(60,'Vũ Đức Tiến','vuductien2908@gmail.com','$2y$10$lZ8riW4W7CmPCNmPZkKVHuTY2QkNwEiMhexvFk3L8Hwuhe540.WY2',NULL,NULL,NULL,400,0,'2024-10-12 09:56:10','2024-10-12 09:56:10',NULL,NULL,18,0,NULL),(61,'nguyễn văn a','nguyenvana@gmail.com','$2y$12$IkQRfxbugz0C7R9MDGzK7OITrQZoL4Md1u52xD5SacGHY0QNEQOiG','1994-09-29','62 Trương Phước Phan, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh','0934190085',0,1,'2024-12-02 10:37:10','2024-12-02 13:43:14',NULL,NULL,NULL,0,NULL),(62,'nguyễn văn a','nguyenvanb@gmail.com','$2y$12$MALNIcDLM77Dl/v0BaW5s.Pzn2TDLy7GKECQghNhuyik6HkCyadse',NULL,NULL,NULL,0,1,'2024-12-02 10:42:08','2024-12-02 10:42:08',NULL,NULL,NULL,0,NULL),(63,'duong thi b','duongthib@gmail.com','$2y$12$QGbINK9urlGP0UddM2tT0eszSnlopHAh./c/FPqFcD0WtFR6ev7OS',NULL,NULL,NULL,0,1,'2024-12-03 01:31:23','2024-12-03 01:31:23',NULL,NULL,NULL,0,NULL),(64,'Nguyễn Thị Ngọc Hạnh','nguyenthingochanh@gmail.com','$2y$12$unqE.xm8fhMfrRePo19dZuvwFUrR4JGyRCAhvPCoNMlVlOLDZ/MEa',NULL,NULL,NULL,0,1,'2024-12-03 01:54:56','2024-12-03 01:54:56',NULL,NULL,NULL,0,NULL),(65,'Nguyễn Bích Vân','nguyenbichvan@gmail.com','$2y$12$vL566.HdjWKv.ShHB6bVaueEvnrhh.CfXAp58kCbbKrstnqqpNJQK',NULL,NULL,NULL,270,0,'2024-12-03 10:03:07','2024-12-03 10:03:07',NULL,NULL,18,0,NULL),(66,'nguyenvanhai','nguyenvanhai@gmail.com','$2y$12$ABVWuPJXQtdKQ3hejf1N2uW02klaI3ba.BIoMcUDX7GI/qG9t2cYO','1994-09-28','62 Trương Phước Phan, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh','0934190084',0,0,'2024-12-03 15:42:20','2024-12-03 15:43:56',18,18,18,0,NULL),(67,'nguyenthixuyen','nguyenthixuyen@gmail.com','$2y$12$Ha9olDIgtWnTwr2O.tkmgeKqmTlsJqVnRgTv2XMffRJ49ux7XMlda','1998-01-01','Quận 11','0934198800',3110,1,'2024-12-03 15:48:02','2024-12-04 05:26:34',NULL,NULL,NULL,0,NULL),(68,'Nguyễn Văn C','18c12003@gmail.com','$2y$12$qwiikFUyLaGRvhD5S0sVduxfxTf33j5pDup/ZADcd0DRfTA1berim',NULL,NULL,NULL,1010,1,'2024-12-05 06:42:33','2024-12-05 06:42:33',NULL,NULL,NULL,0,NULL),(69,'Nguyễn Văn D','nguyenvand@gmail.com','$2y$12$g6WbLtrgPXDEjuQUBUd0WuDB.8pMegGvfTzvB46oC.TyRBNjIBAk2',NULL,NULL,NULL,0,1,'2024-12-06 13:28:56','2024-12-06 13:28:56',NULL,NULL,NULL,0,NULL),(70,'Nguyễn Văn E','nguyenvane@gmail.com','$2y$12$/wDBN8jK98cqHVAks3/4Y.OYPH2/opfZ3d.YJEzRPeen.9MnsVMu.',NULL,NULL,NULL,0,1,'2024-12-06 13:30:36','2024-12-06 13:30:36',NULL,NULL,NULL,0,NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `details_conversations`
--

DROP TABLE IF EXISTS `details_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `details_conversations` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `conversation_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `sender_type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `receiver_type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `sender_id` bigint unsigned DEFAULT NULL,
  `receiver_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `details_conversations_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`conversation_id`),
  CONSTRAINT `details_conversations_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `details_conversations`
--

LOCK TABLES `details_conversations` WRITE;
/*!40000 ALTER TABLE `details_conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `details_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`product_id`,`order_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (12,18,11,'2024-12-05 06:04:50','2024-12-05 06:04:50',NULL,NULL,NULL),(19,19,10,'2024-12-05 06:44:34','2024-12-05 06:44:34',NULL,NULL,NULL),(19,20,1,'2024-12-06 11:38:26','2024-12-06 11:38:26',NULL,NULL,NULL),(19,22,1,'2024-12-06 11:55:16','2024-12-06 11:55:16',NULL,NULL,NULL),(19,23,20,'2024-12-06 12:01:08','2024-12-06 12:01:08',NULL,NULL,NULL),(19,24,1,'2024-12-06 12:05:29','2024-12-06 12:05:29',NULL,NULL,NULL),(20,18,2,'2024-12-05 06:04:50','2024-12-05 06:04:50',NULL,NULL,NULL);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `promotion_id` bigint unsigned DEFAULT NULL,
  `name_receiver` varchar(255) NOT NULL,
  `phone_receiver` varchar(255) NOT NULL,
  `address_receiver` mediumtext NOT NULL,
  `notes` mediumtext,
  `total_price` double NOT NULL,
  `status` varchar(255) DEFAULT 'đang chờ',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `promotion_id` (`promotion_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (18,67,NULL,'Nguyễn Thị Xuyến','0934190085','Ba Đình, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội','',302400000,'đã giao','2024-12-05 06:04:50','2024-12-05 06:04:50',NULL,18,NULL),(19,68,NULL,'Nguyễn Văn C','0931490002','Ba Đình, Phường Quang Trung, Thành phố Hà Giang, Tỉnh Hà Giang','',34000000,'đã giao','2024-12-05 06:44:34','2024-12-05 06:44:34',NULL,18,NULL),(20,68,NULL,'Hien Pham','0934190085','a, Phường Phúc Tân, Quận Hoàn Kiếm, Thành phố Hà Nội','',3400000,'đang giao','2024-12-06 11:38:26','2024-12-06 11:38:26',NULL,18,NULL),(21,68,15,'Hien Pham','0934190085','a, Phường Phúc Tân, Quận Hoàn Kiếm, Thành phố Hà Nội','',3060000,'đang chờ','2024-12-06 11:53:19','2024-12-06 11:53:19',NULL,NULL,NULL),(22,68,15,'Hien Pham','0934190085','a, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội','',3060000,'đã giao','2024-12-06 11:55:16','2024-12-06 11:55:16',NULL,18,NULL),(23,68,15,'Hien Pham','0934190085','a, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội','',61200000,'đã giao','2024-12-06 12:01:08','2024-12-06 12:01:08',NULL,18,NULL),(24,68,17,'Hien Pham','0934190085','a, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội','',3060000,'đang chờ','2024-12-06 12:05:29','2024-12-06 12:05:29',NULL,NULL,NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `permission_code` varchar(250) NOT NULL,
  `permission_name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'statistic','Thống kê',1,'2024-09-30 23:16:15','2024-09-30 23:16:15',NULL,NULL,NULL),(2,'profile','Thông tin cá nhân',1,'2024-09-30 23:17:00','2024-09-30 23:17:00',NULL,NULL,NULL),(4,'manager_category','Quản lý danh mục',1,'2024-09-30 23:18:41','2024-09-30 23:18:41',NULL,NULL,NULL),(5,'create_category','Thêm danh mục',1,'2024-09-30 23:19:16','2024-09-30 23:19:16',NULL,NULL,NULL),(6,'edit_category','Sửa danh mục',1,'2024-09-30 23:19:16','2024-09-30 23:19:16',NULL,NULL,NULL),(7,'delete_category','Xóa danh mục',1,'2024-09-30 23:19:39','2024-09-30 23:19:39',NULL,NULL,NULL),(8,'manager_user','Quản lý nhân viên',1,'2024-09-30 23:21:20','2024-09-30 23:21:20',NULL,NULL,NULL),(9,'create_user','Thêm nhân viên',1,'2024-09-30 23:21:20','2024-09-30 23:21:20',NULL,NULL,NULL),(10,'edit_user','Sửa nhân viên',1,'2024-09-30 23:21:55','2024-09-30 23:21:55',NULL,NULL,NULL),(11,'delete_user','Xóa nhân viên',1,'2024-09-30 23:21:55','2024-09-30 23:21:55',NULL,NULL,NULL),(12,'manager_product','Quản lý sản phẩm',1,'2024-09-30 23:22:51','2024-09-30 23:22:51',NULL,NULL,NULL),(13,'create_product','Tạo sản phẩm',1,'2024-09-30 23:23:25','2024-09-30 23:23:25',NULL,NULL,NULL),(14,'edit_product','Cập nhật sản phẩm',1,'2024-09-30 23:23:25','2024-09-30 23:23:25',NULL,NULL,NULL),(15,'delete_product','Xóa sản phẩm',1,'2024-09-30 23:23:47','2024-09-30 23:23:47',NULL,NULL,NULL),(16,'manager_product_review','Quản lý đánh giá',1,'2024-09-30 23:25:06','2024-09-30 23:25:06',NULL,NULL,NULL),(17,'delete_product_review','Xóa đánh giá',1,'2024-09-30 23:25:06','2024-09-30 23:25:06',NULL,NULL,NULL),(18,'confirm_product_review','Xác nhận đánh giá',1,'2024-09-30 23:25:44','2024-09-30 23:25:44',NULL,NULL,NULL),(19,'manager_customer','Quản lý khách hàng',1,'2024-09-30 23:26:50','2024-09-30 23:26:50',NULL,NULL,NULL),(20,'create_customer','Thêm khách hàng',1,'2024-09-30 23:27:31','2024-09-30 23:27:31',NULL,NULL,NULL),(21,'edit_customer','Sửa khách hàng',1,'2024-09-30 23:27:31','2024-09-30 23:27:31',NULL,NULL,NULL),(22,'delete_customer','Xóa khách hàng',1,'2024-09-30 23:27:49','2024-09-30 23:27:49',NULL,NULL,NULL),(23,'manager_order','Quản lý đơn hàng',1,'2024-09-30 23:28:33','2024-09-30 23:28:33',NULL,NULL,NULL),(24,'confirm_order','Xác nhận đơn hàng',1,'2024-09-30 23:28:33','2024-09-30 23:28:33',NULL,NULL,NULL),(25,'show_order','Xem chi tiết đơn hàng',1,'2024-09-30 23:28:52','2024-09-30 23:28:52',NULL,NULL,NULL),(26,'delete_order','Xóa đơn hàng',1,'2024-09-30 23:29:23','2024-09-30 23:29:23',NULL,NULL,NULL),(27,'chat_manager','Quản lý hội thoại',1,'2024-09-30 23:30:13','2024-09-30 23:30:13',NULL,NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `customer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `content` text,
  `rate` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
INSERT INTO `product_reviews` VALUES (67,12,'Sản phẩm bình thường ',2,1,'2024-12-05 06:10:24','2024-12-05 06:10:24',NULL,NULL,NULL),(67,20,'Oppo reno 11 pro xam',1,0,'2024-12-05 06:09:40','2024-12-05 06:09:40',NULL,NULL,NULL),(68,19,'Chưa xài tốt lắm ',1,0,'2024-12-05 06:49:33','2024-12-05 06:49:33',NULL,NULL,NULL);
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `quantity` int NOT NULL,
  `description` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Iphone 12',12000000,1378,'Trong những tháng cuối năm 2020, Apple đã chính thức giới thiệu đến người dùng cũng như iFan thế hệ iPhone 12 series mới với hàng loạt tính năng bứt phá, thiết kế được lột xác hoàn toàn, hiệu năng đầy mạnh mẽ và một trong số đó chính là iPhone 12 64GB.\r\nHiệu năng vượt xa mọi giới hạn\r\nApple đã trang bị con chip mới nhất của hãng (tính đến 11/2020) cho iPhone 12 đó là A14 Bionic, được sản xuất trên tiến trình 5 nm với hiệu suất ổn định hơn so với chip A13 được trang bị trên phiên bản tiền nhiệm iPhone 11.Với CPU Apple A14 Bionic, bạn có thể dễ dàng trải nghiệm mọi tựa game với những pha chuyển cảnh mượt mà hay hàng loạt hiệu ứng đồ họa tuyệt đẹp ở mức đồ họa cao mà không lo tình trạng giật lag.Chưa hết, Apple còn gây bất ngờ đến người dùng với hệ thống 5G lần đầu tiên được trang bị trên những chiếc iPhone, cho tốc độ truyền tải dữ liệu nhanh hơn, ổn định hơn.iPhone 12 sẽ chạy trên hệ điều hành iOS 15 (12/2021) với nhiều tính năng hấp dẫn như hỗ trợ Widget cũng như những nâng cấp tối ưu phần mềm đáng kể mang lại những trải nghiệm thú vị mới lạ đến người dùng.Cụm camera không ngừng cải tiến\r\niPhone 12 được trang bị hệ thống camera kép bao gồm camera góc rộng và camera siêu rộng có cùng độ phân giải là 12 MP, chế độ ban đêm (Night Mode) trên bộ đôi camera này cũng đã được nâng cấp về phần cứng lẫn thuật toán xử lý, khi chụp những bức ảnh thiếu sáng bạn sẽ nhận được kết quả ấn tượng với màu sắc, độ chi tiết rõ nét đáng kinh ngạc.','product_images/ZKLtSNvkCzGAl64K9Jt3Ew1y2gR429bDyEAFM3wJ.jpg',1,'2024-03-09 07:43:29','2024-03-09 07:43:29',NULL,NULL,NULL,4),(2,1,'Iphone 13',15000000,1235,'Trong khi sức hút đến từ bộ 4 phiên bản iPhone 12 vẫn chưa nguội đi, thì hãng điện thoại Apple đã mang đến cho người dùng một siêu phẩm mới iPhone 13 với nhiều cải tiến thú vị sẽ mang lại những trải nghiệm hấp dẫn nhất cho người dùng.\r\nHiệu năng vượt trội nhờ chip Apple A15 Bionic\r\nCon chip Apple A15 Bionic siêu mạnh được sản xuất trên quy trình 5 nm giúp iPhone 13 đạt hiệu năng ấn tượng, với CPU nhanh hơn 50%, GPU nhanh hơn 30% so với các đối thủ trong cùng phân khúc.Nhờ hiệu năng được cải tiến, người dùng có được những trải nghiệm tốt hơn trên điện thoại khi dùng các ứng dụng chỉnh sửa ảnh hay chiến các tựa game đồ họa cao mượt mà.iPhone 13 trang bị bộ nhớ trong 128 GB dung lượng lý tưởng cho phép bạn thỏa thích lưu trữ mọi nội dung theo ý muốn mà không lo nhanh đầy bộ nhớ.Tốc độ 5G tốt hơn \r\nMạng 5G được cải thiện chất lượng với nhiều băng tần hơn, với 5G giúp điện thoại xem trực tuyến hay tải xuống các ứng dụng và tài liệu đều đạt tốc độ nhanh chóng. Không chỉ vậy, siêu phẩm mới này còn có chế độ dữ liệu thông minh, tự động phát hiện và giảm tải tốc độ mạng để tiết kiệm năng lượng khi không cần dùng tốc độ cao.','product_images/aMsPz4XdS3vpotucgNrCN2mMUNb4n0X6awzLQB15.jpg',1,'2024-03-09 07:44:38','2024-03-09 07:44:38',NULL,NULL,NULL,3),(3,1,'Iphone 14 Promax 128GB',27300000,123,'Thông tin sản phẩm\r\niPhone 14 Pro Max một siêu phẩm trong giới smartphone được nhà Táo tung ra thị trường vào tháng 09/2022. Máy trang bị con chip Apple A16 Bionic vô cùng mạnh mẽ, đi kèm theo đó là thiết kế màn hình mới, hứa hẹn mang lại những trải nghiệm đầy mới mẻ cho người dùng iPhone.\r\nThiết kế cao cấp bền bỉ\r\niPhone năm nay sẽ được thừa hưởng nét đặc trưng từ người anh iPhone 13 Pro Max, vẫn sẽ là khung thép không gỉ và mặt lưng kính cường lực kết hợp với tạo hình vuông vức hiện đại thông qua cách tạo hình phẳng ở các cạnh và phần mặt lưng.Nổi bật với thiết kế màn hình mới\r\nĐiểm ấn tượng nhất trên điện thoại iPhone năm nay nằm ở thiết kế màn hình, có thể dễ dàng nhận thấy được là hãng cũng đã loại bỏ cụm tai thỏ truyền thống qua bao đời iPhone bằng một hình dáng mới vô cùng lạ mắt.So với cụm tai thỏ hình notch năm nay đã có phần tiết kiệm diện tích tương đối tốt, nhưng khi so với các kiểu màn hình dạng “nốt ruồi” thì đây vẫn chưa thực sự là một điều quá tối ưu cho phần màn hình. Thế nhưng Apple lại rất biết cách tận dụng những nhược điểm để biến chúng trở thành ưu điểm một cách ngoạn mục bằng cách phát minh nhiều hiệu ứng thú vị.Để làm cho chúng trở nên bắt mắt hơn Apple cũng đã giới thiệu nhiều hiệu ứng chuyển động nhằm làm tăng sự thích thú cho người dùng, điều này được kích hoạt trong lúc mình ấn giữ phần hình notch khi đang dùng các phần mềm hỗ trợ như: Nghe nhạc, đồng hồ hẹn giờ, ghi âm,...','product_images/QNDgIHUWLR7RgvLmxk7KFyGnfjpooo0HSwXvZEh5.jpg',1,'2024-03-09 07:45:55','2024-03-09 07:45:55',NULL,NULL,NULL,2),(4,1,'iPhone 15 Pro Max 1TB',44000000,49,'Trong thế giới công nghệ ngày càng phát triển, iPhone 15 Pro Max 1 TB nổi bật như một điện thoại thông minh hoàn hảo, kết hợp sự mạnh mẽ của công nghệ và sự sáng tạo không giới hạn. Chiếc điện thoại này không chỉ đem lại hiệu năng vượt trội mà còn mang đến khả năng chụp ảnh xuất sắc, tạo nên một trải nghiệm hoàn hảo cho người dùng.\r\nDiện mạo sang trọng, cứng cáp\r\niPhone 15 Pro Max 1 TB vẫn duy trì thiết kế vuông vắn và đẳng cấp đã làm nên tên tuổi của dòng sản phẩm này. Việc giữ nguyên dáng vẻ truyền thống không chỉ thể hiện sự sang trọng, thanh lịch mà còn giúp người dùng nhận ra ngay lập tức rằng đây là một chiếc iPhone.\r\n \r\nLà một sự thay đổi quan trọng, iPhone 15 Pro Max 1 TB đã từ bỏ chất liệu khung thép không gỉ quen thuộc để chuyển sang sử dụng khung Titanium. Điều này không chỉ làm cho chiếc điện thoại trở nên cứng cáp hơn mà còn giúp giảm khối lượng tổng thể, mang lại sự thoải mái hơn khi sử dụng trong thời gian dài.Mặt lưng của iPhone 15 Pro Max 1 TB được làm từ kính cường lực cao cấp và chế tạo theo kiểu nhám, tạo nên một vẻ đẹp độc đáo và tạo điểm nhấn cho thiết kế tổng thể. Đồng thời, vật liệu này cũng làm cho chiếc điện thoại trở nên kháng trầy xước và hạn chế bám vân tay tốt hơn.\r\n\r\nDynamic Island là một tính năng độc đáo trên iPhone 15 Pro Max 1 TB. Đây là tính năng hoạt động trên phần hình notch dạng viên thuốc của màn hình, cho phép người dùng truy cập nhanh các ứng dụng và chức năng thông qua các biểu tượng động. Điều này giúp tối ưu hóa sự tiện lợi và tăng hiệu suất của người dùng.','product_images/HJeLayOJAEwUOhTMpdMUx5ByJxrKA5nqVTSiiPVi.jpg',1,'2024-03-09 07:46:59','2024-03-09 07:46:59',NULL,NULL,NULL,4),(5,1,'iPhone 13 Pro Max 128GB',17999000,100,'Thông tin sản phẩm\r\nTrong khi sức hút đến từ bộ 4 phiên bản iPhone 12 vẫn chưa nguội đi, thì hãng điện thoại Apple đã mang đến cho người dùng một siêu phẩm mới iPhone 13 với nhiều cải tiến thú vị sẽ mang lại những trải nghiệm hấp dẫn nhất cho người dùng.\r\nHiệu năng vượt trội nhờ chip Apple A15 Bionic\r\nCon chip Apple A15 Bionic siêu mạnh được sản xuất trên quy trình 5 nm giúp iPhone 13 đạt hiệu năng ấn tượng, với CPU nhanh hơn 50%, GPU nhanh hơn 30% so với các đối thủ trong cùng phân khúc.Tốc độ 5G tốt hơn \r\nMạng 5G được cải thiện chất lượng với nhiều băng tần hơn, với 5G giúp điện thoại xem trực tuyến hay tải xuống các ứng dụng và tài liệu đều đạt tốc độ nhanh chóng. Không chỉ vậy, siêu phẩm mới này còn có chế độ dữ liệu thông minh, tự động phát hiện và giảm tải tốc độ mạng để tiết kiệm năng lượng khi không cần dùng tốc độ cao.\r\nMàn hình Super Retina XDR độ sáng cao, tiết kiệm pin\r\niPhone 13 sử dụng tấm nền OLED với kích thước màn hình 6.1 inch cho chất lượng màu sắc và chi tiết hình ảnh sắc nét, sống động, độ phân giải đạt 1170 x 2532 Pixels.','product_images/UEB31MP5b7Fu1O923rCWYM0GuCMNvzYrplIrDBlX.jpg',0,'2024-03-09 07:48:27','2024-03-09 07:48:27',NULL,NULL,18,4),(6,2,'Huawei P30 blue',18000000,0,'Huawei P30 là chiếc smartphone cao cấp vừa được Huawei giới thiệu với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và thiết lập camera ấn tượng.\r\nCamera siêu ấn tượng\r\nĐiện thoại dòng P của Huawei luôn cho ra mắt những công nghệ camera mới và P30 cũng không ngoại lệ.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.SuperSpectrum là tên của Huawei cho bộ lọc màu RYYB, loại bỏ các yếu tố màu xanh lá cây khỏi bộ lọc RGGB và thay thế chúng bằng màu vàng.\r\nHuawei nói rằng việc chuyển từ màu xanh lục sang màu vàng sẽ giúp cảm biến hấp thụ nhiều ánh sáng hơn.Màu vàng cũng hấp thụ thêm ánh sáng xanh lá và đỏ, giúp cải thiện chất lượng hình ảnh trong bất cứ điều kiện nào, đặc biệt là trong điều kiện ánh sáng yếu.\r\nNgoài ra còn có một tính năng AI mới gọi là AI HDR, giúp điều chỉnh ánh sáng trên tấm ảnh dựa trên nguồn sáng từ đó đem lại cho người dùng những bức ảnh chất lượng hơn.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.','product_images/zXxZj7CYYIYMBiKUTtVxtlfNX8Ue5IIxdHfNmBIU.jpg',0,'2024-03-09 07:51:14','2024-03-09 07:51:14',NULL,NULL,18,5),(8,2,'Huawei Nova 5T blue',10000000,403,'Huawei P30 là chiếc smartphone cao cấp vừa được Huawei giới thiệu với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và thiết lập camera ấn tượng.\r\nCamera siêu ấn tượng\r\nĐiện thoại dòng P của Huawei luôn cho ra mắt những công nghệ camera mới và P30 cũng không ngoại lệ.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.SuperSpectrum là tên của Huawei cho bộ lọc màu RYYB, loại bỏ các yếu tố màu xanh lá cây khỏi bộ lọc RGGB và thay thế chúng bằng màu vàng.\r\nHuawei nói rằng việc chuyển từ màu xanh lục sang màu vàng sẽ giúp cảm biến hấp thụ nhiều ánh sáng hơn.Màu vàng cũng hấp thụ thêm ánh sáng xanh lá và đỏ, giúp cải thiện chất lượng hình ảnh trong bất cứ điều kiện nào, đặc biệt là trong điều kiện ánh sáng yếu.\r\nNgoài ra còn có một tính năng AI mới gọi là AI HDR, giúp điều chỉnh ánh sáng trên tấm ảnh dựa trên nguồn sáng từ đó đem lại cho người dùng những bức ảnh chất lượng hơn.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.','product_images/966EClTWziiiiOvoT74jbAoQ1jIzi7aKObDrjWBl.jpg',1,'2024-03-09 07:51:51','2024-03-09 07:51:51',NULL,NULL,NULL,3),(9,2,'Huawei Mate 30',25000000,100,'Huawei P30 là chiếc smartphone cao cấp vừa được Huawei giới thiệu với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và thiết lập camera ấn tượng.\r\nCamera siêu ấn tượng\r\nĐiện thoại dòng P của Huawei luôn cho ra mắt những công nghệ camera mới và P30 cũng không ngoại lệ.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.SuperSpectrum là tên của Huawei cho bộ lọc màu RYYB, loại bỏ các yếu tố màu xanh lá cây khỏi bộ lọc RGGB và thay thế chúng bằng màu vàng.\r\nHuawei nói rằng việc chuyển từ màu xanh lục sang màu vàng sẽ giúp cảm biến hấp thụ nhiều ánh sáng hơn.Màu vàng cũng hấp thụ thêm ánh sáng xanh lá và đỏ, giúp cải thiện chất lượng hình ảnh trong bất cứ điều kiện nào, đặc biệt là trong điều kiện ánh sáng yếu.\r\nNgoài ra còn có một tính năng AI mới gọi là AI HDR, giúp điều chỉnh ánh sáng trên tấm ảnh dựa trên nguồn sáng từ đó đem lại cho người dùng những bức ảnh chất lượng hơn.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.','product_images/LK8BM73YA2yxt680u8KtniclzvFPRFZgtI5rw9a9.jpg',1,'2024-03-09 07:52:19','2024-03-09 07:52:19',NULL,NULL,18,4),(10,2,'Huawei Nova 7I',7500000,93,'Huawei P30 là chiếc smartphone cao cấp vừa được Huawei giới thiệu với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và thiết lập camera ấn tượng.\r\nCamera siêu ấn tượng\r\nĐiện thoại dòng P của Huawei luôn cho ra mắt những công nghệ camera mới và P30 cũng không ngoại lệ.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.SuperSpectrum là tên của Huawei cho bộ lọc màu RYYB, loại bỏ các yếu tố màu xanh lá cây khỏi bộ lọc RGGB và thay thế chúng bằng màu vàng.\r\nHuawei nói rằng việc chuyển từ màu xanh lục sang màu vàng sẽ giúp cảm biến hấp thụ nhiều ánh sáng hơn.Màu vàng cũng hấp thụ thêm ánh sáng xanh lá và đỏ, giúp cải thiện chất lượng hình ảnh trong bất cứ điều kiện nào, đặc biệt là trong điều kiện ánh sáng yếu.\r\nNgoài ra còn có một tính năng AI mới gọi là AI HDR, giúp điều chỉnh ánh sáng trên tấm ảnh dựa trên nguồn sáng từ đó đem lại cho người dùng những bức ảnh chất lượng hơn.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.','product_images/RJI0XpqRzpXf6sBwL4A3dYpOGDXzY0vlzugAIooI.jpg',1,'2024-03-09 07:53:13','2024-03-09 07:53:13',NULL,NULL,NULL,3),(11,2,'Huawei Nova 8I',9200000,97,'Huawei P30 là chiếc smartphone cao cấp vừa được Huawei giới thiệu với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và thiết lập camera ấn tượng.\r\nCamera siêu ấn tượng\r\nĐiện thoại dòng P của Huawei luôn cho ra mắt những công nghệ camera mới và P30 cũng không ngoại lệ.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.SuperSpectrum là tên của Huawei cho bộ lọc màu RYYB, loại bỏ các yếu tố màu xanh lá cây khỏi bộ lọc RGGB và thay thế chúng bằng màu vàng.\r\nHuawei nói rằng việc chuyển từ màu xanh lục sang màu vàng sẽ giúp cảm biến hấp thụ nhiều ánh sáng hơn.Màu vàng cũng hấp thụ thêm ánh sáng xanh lá và đỏ, giúp cải thiện chất lượng hình ảnh trong bất cứ điều kiện nào, đặc biệt là trong điều kiện ánh sáng yếu.\r\nNgoài ra còn có một tính năng AI mới gọi là AI HDR, giúp điều chỉnh ánh sáng trên tấm ảnh dựa trên nguồn sáng từ đó đem lại cho người dùng những bức ảnh chất lượng hơn.Huawei P30 có 3 camera trên mặt lưng bao gồm ống kính chính SuperSpectrum 40 MP, khẩu độ f / 1.8 + ống kính góc cực rộng 16 MP + ống kính tele 8 MP với khả năng zoom quang 3X và khẩu độ f / 2.4.','product_images/fmMbn2tOaxytB7q9dkhuHfzMya6pd3L7183RdBGz.jpg',1,'2024-03-09 07:53:44','2024-03-09 07:53:44',NULL,NULL,NULL,1),(12,3,'Samsung galaxy s24 grey',25000000,76,'Thông tin sản phẩm\r\nTrong sự kiện Unpacked 2024 diễn ra vào ngày 18/01, Samsung đã chính thức ra mắt chiếc điện thoại Samsung Galaxy S24. Sản phẩm này mang đến nhiều cải tiến độc đáo, bao gồm việc sử dụng chip mới của Samsung, tích hợp nhiều tính năng thông minh sử dụng trí tuệ nhân tạo và cải thiện đáng kể hiệu suất chụp ảnh từ hệ thống camera.\r\nThiết kế vuông hơn, thời thượng hơn\r\nVề phần thiết kế, Samsung vẫn tiếp tục sử dụng kiểu dáng vuông vức và cách bố trí cụm camera quen thuộc so với Samsung Galaxy S23. Đáng khen là hãng có ghi nhận những góp ý từ đời trước nên cũng đã tối ưu nhẹ ở một vài điểm như: Các góc làm vuông hơn, viền màn hình mỏng hơn và cuối cùng là dải loa được làm theo dạng rảnh.Galaxy S24 có phần khung được làm từ chất liệu nhôm kết hợp cùng mặt lưng kính cường lực. Mình cảm giác máy cực kỳ chắc chắn, cảm giác cầm máy cũng khá là chặt tay. Bởi năm nay cả mặt lưng và khung viền đều được làm nhám, khác với Galaxy S23 có khung viền làm theo kiểu bóng.Cụm camera ở mặt sau vẫn giữ nguyên với cấu trúc 3 camera xếp dọc, không có sự thay đổi đáng kể về bố trí. Có lẽ Samsung vẫn đang cố gắng duy trì sự tối giản về thiết kế trong những sản phẩm của mình, điều mà hãng hướng tới khi người dùng đang có xu hướng yêu thích những thứ không có quá nhiều hoạ tiết, sự đơn giản.','product_images/ZllM8Hb9CKaBZifcbHPLpdnlINPFwwnAEfZdFkLR.jpg',1,'2024-03-09 07:55:05','2024-03-09 07:55:05',NULL,NULL,NULL,5),(13,3,'Samsung galaxy s24 Plus violet',28000000,90,'Thông tin sản phẩm\r\nTrong sự kiện Unpacked 2024 diễn ra vào ngày 18/01, Samsung đã chính thức ra mắt chiếc điện thoại Samsung Galaxy S24. Sản phẩm này mang đến nhiều cải tiến độc đáo, bao gồm việc sử dụng chip mới của Samsung, tích hợp nhiều tính năng thông minh sử dụng trí tuệ nhân tạo và cải thiện đáng kể hiệu suất chụp ảnh từ hệ thống camera.\r\nThiết kế vuông hơn, thời thượng hơn\r\nVề phần thiết kế, Samsung vẫn tiếp tục sử dụng kiểu dáng vuông vức và cách bố trí cụm camera quen thuộc so với Samsung Galaxy S23. Đáng khen là hãng có ghi nhận những góp ý từ đời trước nên cũng đã tối ưu nhẹ ở một vài điểm như: Các góc làm vuông hơn, viền màn hình mỏng hơn và cuối cùng là dải loa được làm theo dạng rảnh.Galaxy S24 có phần khung được làm từ chất liệu nhôm kết hợp cùng mặt lưng kính cường lực. Mình cảm giác máy cực kỳ chắc chắn, cảm giác cầm máy cũng khá là chặt tay. Bởi năm nay cả mặt lưng và khung viền đều được làm nhám, khác với Galaxy S23 có khung viền làm theo kiểu bóng.Cụm camera ở mặt sau vẫn giữ nguyên với cấu trúc 3 camera xếp dọc, không có sự thay đổi đáng kể về bố trí. Có lẽ Samsung vẫn đang cố gắng duy trì sự tối giản về thiết kế trong những sản phẩm của mình, điều mà hãng hướng tới khi người dùng đang có xu hướng yêu thích những thứ không có quá nhiều hoạ tiết, sự đơn giản.','product_images/AkzXaD4vbktjuSk7jGXioL0Juw2Di3L67wFi1R2Z.jpg',0,'2024-03-09 07:55:32','2024-03-09 07:55:32',NULL,NULL,18,5),(14,3,'Samsung galaxy s24 Ultra grey',32000000,265,'Thông tin sản phẩm\r\nTrong sự kiện Unpacked 2024 diễn ra vào ngày 18/01, Samsung đã chính thức ra mắt chiếc điện thoại Samsung Galaxy S24. Sản phẩm này mang đến nhiều cải tiến độc đáo, bao gồm việc sử dụng chip mới của Samsung, tích hợp nhiều tính năng thông minh sử dụng trí tuệ nhân tạo và cải thiện đáng kể hiệu suất chụp ảnh từ hệ thống camera.\r\nThiết kế vuông hơn, thời thượng hơn\r\nVề phần thiết kế, Samsung vẫn tiếp tục sử dụng kiểu dáng vuông vức và cách bố trí cụm camera quen thuộc so với Samsung Galaxy S23. Đáng khen là hãng có ghi nhận những góp ý từ đời trước nên cũng đã tối ưu nhẹ ở một vài điểm như: Các góc làm vuông hơn, viền màn hình mỏng hơn và cuối cùng là dải loa được làm theo dạng rảnh.Galaxy S24 có phần khung được làm từ chất liệu nhôm kết hợp cùng mặt lưng kính cường lực. Mình cảm giác máy cực kỳ chắc chắn, cảm giác cầm máy cũng khá là chặt tay. Bởi năm nay cả mặt lưng và khung viền đều được làm nhám, khác với Galaxy S23 có khung viền làm theo kiểu bóng.Cụm camera ở mặt sau vẫn giữ nguyên với cấu trúc 3 camera xếp dọc, không có sự thay đổi đáng kể về bố trí. Có lẽ Samsung vẫn đang cố gắng duy trì sự tối giản về thiết kế trong những sản phẩm của mình, điều mà hãng hướng tới khi người dùng đang có xu hướng yêu thích những thứ không có quá nhiều hoạ tiết, sự đơn giản.','product_images/W4gfJr20aICYBEfyqZV0iQQ0B1YokodHOQdnGkZe.jpg',1,'2024-03-09 07:56:01','2024-03-09 07:56:01',NULL,NULL,NULL,4),(15,3,'Samsung galaxy z fold 5 kem',40260000,20,'Thông tin sản phẩm\r\nTrong sự kiện Unpacked 2024 diễn ra vào ngày 18/01, Samsung đã chính thức ra mắt chiếc điện thoại Samsung Galaxy S24. Sản phẩm này mang đến nhiều cải tiến độc đáo, bao gồm việc sử dụng chip mới của Samsung, tích hợp nhiều tính năng thông minh sử dụng trí tuệ nhân tạo và cải thiện đáng kể hiệu suất chụp ảnh từ hệ thống camera.\r\nThiết kế vuông hơn, thời thượng hơn\r\nVề phần thiết kế, Samsung vẫn tiếp tục sử dụng kiểu dáng vuông vức và cách bố trí cụm camera quen thuộc so với Samsung Galaxy S23. Đáng khen là hãng có ghi nhận những góp ý từ đời trước nên cũng đã tối ưu nhẹ ở một vài điểm như: Các góc làm vuông hơn, viền màn hình mỏng hơn và cuối cùng là dải loa được làm theo dạng rảnh.Galaxy S24 có phần khung được làm từ chất liệu nhôm kết hợp cùng mặt lưng kính cường lực. Mình cảm giác máy cực kỳ chắc chắn, cảm giác cầm máy cũng khá là chặt tay. Bởi năm nay cả mặt lưng và khung viền đều được làm nhám, khác với Galaxy S23 có khung viền làm theo kiểu bóng.Cụm camera ở mặt sau vẫn giữ nguyên với cấu trúc 3 camera xếp dọc, không có sự thay đổi đáng kể về bố trí. Có lẽ Samsung vẫn đang cố gắng duy trì sự tối giản về thiết kế trong những sản phẩm của mình, điều mà hãng hướng tới khi người dùng đang có xu hướng yêu thích những thứ không có quá nhiều hoạ tiết, sự đơn giản.','product_images/pmtBWpCNxsdpT1Wjh9exUGQM3OLvvYNzgRXS3aWM.jpg',0,'2024-03-09 07:56:35','2024-03-09 07:56:35',NULL,NULL,NULL,4),(16,4,'Xiaomi redmi note 12 pro 4g den',14250000,254,'Sự bùng nổ của công nghệ di động trong những năm gần đây đã mang đến cho người dùng vô số lựa chọn smartphone đa dạng. Trong phân khúc tầm trung, Xiaomi Redmi Note 13 Pro 128GB nổi lên như một ứng cử viên sáng giá với những ưu điểm vượt trội về thiết kế, hiệu năng nhờ chip Helio G99-Ultra, camera 200 MP và kết hợp sạc nhanh 67 W.\r\nThiết kế đẹp mắt và hỗ trợ chuẩn IP54\r\nĐiện thoại có vẻ ngoài hiện đại và sang trọng, với khung viền vuông vức giúp tạo điểm nhấn cho thiết kế khi mang lại cảm giác mạnh mẽ, nam tính lúc cầm nắm. Đi cùng với đó là mặt lưng và khung nhựa nhẹ được làm bóng, Redmi Note 13 Pro mang đến vẻ đẹp sang trọng, bóng bẩy, thu hút mọi ánh nhìn.Ở vị trí giao nhau giữa khung viền vuông và hai mặt trước sau, máy được làm cong nhẹ để tạo ra một cảm giác cầm nắm thoải mái và tự nhiên. Điều này giúp người dùng dễ dàng sử dụng thiết bị trong thời gian dài mà không gặp phải cảm giác mệt mỏi hay không thoải mái.Xiaomi Redmi Note 13 Pro không chỉ nổi bật với thiết kế, mà còn có sự đa dạng trong màu sắc, phù hợp với sở thích cá nhân của mỗi người dùng. Tùy chọn màu sắc gồm có xanh lá, đen và tím, giúp người dùng có thêm lựa chọn để thể hiện phong cách riêng của mình.\r\n\r\nVới viền dưới mỏng chỉ với 2.25 mm, Redmi Note 13 Pro mang lại trải nghiệm sử dụng mượt mà và còn tạo điểm nhấn cho thiết kế tổng thể của sản phẩm. Đặc biệt, việc đạt được tiêu chuẩn kháng nước và bụi IP54 cũng là điểm nhấn giúp bảo vệ thiết bị khỏi những tác động từ môi trường bên ngoài, gia tăng tuổi thọ và độ bền cho sản phẩm.Xiaomi Redmi Note 13 Pro thu hút sự chú ý như là một trung tâm giải trí di động với tích hợp loa kép đi kèm công nghệ âm thanh Dolby Atmos, từ đó mang lại trải nghiệm âm thanh sống động, chi tiết và mạnh mẽ, làm cho việc xem phim, nghe nhạc trở nên thú vị hơn bao giờ hết.','product_images/w8kWrLMf5L8sFQ1vjpCflkKJOHdKvW5fPbLz3xae.jpg',1,'2024-03-09 07:58:02','2024-03-09 07:58:02',NULL,NULL,NULL,5),(17,4,'Xiaomi 14 white',14250000,258,'Sự bùng nổ của công nghệ di động trong những năm gần đây đã mang đến cho người dùng vô số lựa chọn smartphone đa dạng. Trong phân khúc tầm trung, Xiaomi Redmi Note 13 Pro 128GB nổi lên như một ứng cử viên sáng giá với những ưu điểm vượt trội về thiết kế, hiệu năng nhờ chip Helio G99-Ultra, camera 200 MP và kết hợp sạc nhanh 67 W.\r\nThiết kế đẹp mắt và hỗ trợ chuẩn IP54\r\nĐiện thoại có vẻ ngoài hiện đại và sang trọng, với khung viền vuông vức giúp tạo điểm nhấn cho thiết kế khi mang lại cảm giác mạnh mẽ, nam tính lúc cầm nắm. Đi cùng với đó là mặt lưng và khung nhựa nhẹ được làm bóng, Redmi Note 13 Pro mang đến vẻ đẹp sang trọng, bóng bẩy, thu hút mọi ánh nhìn.Ở vị trí giao nhau giữa khung viền vuông và hai mặt trước sau, máy được làm cong nhẹ để tạo ra một cảm giác cầm nắm thoải mái và tự nhiên. Điều này giúp người dùng dễ dàng sử dụng thiết bị trong thời gian dài mà không gặp phải cảm giác mệt mỏi hay không thoải mái.Xiaomi Redmi Note 13 Pro không chỉ nổi bật với thiết kế, mà còn có sự đa dạng trong màu sắc, phù hợp với sở thích cá nhân của mỗi người dùng. Tùy chọn màu sắc gồm có xanh lá, đen và tím, giúp người dùng có thêm lựa chọn để thể hiện phong cách riêng của mình.\r\n\r\nVới viền dưới mỏng chỉ với 2.25 mm, Redmi Note 13 Pro mang lại trải nghiệm sử dụng mượt mà và còn tạo điểm nhấn cho thiết kế tổng thể của sản phẩm. Đặc biệt, việc đạt được tiêu chuẩn kháng nước và bụi IP54 cũng là điểm nhấn giúp bảo vệ thiết bị khỏi những tác động từ môi trường bên ngoài, gia tăng tuổi thọ và độ bền cho sản phẩm.Xiaomi Redmi Note 13 Pro thu hút sự chú ý như là một trung tâm giải trí di động với tích hợp loa kép đi kèm công nghệ âm thanh Dolby Atmos, từ đó mang lại trải nghiệm âm thanh sống động, chi tiết và mạnh mẽ, làm cho việc xem phim, nghe nhạc trở nên thú vị hơn bao giờ hết.','product_images/3SUzBRm5yuoUjQxCgbPlZz2cmMQPogO0jyyOfVYT.jpg',1,'2024-03-09 07:58:25','2024-03-09 07:58:25',NULL,NULL,NULL,5),(18,6,'Readlmi C35 white',5500000,366,'realme Note 50 64GB tiếp tục thu hút sự chú ý nhờ vào mức giá nổi bật và hấp dẫn của mình. Mặc dù nằm trong phân khúc giá thấp, sản phẩm này vẫn mang đến nhiều công nghệ ấn tượng, tạo nên sự đáng chú ý khi trang bị màn hình lớn 6.74 inch, pin 5000 mAh và đạt chuẩn IP54.\r\nThiết kế vuông vức hiện đại\r\nVới hình dạng vuông vức, realme Note 50 trở nên nổi bật giữa các sản phẩm giá rẻ khác nhờ vẻ hiện đại. Mặt lưng và khung viền được làm từ chất liệu nhựa, không chỉ giúp giảm giá thành mà còn tối ưu hóa khối lượng, tạo ra trải nghiệm cầm nắm nhẹ nhàng.\r\n\r\nVới độ mỏng chỉ 7.99 mm, realme Note 50 thực sự tạo ấn tượng bởi đây được xem là một chiếc máy giá rẻ trang bị pin 5000 mAh nhưng được. tối ưu độ mỏng tốt như vậy. Với độ dày này, người dùng có cơ hội trải nghiệm cảm giác sử dụng mỏng nhẹ cũng như sở hữu được một chiếc máy đẹp có tạo hình đẳng cấp và phong cách hiện đại.\r\n\r\n\r\n\r\nrealme Note 50 không chỉ nổi bật với thiết kế đẹp mắt mà còn chinh phục người dùng bằng tính linh hoạt với cổng sạc Type-C. Công nghệ này giúp tăng cường hiệu suất khi cung cấp tốc độ truyền dữ liệu, đồng thời tạo ra sự thuận lợi với khả năng kết nối hai chiều.\r\n\r\nNgoài ra, với chuẩn kháng nước và bụi IP54, chiếc điện thoại realme này tự tin đối mặt với mọi thách thức môi trường. Bạn không cần phải lo lắng khi sử dụng điện thoại dưới mưa nhỏ hoặc trong môi trường bụi bặm. realme Note 50 giúp bảo vệ thiết bị khỏi những yếu tố gây hại, mang lại sự an tâm và thoải mái khi sử dụng hằng ngày.\r\n\r\nTích hợp vân tay cạnh viền, realme Note 50 giúp trải nghiệm mở khóa trở nên tối ưu hơn. Không chỉ nhanh chóng và hiệu quả, mà còn tăng cường đáng kể về mức độ bảo mật. Việc đặt cảm biến vân tay ở cạnh viền, một vị trí thuận tiện giúp người dùng truy cập thiết bị một cách tự nhiên và nhanh chóng.\r\n\r\n\r\n\r\nMàn hình lớn sử dụng tấm nền IPS LCD\r\nrealme Note 50 được trang bị công nghệ màn hình IPS LCD, một chuẩn khá phổ biến trên các dòng điện thoại giá rẻ - tầm trung. Tấm nền này ngoài ưu điểm về giá còn giúp mang lại chất lượng hình ảnh tốt, độ sáng cao, góc nhìn rộng và màu sắc trung thực.\r\n\r\nVới độ phân giải HD+ (720 x 1600 Pixels), màn hình điện thoại vẫn đảm bảo hình ảnh rõ ràng và chi tiết, làm cho mọi trải nghiệm giải trí trở nên trung thực và sống động. Với kích thước màn hình lên đến 6.74 inch, realme Note 50 đưa người dùng vào một thế giới mở rộng, nơi mọi chi tiết trên màn hình được hiển thị một cách to rõ và dễ nhìn.\r\n\r\n\r\n\r\nMàn hình có độ sáng 560 nits, một mức khá ổn trên một chiếc điện thoại giá rẻ, đủ để người dùng xem được các nội dung cơ bản như tin nhắn, bản đồ hay xem trước ảnh chụp mà ít khi gặp khó khăn. Tuy nhiên, để phục vụ nhu cầu giải trí như xem phim hay chơi game được tốt khi ở môi trường có độ sáng cao, có lẽ bạn nên quan tâm đến các sản phẩm thuộc phân khúc cao hơn.\r\n\r\nNgoài ra, điện thoại còn được trang bị kính cường lực Panda ở phần mặt trước, công nghệ này giúp bảo vệ màn hình tốt hơn trước những vật dụng gây dễ xước, tình trạng hư hại nặng khi va đập cũng được giảm thiểu phần nào nhờ lớp kính cường lực này.\r\n\r\n\r\n\r\nCamera đủ để đáp ứng tốt các nhu cầu quay chụp cơ bản\r\nrealme Note 50 được trang bị camera chính 13 MP, đảm bảo việc bắt gọn mọi khoảnh khắc của cuộc sống. Từ cảnh đẹp tự nhiên đến chụp ảnh gia đình, chiếc điện thoại này là bạn đồng hành đáng tin cậy, cung cấp hình ảnh sắc nét và chi tiết tốt trong tầm giá.\r\n\r\nVới camera phụ 0.08 MP tích hợp, realme Note 50 mang đến khả năng chụp ảnh với hiệu ứng xóa phông tinh tế, từ đó giúp tạo ra những bức ảnh nghệ thuật, nổi bật với sự tập trung vào đối tượng chính, trong khi phông nền được làm mịn màng và ấn tượng.\r\n\r\n\r\n\r\nỞ mặt trước, realme Note 50 sở hữu camera selfie 5 MP, với khả năng xóa phông và nhiều tính năng làm đẹp. Bạn sẽ luôn tự tin khi chụp ảnh tự sướng hay thậm chí là khi tham gia các cuộc họp trực tuyến, với chất lượng ảnh rõ nét và hiệu ứng làm đẹp tự nhiên.\r\n\r\nHiệu năng ổn và hỗ trợ mở rộng bộ nhớ lên đến 2 TB\r\nrealme Note 50 sử dụng chip Unisoc Tiger T612, một sự kết hợp tinh tế giữa hiệu năng và chi phí, chip này đảm bảo rằng chiếc điện thoại có thể chạy mượt mà, từ các ứng dụng hằng ngày đến những trải nghiệm giải trí đa phương tiện cơ bản.\r\n\r\nVì đây là mẫu điện thoại RAM 4 GB, vậy nên những tác vụ đa nhiệm nhiều ứng dụng cùng lúc vẫn sẽ gây đôi chút khó khăn dành cho máy, các hiện tượng như khựng hay đơ máy tạm thời có thể sẽ xảy ra. Tuy nhiên, để khắc phục điều này người dùng nên hạn chế mở nhiều ứng dụng không cần thiết để tối ưu RAM có trên máy.\r\n\r\n\r\n\r\nVới bộ nhớ trong 64 GB có sẵn, realme Note 50 cho phép bạn lưu trữ nhiều dữ liệu, ảnh, video và ứng dụng mà không lo lắng về không gian. Để đảm bảo không gian lưu trữ không bao giờ là vấn đề, điện thoại hỗ trợ mở rộng dung lượng qua thẻ MicroSD lên đến 2 TB, giúp cung cấp cho bạn sự linh hoạt theo nhu cầu của mình.\r\n\r\nPin 5000 mAh đảm bảo trải nghiệm dài lâu mà không cần sạc\r\nVới pin lớn 5000 mAh, realme Note 50 là chiếc điện thoại đồng hành lý tưởng cho những người dùng yêu cầu cao về thời lượng pin. Không còn lo lắng về việc sạc điện thoại liên tục, bạn có thể thoải mái sử dụng nhiều tính năng và trải nghiệm giải trí suốt cả ngày mà không cần phải nghĩ đến việc sạc pin nhiều lần trong ngày.\r\n\r\nMặc dù không phải là sạc nhanh, nhưng sạc 10 W của realme Note 50 lại giúp điện thoại nạp năng lượng một cách từ tốn và an toàn. Với tốc độ này, việc sạc điện thoại vào lúc đi ngủ là một lựa chọn thông minh, bởi cách này giúp tránh tình trạng phải chờ đợi khi bạn thức dậy.\r\n\r\n\r\n\r\nTổng kết, realme Note 50 là một sự chọn lựa hoàn hảo cho những người đang tìm kiếm một chiếc điện thoại nổi bật với mức giá phải chăng. Không đặt quá nhiều trọng tâm vào hiệu năng và chất lượng camera, chiếc điện thoại này đáp ứng nhu cầu sử dụng hằng ngày chủ yếu như lướt web, xem phim và thực hiện cuộc gọi hay nhắn tin.realme Note 50 không chỉ nổi bật với thiết kế đẹp mắt mà còn chinh phục người dùng bằng tính linh hoạt với cổng sạc Type-C. Công nghệ này giúp tăng cường hiệu suất khi cung cấp tốc độ truyền dữ liệu, đồng thời tạo ra sự thuận lợi với khả năng kết nối hai chiều.\r\n\r\nNgoài ra, với chuẩn kháng nước và bụi IP54, chiếc điện thoại realme này tự tin đối mặt với mọi thách thức môi trường. Bạn không cần phải lo lắng khi sử dụng điện thoại dưới mưa nhỏ hoặc trong môi trường bụi bặm. realme Note 50 giúp bảo vệ thiết bị khỏi những yếu tố gây hại, mang lại sự an tâm và thoải mái khi sử dụng hằng ngày.\r\n\r\nTích hợp vân tay cạnh viền, realme Note 50 giúp trải nghiệm mở khóa trở nên tối ưu hơn. Không chỉ nhanh chóng và hiệu quả, mà còn tăng cường đáng kể về mức độ bảo mật. Việc đặt cảm biến vân tay ở cạnh viền, một vị trí thuận tiện giúp người dùng truy cập thiết bị một cách tự nhiên và nhanh chóng.Màn hình lớn sử dụng tấm nền IPS LCD\r\nrealme Note 50 được trang bị công nghệ màn hình IPS LCD, một chuẩn khá phổ biến trên các dòng điện thoại giá rẻ - tầm trung. Tấm nền này ngoài ưu điểm về giá còn giúp mang lại chất lượng hình ảnh tốt, độ sáng cao, góc nhìn rộng và màu sắc trung thực.\r\n\r\nVới độ phân giải HD+ (720 x 1600 Pixels), màn hình điện thoại vẫn đảm bảo hình ảnh rõ ràng và chi tiết, làm cho mọi trải nghiệm giải trí trở nên trung thực và sống động. Với kích thước màn hình lên đến 6.74 inch, realme Note 50 đưa người dùng vào một thế giới mở rộng, nơi mọi chi tiết trên màn hình được hiển thị một cách to rõ và dễ nhìn.','product_images/5QjPxIMLzF0DiuPzsFz2BdctMQVxf2v5DpNjZb8N.jpg',1,'2024-03-09 07:59:49','2024-03-09 07:59:49',NULL,NULL,NULL,5),(19,6,'Readlmi C67 Xanh',3400000,343,'realme Note 50 64GB tiếp tục thu hút sự chú ý nhờ vào mức giá nổi bật và hấp dẫn của mình. Mặc dù nằm trong phân khúc giá thấp, sản phẩm này vẫn mang đến nhiều công nghệ ấn tượng, tạo nên sự đáng chú ý khi trang bị màn hình lớn 6.74 inch, pin 5000 mAh và đạt chuẩn IP54.\r\nThiết kế vuông vức hiện đại\r\nVới hình dạng vuông vức, realme Note 50 trở nên nổi bật giữa các sản phẩm giá rẻ khác nhờ vẻ hiện đại. Mặt lưng và khung viền được làm từ chất liệu nhựa, không chỉ giúp giảm giá thành mà còn tối ưu hóa khối lượng, tạo ra trải nghiệm cầm nắm nhẹ nhàng.\r\n\r\nVới độ mỏng chỉ 7.99 mm, realme Note 50 thực sự tạo ấn tượng bởi đây được xem là một chiếc máy giá rẻ trang bị pin 5000 mAh nhưng được. tối ưu độ mỏng tốt như vậy. Với độ dày này, người dùng có cơ hội trải nghiệm cảm giác sử dụng mỏng nhẹ cũng như sở hữu được một chiếc máy đẹp có tạo hình đẳng cấp và phong cách hiện đại.\r\n\r\n\r\n\r\nrealme Note 50 không chỉ nổi bật với thiết kế đẹp mắt mà còn chinh phục người dùng bằng tính linh hoạt với cổng sạc Type-C. Công nghệ này giúp tăng cường hiệu suất khi cung cấp tốc độ truyền dữ liệu, đồng thời tạo ra sự thuận lợi với khả năng kết nối hai chiều.\r\n\r\nNgoài ra, với chuẩn kháng nước và bụi IP54, chiếc điện thoại realme này tự tin đối mặt với mọi thách thức môi trường. Bạn không cần phải lo lắng khi sử dụng điện thoại dưới mưa nhỏ hoặc trong môi trường bụi bặm. realme Note 50 giúp bảo vệ thiết bị khỏi những yếu tố gây hại, mang lại sự an tâm và thoải mái khi sử dụng hằng ngày.\r\n\r\nTích hợp vân tay cạnh viền, realme Note 50 giúp trải nghiệm mở khóa trở nên tối ưu hơn. Không chỉ nhanh chóng và hiệu quả, mà còn tăng cường đáng kể về mức độ bảo mật. Việc đặt cảm biến vân tay ở cạnh viền, một vị trí thuận tiện giúp người dùng truy cập thiết bị một cách tự nhiên và nhanh chóng.\r\n\r\n\r\n\r\nMàn hình lớn sử dụng tấm nền IPS LCD\r\nrealme Note 50 được trang bị công nghệ màn hình IPS LCD, một chuẩn khá phổ biến trên các dòng điện thoại giá rẻ - tầm trung. Tấm nền này ngoài ưu điểm về giá còn giúp mang lại chất lượng hình ảnh tốt, độ sáng cao, góc nhìn rộng và màu sắc trung thực.\r\n\r\nVới độ phân giải HD+ (720 x 1600 Pixels), màn hình điện thoại vẫn đảm bảo hình ảnh rõ ràng và chi tiết, làm cho mọi trải nghiệm giải trí trở nên trung thực và sống động. Với kích thước màn hình lên đến 6.74 inch, realme Note 50 đưa người dùng vào một thế giới mở rộng, nơi mọi chi tiết trên màn hình được hiển thị một cách to rõ và dễ nhìn.\r\n\r\n\r\n\r\nMàn hình có độ sáng 560 nits, một mức khá ổn trên một chiếc điện thoại giá rẻ, đủ để người dùng xem được các nội dung cơ bản như tin nhắn, bản đồ hay xem trước ảnh chụp mà ít khi gặp khó khăn. Tuy nhiên, để phục vụ nhu cầu giải trí như xem phim hay chơi game được tốt khi ở môi trường có độ sáng cao, có lẽ bạn nên quan tâm đến các sản phẩm thuộc phân khúc cao hơn.\r\n\r\nNgoài ra, điện thoại còn được trang bị kính cường lực Panda ở phần mặt trước, công nghệ này giúp bảo vệ màn hình tốt hơn trước những vật dụng gây dễ xước, tình trạng hư hại nặng khi va đập cũng được giảm thiểu phần nào nhờ lớp kính cường lực này.\r\n\r\n\r\n\r\nCamera đủ để đáp ứng tốt các nhu cầu quay chụp cơ bản\r\nrealme Note 50 được trang bị camera chính 13 MP, đảm bảo việc bắt gọn mọi khoảnh khắc của cuộc sống. Từ cảnh đẹp tự nhiên đến chụp ảnh gia đình, chiếc điện thoại này là bạn đồng hành đáng tin cậy, cung cấp hình ảnh sắc nét và chi tiết tốt trong tầm giá.\r\n\r\nVới camera phụ 0.08 MP tích hợp, realme Note 50 mang đến khả năng chụp ảnh với hiệu ứng xóa phông tinh tế, từ đó giúp tạo ra những bức ảnh nghệ thuật, nổi bật với sự tập trung vào đối tượng chính, trong khi phông nền được làm mịn màng và ấn tượng.\r\n\r\n\r\n\r\nỞ mặt trước, realme Note 50 sở hữu camera selfie 5 MP, với khả năng xóa phông và nhiều tính năng làm đẹp. Bạn sẽ luôn tự tin khi chụp ảnh tự sướng hay thậm chí là khi tham gia các cuộc họp trực tuyến, với chất lượng ảnh rõ nét và hiệu ứng làm đẹp tự nhiên.\r\n\r\nHiệu năng ổn và hỗ trợ mở rộng bộ nhớ lên đến 2 TB\r\nrealme Note 50 sử dụng chip Unisoc Tiger T612, một sự kết hợp tinh tế giữa hiệu năng và chi phí, chip này đảm bảo rằng chiếc điện thoại có thể chạy mượt mà, từ các ứng dụng hằng ngày đến những trải nghiệm giải trí đa phương tiện cơ bản.\r\n\r\nVì đây là mẫu điện thoại RAM 4 GB, vậy nên những tác vụ đa nhiệm nhiều ứng dụng cùng lúc vẫn sẽ gây đôi chút khó khăn dành cho máy, các hiện tượng như khựng hay đơ máy tạm thời có thể sẽ xảy ra. Tuy nhiên, để khắc phục điều này người dùng nên hạn chế mở nhiều ứng dụng không cần thiết để tối ưu RAM có trên máy.\r\n\r\n\r\n\r\nVới bộ nhớ trong 64 GB có sẵn, realme Note 50 cho phép bạn lưu trữ nhiều dữ liệu, ảnh, video và ứng dụng mà không lo lắng về không gian. Để đảm bảo không gian lưu trữ không bao giờ là vấn đề, điện thoại hỗ trợ mở rộng dung lượng qua thẻ MicroSD lên đến 2 TB, giúp cung cấp cho bạn sự linh hoạt theo nhu cầu của mình.\r\n\r\nPin 5000 mAh đảm bảo trải nghiệm dài lâu mà không cần sạc\r\nVới pin lớn 5000 mAh, realme Note 50 là chiếc điện thoại đồng hành lý tưởng cho những người dùng yêu cầu cao về thời lượng pin. Không còn lo lắng về việc sạc điện thoại liên tục, bạn có thể thoải mái sử dụng nhiều tính năng và trải nghiệm giải trí suốt cả ngày mà không cần phải nghĩ đến việc sạc pin nhiều lần trong ngày.\r\n\r\nMặc dù không phải là sạc nhanh, nhưng sạc 10 W của realme Note 50 lại giúp điện thoại nạp năng lượng một cách từ tốn và an toàn. Với tốc độ này, việc sạc điện thoại vào lúc đi ngủ là một lựa chọn thông minh, bởi cách này giúp tránh tình trạng phải chờ đợi khi bạn thức dậy.\r\n\r\n\r\n\r\nTổng kết, realme Note 50 là một sự chọn lựa hoàn hảo cho những người đang tìm kiếm một chiếc điện thoại nổi bật với mức giá phải chăng. Không đặt quá nhiều trọng tâm vào hiệu năng và chất lượng camera, chiếc điện thoại này đáp ứng nhu cầu sử dụng hằng ngày chủ yếu như lướt web, xem phim và thực hiện cuộc gọi hay nhắn tin.realme Note 50 không chỉ nổi bật với thiết kế đẹp mắt mà còn chinh phục người dùng bằng tính linh hoạt với cổng sạc Type-C. Công nghệ này giúp tăng cường hiệu suất khi cung cấp tốc độ truyền dữ liệu, đồng thời tạo ra sự thuận lợi với khả năng kết nối hai chiều.\r\n\r\nNgoài ra, với chuẩn kháng nước và bụi IP54, chiếc điện thoại realme này tự tin đối mặt với mọi thách thức môi trường. Bạn không cần phải lo lắng khi sử dụng điện thoại dưới mưa nhỏ hoặc trong môi trường bụi bặm. realme Note 50 giúp bảo vệ thiết bị khỏi những yếu tố gây hại, mang lại sự an tâm và thoải mái khi sử dụng hằng ngày.\r\n\r\nTích hợp vân tay cạnh viền, realme Note 50 giúp trải nghiệm mở khóa trở nên tối ưu hơn. Không chỉ nhanh chóng và hiệu quả, mà còn tăng cường đáng kể về mức độ bảo mật. Việc đặt cảm biến vân tay ở cạnh viền, một vị trí thuận tiện giúp người dùng truy cập thiết bị một cách tự nhiên và nhanh chóng.Màn hình lớn sử dụng tấm nền IPS LCD\r\nrealme Note 50 được trang bị công nghệ màn hình IPS LCD, một chuẩn khá phổ biến trên các dòng điện thoại giá rẻ - tầm trung. Tấm nền này ngoài ưu điểm về giá còn giúp mang lại chất lượng hình ảnh tốt, độ sáng cao, góc nhìn rộng và màu sắc trung thực.\r\n\r\nVới độ phân giải HD+ (720 x 1600 Pixels), màn hình điện thoại vẫn đảm bảo hình ảnh rõ ràng và chi tiết, làm cho mọi trải nghiệm giải trí trở nên trung thực và sống động. Với kích thước màn hình lên đến 6.74 inch, realme Note 50 đưa người dùng vào một thế giới mở rộng, nơi mọi chi tiết trên màn hình được hiển thị một cách to rõ và dễ nhìn.','product_images/jugZ5U1Z75DGQGcD7NEoFSHXn8cxGHijhlBiosmK.jpg',1,'2024-03-09 08:00:38','2024-03-09 08:00:38',NULL,NULL,NULL,1),(20,5,'Oppo reno 11 pro xam',13700000,331,'Thông tin sản phẩm\r\nOPPO Reno11 5G tiếp tục mang đến sự hấp dẫn cho người dùng, lấy cảm hứng từ những thành công trước đó. Điểm độc đáo của chiếc điện thoại nằm ở thiết kế thu hút, cấu hình mạnh mẽ và khả năng chụp ảnh ấn tượng. Được tạo ra để đáp ứng một loạt các nhu cầu từ giải trí, nhiếp ảnh đến công việc đòi hỏi hiệu năng cao.\r\nNổi bật nhờ thiết kế lấy cảm hứng từ thiên nhiên\r\nChắc chắn bạn sẽ thấy Reno11 5G ấn tượng ngay từ cái nhìn đầu tiên. Phiên bản này có hai màu sắc độc đáo: xanh lá nhạt và xám. Mặt lưng xanh lá nhạt được phủ lớp vân sáng, lấy cảm hứng từ biển xanh, tạo nên hiệu ứng chuyển sắc lấp lánh giống như sóng biển, làm cho chiếc điện thoại luôn nổi bật và thu hút. Trong khi đó, màu xám mang lại vẻ đẹp tinh tế và tối giản, phản ánh một phong cách sang trọng và lịch lãm.Một đặc điểm nổi bật khác của chiếc điện thoại OPPO này là cụm camera được thiết kế theo hình dáng bầu dục độc đáo, tạo điểm nhấn đặc biệt cho tổng thể thiết kế. Viền xung quanh camera được chế tác một cách tinh tế, tạo nên sự hài hòa và sang trọng, làm tăng thêm vẻ đẹp thẩm mỹ của chiếc điện thoại và khiến nó trở nên thú vị và cuốn hút hơn.Loa kép với công nghệ âm thanh tiên tiến cũng là một điểm mạnh của Reno11 5G. Âm thanh sống động, rõ ràng và mạnh mẽ mang lại trải nghiệm giải trí tuyệt vời. Mình thực sự thích chất âm mà máy mang lại, mọi thứ được tái hiện lại to rõ và trong trẻo, kể cả khi nghe ở mức âm lượng lớn cũng không có hiện tượng bị rè.Ngoài ra, Reno11 5G không chỉ là một chiếc điện thoại đẹp mắt, mà còn là một trợ thủ đáng tin cậy trong mọi hoàn cảnh. Khả năng chống nước, bụi IPX4 giúp bảo vệ thiết bị khỏi những rủi ro ngoài ý muốn, đảm bảo được độ bền để mình có thể an tâm sử dụng trong mọi hoàn cảnh như đi mưa hay vướng bụi nơi công trường, đường phố.','product_images/ZpK6MaV8yvqnsEvEh8ksVeYj5066bqEd1CrmGztI.jpg',1,'2024-03-09 08:01:50','2024-03-09 08:01:50',NULL,NULL,NULL,2),(21,5,'Oppo reno 10 pro grey',10250000,1250,'Thông tin sản phẩm\r\nOPPO Reno11 5G tiếp tục mang đến sự hấp dẫn cho người dùng, lấy cảm hứng từ những thành công trước đó. Điểm độc đáo của chiếc điện thoại nằm ở thiết kế thu hút, cấu hình mạnh mẽ và khả năng chụp ảnh ấn tượng. Được tạo ra để đáp ứng một loạt các nhu cầu từ giải trí, nhiếp ảnh đến công việc đòi hỏi hiệu năng cao.\r\nNổi bật nhờ thiết kế lấy cảm hứng từ thiên nhiên\r\nChắc chắn bạn sẽ thấy Reno11 5G ấn tượng ngay từ cái nhìn đầu tiên. Phiên bản này có hai màu sắc độc đáo: xanh lá nhạt và xám. Mặt lưng xanh lá nhạt được phủ lớp vân sáng, lấy cảm hứng từ biển xanh, tạo nên hiệu ứng chuyển sắc lấp lánh giống như sóng biển, làm cho chiếc điện thoại luôn nổi bật và thu hút. Trong khi đó, màu xám mang lại vẻ đẹp tinh tế và tối giản, phản ánh một phong cách sang trọng và lịch lãm.Một đặc điểm nổi bật khác của chiếc điện thoại OPPO này là cụm camera được thiết kế theo hình dáng bầu dục độc đáo, tạo điểm nhấn đặc biệt cho tổng thể thiết kế. Viền xung quanh camera được chế tác một cách tinh tế, tạo nên sự hài hòa và sang trọng, làm tăng thêm vẻ đẹp thẩm mỹ của chiếc điện thoại và khiến nó trở nên thú vị và cuốn hút hơn.Loa kép với công nghệ âm thanh tiên tiến cũng là một điểm mạnh của Reno11 5G. Âm thanh sống động, rõ ràng và mạnh mẽ mang lại trải nghiệm giải trí tuyệt vời. Mình thực sự thích chất âm mà máy mang lại, mọi thứ được tái hiện lại to rõ và trong trẻo, kể cả khi nghe ở mức âm lượng lớn cũng không có hiện tượng bị rè.Ngoài ra, Reno11 5G không chỉ là một chiếc điện thoại đẹp mắt, mà còn là một trợ thủ đáng tin cậy trong mọi hoàn cảnh. Khả năng chống nước, bụi IPX4 giúp bảo vệ thiết bị khỏi những rủi ro ngoài ý muốn, đảm bảo được độ bền để mình có thể an tâm sử dụng trong mọi hoàn cảnh như đi mưa hay vướng bụi nơi công trường, đường phố.','product_images/WfRHUIeutyU6iJ9IWNvh7RJqiec2vmH35BlLiwhk.jpg',0,'2024-03-09 08:02:24','2024-03-09 08:02:24',NULL,NULL,18,4),(23,5,'Oppo reno 10 pro blue',10250000,357,'Thông tin sản phẩm\r\nOPPO Reno11 5G tiếp tục mang đến sự hấp dẫn cho người dùng, lấy cảm hứng từ những thành công trước đó. Điểm độc đáo của chiếc điện thoại nằm ở thiết kế thu hút, cấu hình mạnh mẽ và khả năng chụp ảnh ấn tượng. Được tạo ra để đáp ứng một loạt các nhu cầu từ giải trí, nhiếp ảnh đến công việc đòi hỏi hiệu năng cao.\r\nNổi bật nhờ thiết kế lấy cảm hứng từ thiên nhiên\r\nChắc chắn bạn sẽ thấy Reno11 5G ấn tượng ngay từ cái nhìn đầu tiên. Phiên bản này có hai màu sắc độc đáo: xanh lá nhạt và xám. Mặt lưng xanh lá nhạt được phủ lớp vân sáng, lấy cảm hứng từ biển xanh, tạo nên hiệu ứng chuyển sắc lấp lánh giống như sóng biển, làm cho chiếc điện thoại luôn nổi bật và thu hút. Trong khi đó, màu xám mang lại vẻ đẹp tinh tế và tối giản, phản ánh một phong cách sang trọng và lịch lãm.Một đặc điểm nổi bật khác của chiếc điện thoại OPPO này là cụm camera được thiết kế theo hình dáng bầu dục độc đáo, tạo điểm nhấn đặc biệt cho tổng thể thiết kế. Viền xung quanh camera được chế tác một cách tinh tế, tạo nên sự hài hòa và sang trọng, làm tăng thêm vẻ đẹp thẩm mỹ của chiếc điện thoại và khiến nó trở nên thú vị và cuốn hút hơn.Loa kép với công nghệ âm thanh tiên tiến cũng là một điểm mạnh của Reno11 5G. Âm thanh sống động, rõ ràng và mạnh mẽ mang lại trải nghiệm giải trí tuyệt vời. Mình thực sự thích chất âm mà máy mang lại, mọi thứ được tái hiện lại to rõ và trong trẻo, kể cả khi nghe ở mức âm lượng lớn cũng không có hiện tượng bị rè.Ngoài ra, Reno11 5G không chỉ là một chiếc điện thoại đẹp mắt, mà còn là một trợ thủ đáng tin cậy trong mọi hoàn cảnh. Khả năng chống nước, bụi IPX4 giúp bảo vệ thiết bị khỏi những rủi ro ngoài ý muốn, đảm bảo được độ bền để mình có thể an tâm sử dụng trong mọi hoàn cảnh như đi mưa hay vướng bụi nơi công trường, đường phố.','product_images/NacK4YIRcTiarLotD1gEEkh86JUACx0cuc29ofJq.jpg',0,'2024-03-09 08:03:32','2024-03-09 08:03:32',NULL,NULL,NULL,3),(24,7,'Oppo reno 8 pro đen 256GB',6830000,122,'Thông tin sản phẩm\r\nOPPO Reno11 5G tiếp tục mang đến sự hấp dẫn cho người dùng, lấy cảm hứng từ những thành công trước đó. Điểm độc đáo của chiếc điện thoại nằm ở thiết kế thu hút, cấu hình mạnh mẽ và khả năng chụp ảnh ấn tượng. Được tạo ra để đáp ứng một loạt các nhu cầu từ giải trí, nhiếp ảnh đến công việc đòi hỏi hiệu năng cao.\r\nNổi bật nhờ thiết kế lấy cảm hứng từ thiên nhiên\r\nChắc chắn bạn sẽ thấy Reno11 5G ấn tượng ngay từ cái nhìn đầu tiên. Phiên bản này có hai màu sắc độc đáo: xanh lá nhạt và xám. Mặt lưng xanh lá nhạt được phủ lớp vân sáng, lấy cảm hứng từ biển xanh, tạo nên hiệu ứng chuyển sắc lấp lánh giống như sóng biển, làm cho chiếc điện thoại luôn nổi bật và thu hút. Trong khi đó, màu xám mang lại vẻ đẹp tinh tế và tối giản, phản ánh một phong cách sang trọng và lịch lãm.Một đặc điểm nổi bật khác của chiếc điện thoại OPPO này là cụm camera được thiết kế theo hình dáng bầu dục độc đáo, tạo điểm nhấn đặc biệt cho tổng thể thiết kế. Viền xung quanh camera được chế tác một cách tinh tế, tạo nên sự hài hòa và sang trọng, làm tăng thêm vẻ đẹp thẩm mỹ của chiếc điện thoại và khiến nó trở nên thú vị và cuốn hút hơn.Loa kép với công nghệ âm thanh tiên tiến cũng là một điểm mạnh của Reno11 5G. Âm thanh sống động, rõ ràng và mạnh mẽ mang lại trải nghiệm giải trí tuyệt vời. Mình thực sự thích chất âm mà máy mang lại, mọi thứ được tái hiện lại to rõ và trong trẻo, kể cả khi nghe ở mức âm lượng lớn cũng không có hiện tượng bị rè.Ngoài ra, Reno11 5G không chỉ là một chiếc điện thoại đẹp mắt, mà còn là một trợ thủ đáng tin cậy trong mọi hoàn cảnh. Khả năng chống nước, bụi IPX4 giúp bảo vệ thiết bị khỏi những rủi ro ngoài ý muốn, đảm bảo được độ bền để mình có thể an tâm sử dụng trong mọi hoàn cảnh như đi mưa hay vướng bụi nơi công trường, đường phố.','product_images/vs7dlsZMIlqFHC0ehU4W6UJnkUDMMq8CxcfwvoTs.jpg',0,'2024-03-09 08:03:57','2024-04-22 00:31:28',NULL,NULL,NULL,3),(47,6,'IPHONE XXX update',135789,13567,'IPHONE XXX chưa ra','product_images/a963a55ae5346200564ea06e002bd802derick-anies-hDJT_ERrB-w-unsplash.jpg',0,'2024-04-03 01:29:13','2024-04-03 03:08:38',NULL,NULL,NULL,5);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `promotion_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_code` varchar(250) NOT NULL,
  `value` int NOT NULL,
  `promotion_used` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`promotion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (1,'5213',10,0,1,'2024-10-12 09:34:13','2024-10-12 09:34:13',NULL,NULL,NULL),(15,'5214',10,1,1,'2024-10-12 21:33:39','2024-10-12 21:33:39',NULL,NULL,NULL),(16,'959480',10,0,1,'2024-12-06 12:04:02','2024-12-06 12:04:02',NULL,NULL,NULL),(17,'163',10,1,1,'2024-12-06 12:04:22','2024-12-06 12:04:22',NULL,NULL,NULL);
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,2,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,4,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,5,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,6,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,7,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,8,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,9,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,10,1,'2024-10-01 03:13:52','2024-10-01 03:13:52',NULL,NULL,NULL),(1,11,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,12,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,13,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,14,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,15,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,16,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,17,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,18,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,19,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,20,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,21,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,22,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,23,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,24,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,25,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,26,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(1,27,1,'2024-10-01 03:13:53','2024-10-01 03:13:53',NULL,NULL,NULL),(17,1,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,2,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,4,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,8,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,12,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,16,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,17,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(17,19,1,'2024-12-03 09:57:33','2024-12-03 09:57:33',NULL,NULL,NULL),(18,1,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,2,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,4,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,5,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,6,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,10,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,11,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(18,15,1,'2024-10-01 02:19:19','2024-10-01 02:19:19',NULL,NULL,NULL),(19,1,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,2,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,4,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,6,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,7,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,8,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,26,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(19,27,1,'2024-12-03 09:57:41','2024-12-03 09:57:41',NULL,NULL,NULL),(20,1,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,2,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,4,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,6,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,20,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,21,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,23,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(20,25,1,'2024-12-03 09:57:48','2024-12-03 09:57:48',NULL,NULL,NULL),(21,1,1,'2024-12-03 09:58:01','2024-12-03 09:58:01',NULL,NULL,NULL),(21,2,1,'2024-12-03 09:58:01','2024-12-03 09:58:01',NULL,NULL,NULL),(21,4,1,'2024-12-03 09:58:01','2024-12-03 09:58:01',NULL,NULL,NULL),(21,5,1,'2024-12-03 09:58:01','2024-12-03 09:58:01',NULL,NULL,NULL);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin',1,'2024-09-29 19:43:32','2024-10-01 03:13:52',NULL,23,NULL),(17,'Nhân viên cấp 2',1,'2024-10-01 01:05:30','2024-12-03 09:57:33',18,18,NULL),(18,'Quản lý chi nhánh',0,'2024-10-01 02:19:19','2024-10-01 02:19:19',18,NULL,NULL),(19,'Nhân viên cấp 1',1,'2024-10-01 02:29:54','2024-12-03 09:57:41',18,18,NULL),(20,'Quản lý cấp 2',1,'2024-10-01 02:30:06','2024-12-03 09:57:48',18,18,NULL),(21,'Quản lý cấp 1',1,'2024-10-06 22:23:35','2024-12-03 09:58:01',18,18,NULL),(22,'Nhân viên cấp 3',0,'2024-12-03 15:11:29','2024-12-03 15:11:29',18,NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '1',
  `role_id` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (18,'Admin','admin@gmail.com','$2y$12$unqE.xm8fhMfrRePo19dZuvwFUrR4JGyRCAhvPCoNMlVlOLDZ/MEa','Quận 6 ','03423421312',NULL,1,1,'2024-09-29 19:44:11','2024-12-03 08:35:41',NULL,18,NULL),(30,'nguyễn văn 2','nguyenvan2@gmail.com','$2y$12$7yD1bqyu7dW8ev/nui8PKO.h/famxy.05WbO43PEfwVdtXpM7hTA2','62 Trương Phước Phan, Phường Bình Trị Đông, Quận Bình Tân, Hồ Chí Minh','0934190085',0,17,1,'2024-12-03 10:41:08','2024-12-03 10:41:08',18,NULL,NULL),(31,'nguyễn văn 3','nguyenvan3@gmail.com','$2y$12$HzIT5hbBxTYk7Snj9uTMDO2aOdW2YEiN1wwvHdfMyENyK01a7I3Lm','Quận 10','',1,17,1,'2024-12-03 10:48:06','2024-12-03 10:48:06',18,NULL,NULL),(32,'Nguyễn Thành Chí Tâm','nguyenthanhchitam@gmail.com','$2y$12$fQ3vMY5jA9I7/j14O8Nq/.0qIQ39Sd.dVWbfLeYGLNemByHCLa9Bu','Quận 8','0934190076',1,17,0,'2024-12-04 11:28:12','2024-12-04 11:28:12',18,NULL,NULL);
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

-- Dump completed on 2024-12-06 20:34:08
