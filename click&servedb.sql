CREATE DATABASE  IF NOT EXISTS `pedidos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `pedidos`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: pedidos
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Desayunos','Lo mejor de la Casa','Desayunos.html'),(2,'Platos Principales','El sabor de nuestro puerto','PlatosPrincipales.html'),(3,'Antojos','Lo mejor de la Casa','Antojos.html'),(4,'Entradas','Lo mejor de la Casa','Entradas.html'),(5,'Batidos','Lo mejor de la Casa','Batidos.html'),(6,'Postres','Lo mejor de la Casa','Postres.html'),(7,'Promociones','Ofertas especiales del día','index.php');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturas`
--

DROP TABLE IF EXISTS `facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_factura` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas`
--

LOCK TABLES `facturas` WRITE;
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredientes`
--

DROP TABLE IF EXISTS `ingredientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredientes`
--

LOCK TABLES `ingredientes` WRITE;
/*!40000 ALTER TABLE `ingredientes` DISABLE KEYS */;
INSERT INTO `ingredientes` VALUES (1,'Tomate'),(2,'Cebolla'),(3,'Queso'),(4,'Huevos'),(5,'Pan'),(6,'Tocino'),(7,'Plátanos'),(8,'Frijoles'),(9,'Tortillas'),(10,'Crema');
/*!40000 ALTER TABLE `ingredientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mesa` varchar(50) NOT NULL,
  `pedido` text NOT NULL,
  `detalle` text DEFAULT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `items_json` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estado` (`estado`),
  KEY `mesa` (`mesa`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,'8','- Pizza de Birria Personal/Grande x2\n','','completado',98.00,'2025-05-02 05:04:56','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":2,\"ingredientes_removidos\":[]}]'),(2,'8','- Pizza de Birria Personal/Grande x1\n','','completado',49.00,'2025-05-02 05:06:47','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(3,'8','- Pizza de Birria Personal/Grande x1\n','','completado',49.00,'2025-05-02 05:07:06','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(4,'8','- Pizza de Birria Personal/Grande x1\n','','completado',49.00,'2025-05-02 05:17:12','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(5,'8','- Pizza de Birria Personal/Grande x1\n','','completado',49.00,'2025-05-02 05:22:05','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(6,'8','- Pizza de Birria Personal/Grande x2\n','','completado',98.00,'2025-05-02 05:24:04','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":2,\"ingredientes_removidos\":[]}]'),(7,'8','- Pizza de Birria Personal/Grande x1\n','','completado',49.00,'2025-05-02 05:35:07','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(8,'2','- Tacos de Birria x1\n','','completado',19.00,'2025-05-02 05:54:55','[{\"nombre\":\"Tacos de Birria\",\"descripcion\":\"Lo mejor de la Casa. Tortilla de ma\\u00edz, birria, queso, cilantro y cebolla.\",\"precio\":19,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(9,'2','- Hamburguesa de Pollo x1\n  Sin: Lechuga, Tomate\n- Desayuno Infantil x1\n  Sin: Tomate, Cebolla\n- Desayuno Normal x1\n','','completado',94.00,'2025-05-02 06:26:42','[{\"nombre\":\"Hamburguesa de Pollo\",\"descripcion\":\"Pechuga de pollo empanizada, lechuga, tomate y mayonesa especial.\",\"precio\":29,\"cantidad\":1,\"ingredientes_removidos\":[\"Lechuga\",\"Tomate\"]},{\"nombre\":\"Desayuno Infantil\",\"descripcion\":\"esto es una descripci\\u00f3n del producto me da pereza pensar en una\",\"precio\":35,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(10,'4','- Desayuno Normal x1\n- Desayuno Americano x1\n','','completado',75.00,'2025-05-02 06:29:12','[{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]},{\"nombre\":\"Desayuno Americano\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(11,'4','- Desayuno Tradicional x1\n','','completado',45.00,'2025-05-02 06:29:52','[{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(12,'10','- Pizza de Birria Personal/Grande x1\n- Hamburguesa de Pollo x1\n  Sin: Mayonesa\n- Desayuno Tradicional x1\n  Sin: Tomate, Cebolla\n- Desayuno Infantil x1\n','desayuno infantil extraqueso','completado',158.00,'2025-05-02 06:35:21','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]},{\"nombre\":\"Hamburguesa de Pollo\",\"descripcion\":\"Pechuga de pollo empanizada, lechuga, tomate y mayonesa especial.\",\"precio\":29,\"cantidad\":1,\"ingredientes_removidos\":[\"Mayonesa\"]},{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Infantil\",\"descripcion\":\"esto es una descripci\\u00f3n del producto me da pereza pensar en una\",\"precio\":35,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(13,'10','- superplato x1\n  Sin: Tortillas, Crema\n','','completado',100.00,'2025-05-02 06:38:03','[{\"nombre\":\"superplato\",\"descripcion\":\"unadescripcion\",\"precio\":100,\"cantidad\":1,\"ingredientes_removidos\":[\"Tortillas\",\"Crema\"]}]'),(14,'2','- Desayuno Tradicional x1\n  Sin: Tomate, Cebolla\n- Desayuno Normal x1\n','','completado',75.00,'2025-05-03 07:08:13','[{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(15,'2','- algonuevo x1\n','','completado',100.00,'2025-05-03 07:10:23','[{\"nombre\":\"algonuevo\",\"descripcion\":\"fgdfhgdsf\",\"precio\":100,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(16,'2','- Desayuno Chapín x1\n','','pendiente',44.00,'2025-05-07 03:28:39','[{\"nombre\":\"Desayuno Chap\\u00edn\",\"descripcion\":\"desayuno t\\u00edpico guatemalteco \",\"precio\":44,\"cantidad\":1,\"ingredientes_removidos\":[]}]');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_ingredientes`
--

DROP TABLE IF EXISTS `producto_ingredientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_ingredientes` (
  `producto_id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  PRIMARY KEY (`producto_id`,`ingrediente_id`),
  KEY `ingrediente_id` (`ingrediente_id`),
  CONSTRAINT `producto_ingredientes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_ingredientes_ibfk_2` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_ingredientes`
--

LOCK TABLES `producto_ingredientes` WRITE;
/*!40000 ALTER TABLE `producto_ingredientes` DISABLE KEYS */;
INSERT INTO `producto_ingredientes` VALUES (10,1),(10,2),(10,3),(13,1),(13,2),(13,3),(14,1),(14,2),(14,3),(15,1),(15,2),(15,3),(16,1),(16,2),(16,3),(20,8),(20,9),(20,10);
/*!40000 ALTER TABLE `producto_ingredientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (10,1,'Desayuno Chapín','desayuno típico guatemalteco ',50.00,'https://aprende.guatemala.com/wp-content/uploads/2016/10/C%C3%B3mo-preparar-un-desayuno-t%C3%ADpico-chap%C3%ADn2.jpg'),(13,1,'Desayuno Tradicional','Disfruta de la perfecta combinación de huevos frescos preparados a tu gusto, acompañados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosión de sabores que revitalizan tus mañanas.',45.00,'https://mcdonalds.com.gt/storage/menu-products/1640713435_19_DesayunoTradicional_1624550705.png'),(14,1,'Desayuno Infantil','esto es una descripción del producto me da pereza pensar en una',35.00,'https://tofuu.getjusto.com/orioneat-local/resized2/ZhE4PM2B8hDma6Wtm-2400-x.webp'),(15,1,'Desayuno Americano','esto es una descripcion del producto me da pereza pensar en una',45.00,'https://cdn.recetasderechupete.com/wp-content/uploads/2024/10/Desayuno-americano-Paso-15.jpg'),(16,1,'Desayuno Normal','esto es una descripcion del producto me da pereza pensar en una',30.00,'https://tiptop.com.pe/wp-content/uploads/2023/06/Desayuno-Americano-1-scaled.jpg'),(20,2,'superplato','unadescripcion',100.00,'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9zXJNyANS8TG8NHjHX4hUdaU_Q6VQSKaMQg&s'),(22,5,'licuado','algo yoquese',30.00,'https://myplate-prod.azureedge.us/sites/default/files/styles/recipe_525_x_350_/public/2020-10/FruitMilkShakes_527x323.jpg?itok=90Emaamu');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registropedidos`
--

DROP TABLE IF EXISTS `registropedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registropedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `mesa` varchar(50) NOT NULL,
  `pedido` text NOT NULL,
  `detalle` text DEFAULT NULL,
  `estado` varchar(50) NOT NULL COMMENT 'Estado en este registro',
  `total` decimal(10,2) NOT NULL,
  `fecha_hora_pedido` datetime NOT NULL COMMENT 'Cuando se hizo el pedido',
  `fecha_hora_registro` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Cuando se registró',
  `items_json` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `mesa` (`mesa`),
  KEY `fecha_hora_pedido` (`fecha_hora_pedido`),
  KEY `estado` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registropedidos`
--

LOCK TABLES `registropedidos` WRITE;
/*!40000 ALTER TABLE `registropedidos` DISABLE KEYS */;
INSERT INTO `registropedidos` VALUES (1,1,'8','- Pizza de Birria Personal/Grande x2\n','','pendiente',98.00,'2025-05-02 05:04:56','2025-05-01 21:05:00','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":2,\"ingredientes_removidos\":[]}]'),(2,2,'8','- Pizza de Birria Personal/Grande x1\n','','pendiente',49.00,'2025-05-02 05:06:47','2025-05-01 21:06:51','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(3,3,'8','- Pizza de Birria Personal/Grande x1\n','','pendiente',49.00,'2025-05-02 05:07:06','2025-05-01 21:16:57','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(4,4,'8','- Pizza de Birria Personal/Grande x1\n','','pendiente',49.00,'2025-05-02 05:17:12','2025-05-01 21:17:16','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(5,5,'8','- Pizza de Birria Personal/Grande x1\n','','pendiente',49.00,'2025-05-02 05:22:05','2025-05-01 21:22:09','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(6,6,'8','- Pizza de Birria Personal/Grande x2\n','','pendiente',98.00,'2025-05-02 05:24:04','2025-05-01 21:24:08','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":2,\"ingredientes_removidos\":[]}]'),(7,7,'8','- Pizza de Birria Personal/Grande x1\n','','pendiente',49.00,'2025-05-02 05:35:07','2025-05-01 21:35:12','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(8,8,'2','- Tacos de Birria x1\n','','pendiente',19.00,'2025-05-02 05:54:55','2025-05-01 21:55:05','[{\"nombre\":\"Tacos de Birria\",\"descripcion\":\"Lo mejor de la Casa. Tortilla de ma\\u00edz, birria, queso, cilantro y cebolla.\",\"precio\":19,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(9,9,'2','- Hamburguesa de Pollo x1\n  Sin: Lechuga, Tomate\n- Desayuno Infantil x1\n  Sin: Tomate, Cebolla\n- Desayuno Normal x1\n','','pendiente',94.00,'2025-05-02 06:26:42','2025-05-01 22:29:18','[{\"nombre\":\"Hamburguesa de Pollo\",\"descripcion\":\"Pechuga de pollo empanizada, lechuga, tomate y mayonesa especial.\",\"precio\":29,\"cantidad\":1,\"ingredientes_removidos\":[\"Lechuga\",\"Tomate\"]},{\"nombre\":\"Desayuno Infantil\",\"descripcion\":\"esto es una descripci\\u00f3n del producto me da pereza pensar en una\",\"precio\":35,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(10,10,'4','- Desayuno Normal x1\n- Desayuno Americano x1\n','','pendiente',75.00,'2025-05-02 06:29:12','2025-05-01 22:29:19','[{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]},{\"nombre\":\"Desayuno Americano\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(11,11,'4','- Desayuno Tradicional x1\n','','pendiente',45.00,'2025-05-02 06:29:52','2025-05-01 22:30:01','[{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(12,12,'10','- Pizza de Birria Personal/Grande x1\n- Hamburguesa de Pollo x1\n  Sin: Mayonesa\n- Desayuno Tradicional x1\n  Sin: Tomate, Cebolla\n- Desayuno Infantil x1\n','desayuno infantil extraqueso','pendiente',158.00,'2025-05-02 06:35:21','2025-05-01 22:35:46','[{\"nombre\":\"Pizza de Birria Personal\\/Grande\",\"descripcion\":\"El sabor de nuestro puerto. Masa artesanal, birria, queso mozzarella, cilantro y cebolla.\",\"precio\":49,\"cantidad\":1,\"ingredientes_removidos\":[]},{\"nombre\":\"Hamburguesa de Pollo\",\"descripcion\":\"Pechuga de pollo empanizada, lechuga, tomate y mayonesa especial.\",\"precio\":29,\"cantidad\":1,\"ingredientes_removidos\":[\"Mayonesa\"]},{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Infantil\",\"descripcion\":\"esto es una descripci\\u00f3n del producto me da pereza pensar en una\",\"precio\":35,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(13,13,'10','- superplato x1\n  Sin: Tortillas, Crema\n','','pendiente',100.00,'2025-05-02 06:38:03','2025-05-01 22:38:09','[{\"nombre\":\"superplato\",\"descripcion\":\"unadescripcion\",\"precio\":100,\"cantidad\":1,\"ingredientes_removidos\":[\"Tortillas\",\"Crema\"]}]'),(14,14,'2','- Desayuno Tradicional x1\n  Sin: Tomate, Cebolla\n- Desayuno Normal x1\n','','pendiente',75.00,'2025-05-03 07:08:13','2025-05-02 23:09:25','[{\"nombre\":\"Desayuno Tradicional\",\"descripcion\":\"Disfruta de la perfecta combinaci\\u00f3n de huevos frescos preparados a tu gusto, acompa\\u00f1ados de crujiente tocino dorado, salchichas artesanales y pan casero tostado. Servido con frijoles caseros y rodajas de tomate asado. Una explosi\\u00f3n de sabores que revitalizan tus ma\\u00f1anas.\",\"precio\":45,\"cantidad\":1,\"ingredientes_removidos\":[\"Tomate\",\"Cebolla\"]},{\"nombre\":\"Desayuno Normal\",\"descripcion\":\"esto es una descripcion del producto me da pereza pensar en una\",\"precio\":30,\"cantidad\":1,\"ingredientes_removidos\":[]}]'),(15,15,'2','- algonuevo x1\n','','pendiente',100.00,'2025-05-03 07:10:23','2025-05-02 23:10:26','[{\"nombre\":\"algonuevo\",\"descripcion\":\"fgdfhgdsf\",\"precio\":100,\"cantidad\":1,\"ingredientes_removidos\":[]}]');
/*!40000 ALTER TABLE `registropedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cocinero','cajero','cliente') NOT NULL DEFAULT 'cliente',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','$2y$10$mwFbo119zO5H/HA84J4Kl.ByJxC4LrSfW39J/uc4GEujH6gB04WNm','admin','2025-04-28 21:50:54','activo'),(2,'yozef','$2y$10$jmrtfVdgDhUe8kR7a5JnKeRsUn1hQlDuu7TlsRVpKRYiXqF0X/Ega','admin','2025-04-29 00:55:34','activo'),(3,'cocinero1','$2y$10$dbDCgsa8RNsIXPz9fRnw4uEOu163YWyruscHdBHUBDZDyFuwG5X6m','cocinero','2025-04-29 02:56:47','activo'),(4,'hola','$2y$10$FELygbLYYYZb7wQqh53/hOK1W3qkxHqAW6uDOVG0EQ4pC8Z07584u','cliente','2025-04-29 03:09:57','activo'),(5,'cocinero2','$2y$10$u0s3pKqLN1mpliz4AzJpFuAgD9fN5RMpE7/f9pqqYRsE0d3sljra.','cocinero','2025-04-29 03:13:34','activo'),(9,'hola222222','$2y$10$tKGLyttZEj.NAU/FFes9IOdHGDdBoxqep18fdmxZn8DAI69i3uHCi','cliente','2025-04-29 03:25:24','activo'),(10,'webaaa','$2y$10$TmmQ36s2bJJYaCCdVV2gD.aD5wR3aDmTrcM5A6bpcwj.a30EYxxEO','cliente','2025-04-29 03:30:52','activo'),(11,'holaolaass','$2y$10$uNXvHwliGSdQAfPsA7L/A.eWN8m1ewN97a.Izgunv8NfPTmvyqbue','admin','2025-04-29 03:47:07','activo'),(12,'yasushi','$2y$10$pDSeI52hsh45UGn5Q5mmmO5QQU4ebFLpDX0jbMp5yk8ILbjR.z6va','cocinero','2025-04-29 03:49:54','activo'),(13,'thedark','$2y$10$exrvNOIKkD5u3lS.GU1BKOJzZmH9CSpVJLcYsdOHKH3WGaXdvV//G','cocinero','2025-04-29 03:52:44','activo'),(14,'asor','$2y$10$6RVgH7pWPFJHH2tNqP5F9e1kFk1jX3LaJqBycfP7v0RdtCCVvcKBe','cocinero','2025-04-29 04:36:00','activo'),(15,'cayetano','$2y$10$isFRskRS8JyMBa9.w7Gnbehaz71H.xgB6/u/lZUxqup4XssVypL5.','cocinero','2025-04-29 04:41:48','activo'),(16,'alejandro','$2y$10$JT0gzkU/Oi0MYjwBfYpO7.roQ7MkywJPbJXj1ZIVhM3JpugLHO8BW','admin','2025-04-29 04:43:02','activo'),(18,'leonidas','$2y$10$MNP50gFMqH66reB/DZWVFe0mhNvzA6ZZ52FImWrjBqFl2pHcvfLPy','cliente','2025-04-29 22:50:17','activo'),(19,'mellamo','$2y$10$euF2N861cvlVBMV74ERYCu7kAKE3cpk2xCigB9IfCA7Twk84EZrpG','cliente','2025-05-01 07:02:40','activo'),(20,'Klelim Sagastume','$2y$10$cjcvl1ZIYtwoT9iyRAySPuEaYDsmlObbSiGz5us14KfEh/5G.72cy','cocinero','2025-05-01 22:09:33','activo'),(21,'cuenta','$2y$10$ZmFMZV9ObIWl9xJAPa4rS.eIefewwCSasFDVQC.khu9vHogeFRn0e','cliente','2025-05-02 04:25:33','activo'),(22,'cocinero12','$2y$10$Q4yz1U2De9NWtbbS7DtgF.ZL6DisIP.5dq72yx680XUxmNYixnQNG','cocinero','2025-05-02 04:32:10','activo'),(23,'macu','$2y$10$1UaKUvrY3ngGcvnKpcmjROmIp8HFg565RYPxHzdqjD6DTNGObKQKe','cliente','2025-05-02 04:33:18','activo'),(24,'jose cayetano','$2y$10$vfC7nzyn9NUXpCijq/7dxOzkxSR/WVIOIBmoWoBrnFIbRPtXYizGu','cliente','2025-05-07 21:57:50','activo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-07 23:24:43
