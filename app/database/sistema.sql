CREATE DATABASE  IF NOT EXISTS `sistema` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sistema`;
-- MySQL dump 10.13  Distrib 5.5.52, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: sistema
-- ------------------------------------------------------
-- Server version	5.5.52-0+deb8u1

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
-- Table structure for table `candidate`
--

DROP TABLE IF EXISTS `candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `passcode` varchar(255) NOT NULL,
  `active` enum('s','n') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_1_idx` (`state_id`),
  CONSTRAINT `fk_city_1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--

LOCK TABLES `city` WRITE;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
/*!40000 ALTER TABLE `city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city_interest`
--

DROP TABLE IF EXISTS `city_interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city_interest` (
  `id` int(11) NOT NULL,
  `expansion_plan_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `goal_units` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_interest_1_idx` (`expansion_plan_id`),
  KEY `fk_city_interest_2_idx` (`city_id`),
  CONSTRAINT `fk_city_interest_1` FOREIGN KEY (`expansion_plan_id`) REFERENCES `expansion_plan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_city_interest_2` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city_interest`
--

LOCK TABLES `city_interest` WRITE;
/*!40000 ALTER TABLE `city_interest` DISABLE KEYS */;
/*!40000 ALTER TABLE `city_interest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `segment_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `active` enum('s','n') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_segment_1_idx` (`segment_id`),
  CONSTRAINT `fk_segment_1` FOREIGN KEY (`segment_id`) REFERENCES `segment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,1,'Empresa','$2y$10$AcqDGfI4sko7XOrZbD2hj.mdlg.hJvXpF2MHGjMnmUWvFaTVIFVdS','','s','2016-08-31 14:04:59','2016-08-31 14:04:59',NULL),(2,1,'Empresa 2','$2y$10$kXElCly3tP8zYU5LbkyINuQF4.q8oK.3THB0J9/pYjOkDg8nHVxTS','','s','2016-09-01 13:02:22','2016-09-01 13:02:22',NULL),(3,2,'Empresa','$2y$10$259HqKq.DOsVJhBXVYG7JurY8CsKPOk.LJMSSBkWTtoTbyZxJGcLG','','s','2016-09-09 18:37:38','2016-09-09 18:37:38',NULL),(4,2,'Empresa','$2y$10$Drk7z9kfpFhWJRB15psJueCloVzSHOE4mVVXOS1.LHIu21W85nN9G','','s','2016-09-09 18:37:54','2016-09-09 18:37:54',NULL),(5,2,'Empresa','$2y$10$AhrfeNwNd/cGWqOa4O82U.6BwnK.eAucP/J4TbloLvlaYE9rUjUI2','','s','2016-09-09 18:38:07','2016-09-09 18:38:07',NULL),(6,11,'Teste Completo','$2y$10$gbRmA4Kqnk.EKrZeSIWJMOafeCvgYBxpcWVxv8ve89qX0ZxTaG9jG','','s','2016-09-09 18:41:16','2016-09-09 18:41:16',NULL),(7,1,'Mais um teste','$2y$10$iEDOcUsWObLJIC8NghoIP.9zU3YN5kUwXxP6F63aoybPx/USBXB6a','','s','2016-09-09 18:42:55','2016-09-09 18:42:55',NULL);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_candidate`
--

DROP TABLE IF EXISTS `company_candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_candidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_candidate_idx` (`candidate_id`),
  KEY `fk_company_idx` (`company_id`),
  CONSTRAINT `fk_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_candidate`
--

LOCK TABLES `company_candidate` WRITE;
/*!40000 ALTER TABLE `company_candidate` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_id_idx` (`company_id`),
  KEY `fk_evaluation_1_idx` (`company_id`),
  CONSTRAINT `fk_evaluation_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation`
--

LOCK TABLES `evaluation` WRITE;
/*!40000 ALTER TABLE `evaluation` DISABLE KEYS */;
INSERT INTO `evaluation` VALUES (1,1,'Seleção 2016/1','Formulário destinados a interessados do evento de Março',NULL,NULL,NULL),(2,1,'Geral 2015','Formulário de inscritos em 2015',NULL,NULL,NULL);
/*!40000 ALTER TABLE `evaluation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expansion_plan`
--

DROP TABLE IF EXISTS `expansion_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expansion_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `general_goal_units` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`company_id`),
  KEY `fk_expansion_plan_1_idx` (`company_id`),
  CONSTRAINT `fk_expansion_plan_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expansion_plan`
--

LOCK TABLES `expansion_plan` WRITE;
/*!40000 ALTER TABLE `expansion_plan` DISABLE KEYS */;
INSERT INTO `expansion_plan` VALUES (1,1,'Inverno 2016','2016-04-01','2016-10-30',10,'0000-00-00 00:00:00',NULL,NULL),(2,1,'Evento Franquias 2011','2016-05-01','2016-05-30',8,'2016-09-06 01:51:16','2016-09-06 13:08:09',NULL),(3,1,'Mais um teste 2016','2015-03-15','2015-04-15',11,'2016-09-06 01:51:55','2016-09-06 01:51:55',NULL),(4,1,'ExpoFranquias Miami','2010-01-12','2011-02-12',0,'2016-09-06 13:18:29','2016-09-06 16:47:54',NULL);
/*!40000 ALTER TABLE `expansion_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2016_09_05_225115_create_password_reminders_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `option`
--

DROP TABLE IF EXISTS `option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `text` varchar(50) NOT NULL,
  `rating` decimal(10,1) NOT NULL DEFAULT '0.0',
  `order` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_option_1_idx` (`question_id`),
  CONSTRAINT `fk_option_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `option`
--

LOCK TABLES `option` WRITE;
/*!40000 ALTER TABLE `option` DISABLE KEYS */;
INSERT INTO `option` VALUES (9,9,'Opção 1',0.4,1,'2016-09-09 13:22:46','2016-09-09 13:22:46',NULL),(10,9,'Opção 2',0.9,2,'2016-09-09 13:22:46','2016-09-09 13:22:46',NULL);
/*!40000 ALTER TABLE `option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reminders`
--

DROP TABLE IF EXISTS `password_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reminders` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reminders`
--

LOCK TABLES `password_reminders` WRITE;
/*!40000 ALTER TABLE `password_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process`
--

DROP TABLE IF EXISTS `process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `progress` decimal(10,2) NOT NULL,
  `status` char(1) NOT NULL COMMENT 'i = iniciado,\ne = em andamento,\nf = finalizado,\na = aprovado,\nr = reprovado.',
  `minimum_note` decimal(10,2) NOT NULL,
  `final_note` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_process_1_idx` (`candidate_id`),
  KEY `fk_process_2_idx` (`evaluation_id`),
  CONSTRAINT `fk_process_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_process_2` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process`
--

LOCK TABLES `process` WRITE;
/*!40000 ALTER TABLE `process` DISABLE KEYS */;
/*!40000 ALTER TABLE `process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_answer`
--

DROP TABLE IF EXISTS `process_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `text` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_process_answer_2_idx` (`question_id`),
  KEY `fk_process_answer_3_idx` (`option_id`),
  KEY `fk_process_answer_1_idx` (`process_id`),
  CONSTRAINT `fk_process_answer_1` FOREIGN KEY (`process_id`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_process_answer_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_process_answer_3` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_answer`
--

LOCK TABLES `process_answer` WRITE;
/*!40000 ALTER TABLE `process_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` char(1) NOT NULL COMMENT 't = text,\no = option,\np = paragraph,\nm = multiple selection,\nt = telephone,\nd = date,\nc = cpf or cnpj',
  `mandatory` enum('s','n') NOT NULL DEFAULT 'n',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rj45_idx` (`company_id`),
  CONSTRAINT `fk_rj45` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,1,'Data de Nascimento','f','n','2016-09-08 23:20:02','2016-09-08 23:20:02',NULL),(2,1,'Nome Completo','a','s','2016-09-08 23:20:02','2016-09-08 23:20:02',NULL),(3,1,'CPF','g','n','2016-09-08 23:20:40','2016-09-08 23:20:40',NULL),(4,1,'O que levou você a escolher nossa franquia?','a','s','2016-09-08 23:21:09','2016-09-08 23:21:09',NULL),(9,1,'Pergunta com Opções','c','s','2016-09-09 13:22:46','2016-09-09 13:22:46',NULL);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_evaluation`
--

DROP TABLE IF EXISTS `question_evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_evaluation` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `rating` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_evaluation_1_idx` (`question_id`),
  KEY `fk_question_evaluation_2_idx` (`evaluation_id`),
  CONSTRAINT `fk_question_evaluation_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_evaluation_2` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_evaluation`
--

LOCK TABLES `question_evaluation` WRITE;
/*!40000 ALTER TABLE `question_evaluation` DISABLE KEYS */;
/*!40000 ALTER TABLE `question_evaluation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `segment`
--

DROP TABLE IF EXISTS `segment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `segment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `segment`
--

LOCK TABLES `segment` WRITE;
/*!40000 ALTER TABLE `segment` DISABLE KEYS */;
INSERT INTO `segment` VALUES (1,'Acessórios Pessoais, Calçados'),(2,'Alimentação'),(3,'Bares, Restaurantes, Padarias e Pizzarias'),(4,'Bebidas, Cafés, Doces, Salgados e Sorvetes'),(5,'Beleza, Saúde, Farmácias e Produtos Naturais'),(6,'Bijuterias, Joias e Óculos'),(7,'Comunicação'),(8,'Construção e Imobiliárias'),(9,'Cosméticos e Perfumaria'),(10,'Educação e Treinamento'),(11,'Entretenimento, Brinquedos e Lazer'),(12,'Escolas de Idiomas'),(13,'Estética, Medicina e Odontologia'),(14,'Gás'),(15,'Hotelaria e Turismo'),(16,'Informática e Eletrônicos'),(17,'Limpeza e Conservação'),(18,'Livrarias, Fotografia, Gráficas e Sinalização'),(19,'Móveis, Decoração e Presentes'),(20,'Negócios, Serviços e Conveniência'),(21,'Pet Shop, Clínica Animal'),(22,'Serviços Automotivos'),(23,'Vestuário');
/*!40000 ALTER TABLE `segment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `initials` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_id_idx` (`company_id`),
  CONSTRAINT `fk_company_id` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'Usuário Um','$2y$10$Qov9031Z/T.bPcNqJ6/8cuepbEzROc.y/qJZCEOyed9qNqNM1B74y','email@email.com','2016-08-31 14:04:59','2016-09-09 18:22:56',NULL,'wSwU6DuezgtAEMFl44qLPnwAoE8bdP3fCQsyB5QnRGoUzBvaB3D6LsQtoaJZ'),(2,2,'Usuario 2','$2y$10$NMZo.KN0CZXG3SnB9ROEgee0u/GBfcdotZEpV2/gh4/vGYTdjPJUC','email@email.com2','2016-09-01 13:02:22','2016-09-02 01:26:24',NULL,'Ax9dX1MiBZqWxsTBEEGZk3CyvsmPvF5DG1r8tDKclN1AacGgfnaALDErQbaw'),(3,1,'Usuário Dois','$2y$10$ulFzM7x5HB0Q2TfomwFMOOuN9hbCmlbdpMNPNWoytq2b6j63soJ2e','email@email2.com',NULL,'2016-09-09 14:18:56',NULL,NULL),(4,1,'Usuário Três','$2y$10$S.IfiYojVkiW6ah7xH8IBuYBac.q352Jvozd0jI593vw6vXH2cQE6','m@s.n','2016-09-04 01:22:37','2016-09-09 14:19:05',NULL,'hn41OO1q3UHOMTqz75rRTbWwUWwoOzEW5uVVmFW82O5LTzCglGLMPuHB6leb'),(5,5,'Usuario','$2y$10$jLb2DhiXv0Hk8IbyU776G.8iOL48JVVhQ/OS2GjhOkavykwsd6MIC','teste@teste.teste','2016-09-09 18:38:07','2016-09-09 18:38:07',NULL,NULL),(6,6,'Teste','$2y$10$V48ZkKa5Ie2hlkPMZ8YcP.Rdax8F/3X6Y.LzjxujIqm1bdjvzj/f.','teste@teste.teste2','2016-09-09 18:41:16','2016-09-09 18:42:36',NULL,'0fy4Bpc0aFv9hIpSUkCRo8dEiWbPHVegQ2ZxBNpX6fuz8sbyWJgTsKGoE92j'),(7,7,'Usuario ABC','$2y$10$Vu.4R2fLF4J8du6R8bDEWOLimcM1GmmqiJqngZZDQ3EO8FC0WhD5S','teste@teste.teste23','2016-09-09 18:42:56','2016-09-09 18:43:07',NULL,'6sr40r5Kn0rkTQ1HDLpnLhBPR8LUuUiBoI7QI5uAchK2sUw91iePqdLcrO43');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-10-20  8:01:31
