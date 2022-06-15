-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mg_db
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_UNIQUE` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_actividades`
--

DROP TABLE IF EXISTS `t_sgcv_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_actividades` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL,
  `objetivo_id` bigint(20) NOT NULL,
  `fecha_comienzo` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `proced_politicas` varchar(250) DEFAULT NULL,
  `doc_politicas_id` bigint(20) DEFAULT NULL,
  `doc_adjunto_id` bigint(20) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activities_objectives1_idx` (`objetivo_id`),
  KEY `fk_activities_documents1_idx` (`doc_adjunto_id`),
  KEY `fk_T_SGCV_Actividades_T_SGCV_Documentos1_idx` (`doc_politicas_id`),
  CONSTRAINT `fk_T_SGCV_Actividades_T_SGCV_Documentos1` FOREIGN KEY (`doc_politicas_id`) REFERENCES `t_sgcv_documentos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_activities_documents1` FOREIGN KEY (`doc_adjunto_id`) REFERENCES `t_sgcv_documentos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_activities_objectives1` FOREIGN KEY (`objetivo_id`) REFERENCES `t_sgcv_objetivos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_actividades`
--

LOCK TABLES `t_sgcv_actividades` WRITE;
/*!40000 ALTER TABLE `t_sgcv_actividades` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_actividades_viajes`
--

DROP TABLE IF EXISTS `t_sgcv_actividades_viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_actividades_viajes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(450) NOT NULL,
  `tipo` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'area related activities or non related activities',
  `agenda_viaje_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_travel_activities_travel_schedule1_idx` (`agenda_viaje_id`),
  CONSTRAINT `fk_travel_activities_travel_schedule1` FOREIGN KEY (`agenda_viaje_id`) REFERENCES `t_sgcv_agenda_viajes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_actividades_viajes`
--

LOCK TABLES `t_sgcv_actividades_viajes` WRITE;
/*!40000 ALTER TABLE `t_sgcv_actividades_viajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_actividades_viajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_agenda_viajes`
--

DROP TABLE IF EXISTS `t_sgcv_agenda_viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_agenda_viajes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) NOT NULL,
  `sede_id` bigint(20) NOT NULL,
  `viaje_comienzo` datetime NOT NULL,
  `viaje_fin` datetime NOT NULL,
  `vehiculo` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'campo de ',
  `hospedaje` tinyint(4) NOT NULL DEFAULT 0,
  `viaticos` tinyint(4) NOT NULL DEFAULT 0,
  `validacion_uno` tinyint(4) NOT NULL DEFAULT 0,
  `validacion_dos` tinyint(4) NOT NULL DEFAULT 0,
  `estado` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 = eliminado\n1 = enviado a gerente de area\n2 = aprovado por el gerente de area\n3 = rechazado por el gerente de area\n4 = enviado a area de gestion\n5 = aprovado a area de gestion\n6 = rechazado a area de gestion',
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_travel_schedule_users1_idx` (`usuario_id`),
  KEY `fk_travel_schedule_branch1_idx` (`sede_id`),
  CONSTRAINT `fk_travel_schedule_branch1` FOREIGN KEY (`sede_id`) REFERENCES `t_sgcv_sedes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_travel_schedule_users1` FOREIGN KEY (`usuario_id`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_agenda_viajes`
--

LOCK TABLES `t_sgcv_agenda_viajes` WRITE;
/*!40000 ALTER TABLE `t_sgcv_agenda_viajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_agenda_viajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_areas`
--

DROP TABLE IF EXISTS `t_sgcv_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `vis_matriz` tinyint(4) NOT NULL DEFAULT 0,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_areas`
--

LOCK TABLES `t_sgcv_areas` WRITE;
/*!40000 ALTER TABLE `t_sgcv_areas` DISABLE KEYS */;
INSERT INTO `t_sgcv_areas` VALUES (1,'Admin',0,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(2,'IT',0,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(3,'Gerencia',0,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(4,'Administracion',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(5,'Finanzas',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(6,'Comercial',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(7,'Operaciones',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(8,'DDNN',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(9,'Legal',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(10,'Gestión Humana',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00'),(11,'Gestión',1,1,'2022-06-14 12:00:00','2022-06-14 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_comentarios`
--

DROP TABLE IF EXISTS `t_sgcv_comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_comentarios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) NOT NULL,
  `actividad_id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comments_activities1_idx` (`actividad_id`),
  KEY `fk_comments_users1_idx` (`usuario_id`),
  CONSTRAINT `fk_comments_activities1` FOREIGN KEY (`actividad_id`) REFERENCES `t_sgcv_actividades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1` FOREIGN KEY (`usuario_id`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_comentarios`
--

LOCK TABLES `t_sgcv_comentarios` WRITE;
/*!40000 ALTER TABLE `t_sgcv_comentarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_correos`
--

DROP TABLE IF EXISTS `t_sgcv_correos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_correos` (
  `id` bigint(20) NOT NULL,
  `usr_remitente` bigint(20) NOT NULL,
  `usr_receptor` bigint(20) NOT NULL,
  `agenda_viaje_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mail_sent_users1_idx` (`usr_remitente`),
  KEY `fk_mail_sent_users2_idx` (`usr_receptor`),
  KEY `fk_mail_sent_travel_schedule1_idx` (`agenda_viaje_id`),
  CONSTRAINT `fk_mail_sent_travel_schedule1` FOREIGN KEY (`agenda_viaje_id`) REFERENCES `t_sgcv_agenda_viajes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_sent_users1` FOREIGN KEY (`usr_remitente`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_sent_users2` FOREIGN KEY (`usr_receptor`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_correos`
--

LOCK TABLES `t_sgcv_correos` WRITE;
/*!40000 ALTER TABLE `t_sgcv_correos` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_correos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_documentos`
--

DROP TABLE IF EXISTS `t_sgcv_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_documentos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `file` varchar(150) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_documentos`
--

LOCK TABLES `t_sgcv_documentos` WRITE;
/*!40000 ALTER TABLE `t_sgcv_documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_kpi_calendario`
--

DROP TABLE IF EXISTS `t_sgcv_kpi_calendario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_kpi_calendario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kpi_id` bigint(20) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `valor` float NOT NULL,
  `tipo` tinyint(4) NOT NULL DEFAULT 1,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_kpi_calendar_kpis1_idx` (`kpi_id`),
  CONSTRAINT `fk_kpi_calendar_kpis1` FOREIGN KEY (`kpi_id`) REFERENCES `t_sgcv_kpis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_kpi_calendario`
--

LOCK TABLES `t_sgcv_kpi_calendario` WRITE;
/*!40000 ALTER TABLE `t_sgcv_kpi_calendario` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_kpi_calendario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_kpis`
--

DROP TABLE IF EXISTS `t_sgcv_kpis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_kpis` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `metrica_id` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_kpis_metrics1_idx` (`metrica_id`),
  CONSTRAINT `fk_kpis_metrics1` FOREIGN KEY (`metrica_id`) REFERENCES `t_sgcv_metricas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_kpis`
--

LOCK TABLES `t_sgcv_kpis` WRITE;
/*!40000 ALTER TABLE `t_sgcv_kpis` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_kpis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_metricas`
--

DROP TABLE IF EXISTS `t_sgcv_metricas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_metricas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `simbolo` varchar(5) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_metricas`
--

LOCK TABLES `t_sgcv_metricas` WRITE;
/*!40000 ALTER TABLE `t_sgcv_metricas` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_metricas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_obj_kpi`
--

DROP TABLE IF EXISTS `t_sgcv_obj_kpi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_obj_kpi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kpi_id` bigint(20) NOT NULL,
  `objectivo_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_obj_kpi_kpis1_idx` (`kpi_id`),
  KEY `fk_obj_kpi_objectives1_idx` (`objectivo_id`),
  CONSTRAINT `fk_obj_kpi_kpis1` FOREIGN KEY (`kpi_id`) REFERENCES `t_sgcv_kpis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_obj_kpi_objectives1` FOREIGN KEY (`objectivo_id`) REFERENCES `t_sgcv_objetivos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_obj_kpi`
--

LOCK TABLES `t_sgcv_obj_kpi` WRITE;
/*!40000 ALTER TABLE `t_sgcv_obj_kpi` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_obj_kpi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_objetivos`
--

DROP TABLE IF EXISTS `t_sgcv_objetivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_objetivos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `tema_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_objectives_themes1_idx` (`tema_id`),
  CONSTRAINT `fk_objectives_themes1` FOREIGN KEY (`tema_id`) REFERENCES `t_sgcv_temas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_objetivos`
--

LOCK TABLES `t_sgcv_objetivos` WRITE;
/*!40000 ALTER TABLE `t_sgcv_objetivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_objetivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_opcion_perfil`
--

DROP TABLE IF EXISTS `t_sgcv_opcion_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_opcion_perfil` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perfil_id` bigint(20) NOT NULL,
  `opcion_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_T_SGCV_Opcion_Perfil_T_SGCV_Opcion1_idx` (`opcion_id`),
  KEY `fk_T_SGCV_Opcion_Perfil_T_SGCV_Perfil1_idx` (`perfil_id`),
  CONSTRAINT `fk_T_SGCV_Opcion_Perfil_T_SGCV_Opcion1` FOREIGN KEY (`opcion_id`) REFERENCES `t_sgcv_opciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_T_SGCV_Opcion_Perfil_T_SGCV_Perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `t_sgcv_perfiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_opcion_perfil`
--

LOCK TABLES `t_sgcv_opcion_perfil` WRITE;
/*!40000 ALTER TABLE `t_sgcv_opcion_perfil` DISABLE KEYS */;
INSERT INTO `t_sgcv_opcion_perfil` VALUES (1,1,1,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(2,1,2,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(3,1,3,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(4,1,4,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(5,1,5,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(6,1,6,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(7,1,7,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(8,1,8,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(9,1,9,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(10,3,1,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(11,3,4,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(12,3,5,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(13,3,6,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(14,3,7,1,'2022-05-28 12:00:00','2022-05-28 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_opcion_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_opciones`
--

DROP TABLE IF EXISTS `t_sgcv_opciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_opciones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opcion` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `url_img` varchar(100) DEFAULT NULL,
  `url_label` varchar(100) DEFAULT NULL,
  `num_orden` int(11) NOT NULL DEFAULT 1,
  `num_nivel` int(11) NOT NULL DEFAULT 1,
  `opcion_padre_id` bigint(20) DEFAULT NULL,
  `tipo_opcion` tinyint(4) NOT NULL DEFAULT 1,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_T_SGCV_Opcion_T_SGCV_Opcion1_idx` (`opcion_padre_id`),
  CONSTRAINT `fk_T_SGCV_Opcion_T_SGCV_Opcion1` FOREIGN KEY (`opcion_padre_id`) REFERENCES `t_sgcv_opciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_opciones`
--

LOCK TABLES `t_sgcv_opciones` WRITE;
/*!40000 ALTER TABLE `t_sgcv_opciones` DISABLE KEYS */;
INSERT INTO `t_sgcv_opciones` VALUES (1,'Agenda Estrategica','','',NULL,1,1,NULL,1,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(2,'Roles','','cil-clipboard',NULL,2,1,1,2,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(3,'Temas','','cil-apps',NULL,3,1,1,2,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(4,'Objetivos','objectives','cil-task',NULL,4,1,1,2,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(5,'Viajes','','',NULL,1,2,NULL,1,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(6,'Agendas','','cil-calendar',NULL,2,2,5,2,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(7,'Informes','','cil-calendar-check',NULL,3,2,5,2,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(8,'Accesos','',NULL,NULL,1,3,NULL,1,1,'2022-05-28 12:00:00','2022-05-28 12:00:00'),(9,'Usuarios','','cil-user',NULL,2,3,8,2,1,'2022-05-28 12:00:00','2022-05-28 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_opciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_perfiles`
--

DROP TABLE IF EXISTS `t_sgcv_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_perfiles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_perfiles`
--

LOCK TABLES `t_sgcv_perfiles` WRITE;
/*!40000 ALTER TABLE `t_sgcv_perfiles` DISABLE KEYS */;
INSERT INTO `t_sgcv_perfiles` VALUES (1,'Admin',1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(2,'Consulta',1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(3,'Registro',1,'2022-05-25 12:00:00','2022-05-25 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_posiciones`
--

DROP TABLE IF EXISTS `t_sgcv_posiciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_posiciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `area_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_position_area1_idx` (`area_id`),
  CONSTRAINT `fk_position_area1` FOREIGN KEY (`area_id`) REFERENCES `t_sgcv_areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_posiciones`
--

LOCK TABLES `t_sgcv_posiciones` WRITE;
/*!40000 ALTER TABLE `t_sgcv_posiciones` DISABLE KEYS */;
INSERT INTO `t_sgcv_posiciones` VALUES (1,'Admin',1,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(2,'IT',1,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(3,'Gerente',1,1,'2022-05-23 12:00:00','2022-05-23 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_posiciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_reporte_actividades`
--

DROP TABLE IF EXISTS `t_sgcv_reporte_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_reporte_actividades` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(500) NOT NULL,
  `tipo` tinyint(4) NOT NULL,
  `acuerdo` varchar(500) DEFAULT NULL,
  `fecha_comienzo` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `agenda_viaje_id` bigint(20) NOT NULL,
  `actividades_viaje_id` bigint(20) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_inform_activities_travel_schedule1_idx` (`agenda_viaje_id`),
  KEY `fk_inform_activities_travel_activities1_idx` (`actividades_viaje_id`),
  CONSTRAINT `fk_inform_activities_travel_activities1` FOREIGN KEY (`actividades_viaje_id`) REFERENCES `t_sgcv_actividades_viajes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_inform_activities_travel_schedule1` FOREIGN KEY (`agenda_viaje_id`) REFERENCES `t_sgcv_agenda_viajes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_reporte_actividades`
--

LOCK TABLES `t_sgcv_reporte_actividades` WRITE;
/*!40000 ALTER TABLE `t_sgcv_reporte_actividades` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_reporte_actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_reu_document`
--

DROP TABLE IF EXISTS `t_sgcv_reu_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_reu_document` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) NOT NULL,
  `reu_tema_id` bigint(20) NOT NULL,
  `documento_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reunion_areas_areas1_idx` (`area_id`),
  KEY `fk_reunion_document_documents1_idx` (`documento_id`),
  KEY `fk_reu_document_reu_temas1_idx` (`reu_tema_id`),
  CONSTRAINT `fk_reu_document_reu_temas1` FOREIGN KEY (`reu_tema_id`) REFERENCES `t_sgcv_reu_temas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reunion_areas_areas1` FOREIGN KEY (`area_id`) REFERENCES `t_sgcv_areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reunion_document_documents1` FOREIGN KEY (`documento_id`) REFERENCES `t_sgcv_documentos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_reu_document`
--

LOCK TABLES `t_sgcv_reu_document` WRITE;
/*!40000 ALTER TABLE `t_sgcv_reu_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_reu_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_reu_presentadores`
--

DROP TABLE IF EXISTS `t_sgcv_reu_presentadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_reu_presentadores` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) NOT NULL,
  `reunion_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reunion_presenters_users1_idx` (`usuario_id`),
  KEY `fk_reunion_presenters_reunions1_idx` (`reunion_id`),
  CONSTRAINT `fk_reunion_presenters_reunions1` FOREIGN KEY (`reunion_id`) REFERENCES `t_sgcv_reuniones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reunion_presenters_users1` FOREIGN KEY (`usuario_id`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_reu_presentadores`
--

LOCK TABLES `t_sgcv_reu_presentadores` WRITE;
/*!40000 ALTER TABLE `t_sgcv_reu_presentadores` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_reu_presentadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_reu_temas`
--

DROP TABLE IF EXISTS `t_sgcv_reu_temas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_reu_temas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `reunion_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reunion_themes_reunions1_idx` (`reunion_id`),
  CONSTRAINT `fk_reunion_themes_reunions1` FOREIGN KEY (`reunion_id`) REFERENCES `t_sgcv_reuniones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_reu_temas`
--

LOCK TABLES `t_sgcv_reu_temas` WRITE;
/*!40000 ALTER TABLE `t_sgcv_reu_temas` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_reu_temas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_reuniones`
--

DROP TABLE IF EXISTS `t_sgcv_reuniones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_reuniones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_reuniones`
--

LOCK TABLES `t_sgcv_reuniones` WRITE;
/*!40000 ALTER TABLE `t_sgcv_reuniones` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_reuniones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_roles`
--

DROP TABLE IF EXISTS `t_sgcv_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_T_SGCV_Roles_T_SGCV_Areas1_idx` (`area_id`),
  CONSTRAINT `fk_T_SGCV_Roles_T_SGCV_Areas1` FOREIGN KEY (`area_id`) REFERENCES `t_sgcv_areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_roles`
--

LOCK TABLES `t_sgcv_roles` WRITE;
/*!40000 ALTER TABLE `t_sgcv_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_sedes`
--

DROP TABLE IF EXISTS `t_sgcv_sedes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_sedes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_sedes`
--

LOCK TABLES `t_sgcv_sedes` WRITE;
/*!40000 ALTER TABLE `t_sgcv_sedes` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_sedes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_temas`
--

DROP TABLE IF EXISTS `t_sgcv_temas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_temas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rol_id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_themes_roles1_idx` (`rol_id`),
  CONSTRAINT `fk_themes_roles1` FOREIGN KEY (`rol_id`) REFERENCES `t_sgcv_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_temas`
--

LOCK TABLES `t_sgcv_temas` WRITE;
/*!40000 ALTER TABLE `t_sgcv_temas` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_sgcv_temas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_usuario_perfil`
--

DROP TABLE IF EXISTS `t_sgcv_usuario_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_usuario_perfil` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perfil_id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_T_SGCV_Usuario_Perfil_T_SGCV_Perfil1_idx` (`perfil_id`),
  KEY `fk_T_SGCV_Usuario_Perfil_T_SGCV_Usuarios1_idx` (`usuario_id`),
  CONSTRAINT `fk_T_SGCV_Usuario_Perfil_T_SGCV_Perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `t_sgcv_perfiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_T_SGCV_Usuario_Perfil_T_SGCV_Usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `t_sgcv_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_usuario_perfil`
--

LOCK TABLES `t_sgcv_usuario_perfil` WRITE;
/*!40000 ALTER TABLE `t_sgcv_usuario_perfil` DISABLE KEYS */;
INSERT INTO `t_sgcv_usuario_perfil` VALUES (1,1,1,1,'2022-05-25 12:00:00','2022-05-25 12:00:00'),(2,2,2,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(3,3,3,1,'2022-05-23 12:00:00','2022-05-23 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_usuario_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_sgcv_usuarios`
--

DROP TABLE IF EXISTS `t_sgcv_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sgcv_usuarios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(80) NOT NULL,
  `posicion_id` int(11) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(150) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_position1_idx` (`posicion_id`),
  CONSTRAINT `fk_users_position1` FOREIGN KEY (`posicion_id`) REFERENCES `t_sgcv_posiciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sgcv_usuarios`
--

LOCK TABLES `t_sgcv_usuarios` WRITE;
/*!40000 ALTER TABLE `t_sgcv_usuarios` DISABLE KEYS */;
INSERT INTO `t_sgcv_usuarios` VALUES (1,'Admin','admin@admin.com','$2a$12$pbR.YNvu7iEbww4afv1LCO6iKofycxu3/RIJXJfqEU1YO1nQdju7q',1,NULL,NULL,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(2,'Alejandro','alejandro@mail.com','$2a$12$Vt9JHANe0HFVOe7j4KhQxOnJ1/injllG6RbVy2DDscDbGXvbdp1fm',2,NULL,NULL,1,'2022-05-23 12:00:00','2022-05-23 12:00:00'),(3,'Gerente','gerencia@mail.com','$2a$12$OTG7JVaMkw4n08hd4a5ybuCkcjTil/EoDosrAjNZQ1ksFRGbAMZ9y',3,NULL,NULL,1,'2022-05-23 12:00:00','2022-05-23 12:00:00');
/*!40000 ALTER TABLE `t_sgcv_usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-14 23:14:10
