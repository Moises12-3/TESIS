-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ventas_php
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
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `descuento` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Juan P├®rez','1234567890123','123-456-7890','Calle Ficticia 123, Managua',10.00),(2,'Maria Garc├¡a','9876543210987','321-654-0987','Avenida Central 456, Le├│n',15.00),(3,'Luis S├ínchez','4567891234567','555-555-5555','Calle Real 789, Chinandega',5.00),(4,'Carlos Pineda','2135468790321','888-888-8888','Callej├│n del Sol 202, Masaya',8.50),(5,'Oscar Sol├¡s','8765432109876','999-999-9999','Zona 1, Carretera Norte, Managua',20.00),(6,'Laura Mart├¡nez','4321098765432','111-222-3333','Calle Madero 303, Granada',7.50),(7,'Ricardo Garc├¡a','3216549876543','444-444-4444','Calle Las Palmas 404, Rivas',10.00),(8,'Nina L├│pez','7654321098765','555-666-7777','Avenida Jos├® Dolores 505, Boaco',18.00),(9,'Jos├® Ram├¡rez','1237894561237','666-777-8888','Calle Real 606, Jinotega',6.00),(10,'Ana P├®rez','7894561237894','777-888-9999','Avenida Nueva 707, Ocotal',10.50),(11,'Beatriz Valle','8901234567890','999-000-1111','Calle San Juan 808, Carazo',5.00),(12,'Felipe Hern├índez','2345678901234','222-333-4444','Avenida Norte 909, Chontales',12.00),(13,'Ver├│nica Moncada','5678901234567','333-444-5555','Calle Independencia 1010, Masaya',10.00),(14,'Carlos Morales','8765432102345','444-555-6666','Calle Los Pinos 1111, Le├│n',15.00),(15,'Pablo Moreno','2345678909876','555-666-7777','Avenida del Sol 1212, Estel├¡',7.00),(16,'Julio Vargas','5432109876543','666-777-8888','Calle Las Rosas 1313, Managua',9.00),(17,'Nayeli Morales','6543210987654','777-888-9999','Avenida Central 1414, Chinandega',6.00),(18,'Marcelo L├│pez','3216549876543','555-444-3333','Calle Santa Teresa 1515, Boaco',8.00),(19,'Marta Flores','7894561236547','444-555-6666','Avenida Los Robles 1616, Jinotega',11.00),(20,'Samuel Rojas','8901234567891','999-888-7777','Calle Los Alpes 1717, Carazo',10.00),(21,'Antonio L├│pez','4561237894562','222-333-4445','Avenida San Juan 1818, Le├│n',7.00),(22,'Pedro P├®rez','6541239876543','333-444-5556','Calle Sol 1919, Estel├¡',5.00),(23,'Patricia G├│mez','5678904321234','444-555-6667','Avenida La Paz 2020, Jinotega',6.00);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convertidor_medidas`
--

DROP TABLE IF EXISTS `convertidor_medidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `convertidor_medidas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_medida` varchar(50) NOT NULL,
  `unidad_origen` varchar(50) NOT NULL,
  `unidad_destino` varchar(50) NOT NULL,
  `factor_conversion` decimal(15,10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convertidor_medidas`
--

LOCK TABLES `convertidor_medidas` WRITE;
/*!40000 ALTER TABLE `convertidor_medidas` DISABLE KEYS */;
INSERT INTO `convertidor_medidas` VALUES (1,'Longitud','Metro','Cent├¡metro',100.0000000000),(2,'Longitud','Metro','Mil├¡metro',1000.0000000000),(3,'Longitud','Kil├│metro','Metro',1000.0000000000),(4,'Longitud','Pulgada','Cent├¡metro',2.5400000000),(5,'Longitud','Pie','Metro',0.3048000000),(6,'Longitud','Yarda','Metro',0.9144000000),(7,'Longitud','Milla','Kil├│metro',1.6093400000),(8,'Peso','Kilogramo','Gramo',1000.0000000000),(9,'Peso','Kilogramo','Libra',2.2046200000),(10,'Peso','Gramo','Miligramo',1000.0000000000),(11,'Peso','Libra','Onza',16.0000000000),(12,'Peso','Tonelada','Kilogramo',1000.0000000000),(13,'Volumen','Litro','Mililitro',1000.0000000000),(14,'Volumen','Metro c├║bico','Litro',1000.0000000000),(15,'Volumen','Gal├│n','Litro',3.7854100000),(16,'Volumen','Pinta','Litro',0.4731760000),(17,'Temperatura','Celsius','Fahrenheit',1.8000000000),(18,'Temperatura','Fahrenheit','Celsius',0.5556000000),(19,'Temperatura','Celsius','Kelvin',1.0000000000),(20,'Temperatura','Kelvin','Celsius',1.0000000000);
/*!40000 ALTER TABLE `convertidor_medidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `codigo_interno` varchar(100) DEFAULT NULL,
  `identidad_juridica` varchar(100) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_interno` (`codigo_interno`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES (1,'UNIVERSIDAD','Universidad Nacional Comandante Padre Gaspar Garcia Laviana','maaroncarrasco@gmail.com','88090180','3232','EMP_68d4dcef4f446','32432','images/logo_empresa/UNIVERSIDAD_68d4dd13678f5.png','2025-09-25 12:10:55');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impuesto`
--

DROP TABLE IF EXISTS `impuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_impuesto` enum('Porcentaje','Fijo') DEFAULT 'Porcentaje',
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impuesto`
--

LOCK TABLES `impuesto` WRITE;
/*!40000 ALTER TABLE `impuesto` DISABLE KEYS */;
INSERT INTO `impuesto` VALUES (1,'IVA',15.00,'Impuesto al Valor Agregado','Porcentaje','Activo','2025-10-06 00:27:27'),(2,'ISV',12.00,'Impuesto sobre Ventas','Porcentaje','Activo','2025-10-06 00:27:27'),(3,'Impuesto Selectivo al Consumo',10.00,'Aplica a productos espec├¡ficos como alcohol y tabaco','Porcentaje','Activo','2025-10-06 00:27:27'),(4,'Impuesto Municipal',2.00,'Tributo aplicado por las alcald├¡as','Porcentaje','Activo','2025-10-06 00:27:27'),(5,'Impuesto Ecol├│gico',1.50,'Aplicado a productos que afectan el medio ambiente','Porcentaje','Activo','2025-10-06 00:27:27'),(6,'Impuesto Turismo',5.00,'Gravamen aplicado a actividades tur├¡sticas','Porcentaje','Activo','2025-10-06 00:27:27'),(7,'Impuesto Fijo de Timbres',50.00,'Pago ├║nico fijo por documentos legales','Fijo','Activo','2025-10-06 00:27:27'),(8,'Impuesto Fijo de Transporte',100.00,'Tasa fija anual por transporte comercial','Fijo','Activo','2025-10-06 00:27:27'),(9,'Impuesto Digital',7.00,'Aplicado a servicios digitales','Porcentaje','Activo','2025-10-06 00:27:27'),(10,'Impuesto de Exportaci├│n',3.00,'Gravamen sobre productos exportados','Porcentaje','Activo','2025-10-06 00:27:27'),(11,'Impuesto de Importaci├│n',8.00,'Gravamen sobre productos importados','Porcentaje','Activo','2025-10-06 00:27:27'),(12,'Impuesto a Juegos de Azar',20.00,'Aplicado a loter├¡as, casinos, etc.','Porcentaje','Activo','2025-10-06 00:27:27'),(13,'Impuesto de Solidaridad',1.00,'Impuesto adicional para fines sociales','Porcentaje','Activo','2025-10-06 00:27:27');
/*!40000 ALTER TABLE `impuesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moneda`
--

DROP TABLE IF EXISTS `moneda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `simbolo` varchar(10) NOT NULL,
  `tipo` enum('nacional','extranjera') NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `valor` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneda`
--

LOCK TABLES `moneda` WRITE;
/*!40000 ALTER TABLE `moneda` DISABLE KEYS */;
INSERT INTO `moneda` VALUES (1,'Colon','CRC','nacional','Costa Rica','activo',600.00,'2025-10-06 00:27:26'),(2,'C├│rdoba','NIO','extranjera','Nicaragua','activo',35.00,'2025-10-06 00:27:26'),(3,'D├│lar Estadounidense','USD','extranjera','Estados Unidos','activo',1.00,'2025-10-06 00:27:26'),(4,'Peso Mexicano','MXN','extranjera','M├®xico','activo',18.00,'2025-10-06 00:27:26'),(5,'D├│lar Canadiense','CAD','extranjera','Canad├í','activo',1.35,'2025-10-06 00:27:26'),(6,'Quetzal','GTQ','extranjera','Guatemala','activo',7.80,'2025-10-06 00:27:26'),(7,'Sol','PEN','extranjera','Per├║','activo',3.80,'2025-10-06 00:27:26'),(8,'Peso Colombiano','COP','extranjera','Colombia','activo',4000.00,'2025-10-06 00:27:26'),(9,'Bol├¡var','VES','extranjera','Venezuela','activo',4000000.00,'2025-10-06 00:27:26'),(10,'Peso Argentino','ARS','extranjera','Argentina','activo',250.00,'2025-10-06 00:27:26'),(11,'Guaran├¡','PYG','extranjera','Paraguay','activo',7000.00,'2025-10-06 00:27:26'),(12,'Peso Chileno','CLP','extranjera','Chile','activo',850.00,'2025-10-06 00:27:26'),(13,'D├│lar de Belice','BZD','extranjera','Belice','activo',2.00,'2025-10-06 00:27:26'),(14,'Peso Cubano','CUP','extranjera','Cuba','activo',24.00,'2025-10-06 00:27:26'),(15,'D├│lar Jamaiquino','JMD','extranjera','Jamaica','activo',150.00,'2025-10-06 00:27:26'),(16,'Peso Dominicano','DOP','extranjera','Rep├║blica Dominicana','activo',55.00,'2025-10-06 00:27:26'),(17,'Boliviano','BOB','extranjera','Bolivia','activo',7.00,'2025-10-06 00:27:26'),(18,'D├│lar Ecuatoriano','USD','extranjera','Ecuador','activo',1.00,'2025-10-06 00:27:26'),(19,'Peso Uruguayo','UYU','extranjera','Uruguay','activo',45.00,'2025-10-06 00:27:26'),(20,'Peso Colombiano','COP','extranjera','Colombia','activo',4000.00,'2025-10-06 00:27:26'),(21,'S├║cre','SUC','extranjera','Ecuador','inactivo',NULL,'2025-10-06 00:27:26'),(22,'D├│lar Bermude├▒o','BMD','extranjera','Bermuda','activo',1.00,'2025-10-06 00:27:26'),(23,'Caym├ín D├│lar','KYD','extranjera','Islas Caim├ín','activo',0.83,'2025-10-06 00:27:26'),(24,'Balboa','PAB','extranjera','Panam├í','activo',1.00,'2025-10-06 00:27:26'),(25,'C├│rdoba Oro','NIO','extranjera','Nicaragua','activo',35.00,'2025-10-06 00:27:26'),(26,'D├│lar de Trinidad y Tobago','TTD','extranjera','Trinidad y Tobago','activo',6.80,'2025-10-06 00:27:26'),(27,'Colon','CRC','extranjera','El Salvador','activo',8.75,'2025-10-06 00:27:26'),(28,'D├│lar de Guyana','GYD','extranjera','Guyana','activo',210.00,'2025-10-06 00:27:26'),(29,'D├│lar de Surinam','SRD','extranjera','Surinam','activo',21.00,'2025-10-06 00:27:26'),(30,'Peso Paname├▒o','PAB','extranjera','Panam├í','activo',1.00,'2025-10-06 00:27:26');
/*!40000 ALTER TABLE `moneda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `compra` decimal(8,2) NOT NULL,
  `venta` decimal(8,2) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `iva` decimal(8,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `idMoneda` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'P001','Leche Entera',0.80,1.20,'2025-06-30',0.15,150,1),(2,'P002','Arroz Blanco',0.50,0.75,NULL,0.15,200,1),(3,'P003','Caf├® Molido',2.50,3.50,'2026-01-15',0.15,80,1),(4,'P004','Aceite Vegetal',1.20,1.80,'2025-12-10',0.15,90,1),(5,'P005','Az├║car Refinada',0.45,0.70,NULL,0.15,120,1),(6,'P006','Harina de Trigo',0.60,0.90,NULL,0.15,130,1),(7,'P007','Sal de Mesa',0.20,0.40,NULL,0.15,250,1),(8,'P008','Jab├│n de Ba├▒o',0.70,1.00,NULL,0.15,100,1),(9,'P009','Pasta Dental',1.00,1.50,NULL,0.15,95,1),(10,'P010','Shampoo 400ml',2.20,3.00,NULL,0.15,70,1),(11,'P011','Papel Higi├®nico 4pzs',1.50,2.30,NULL,0.15,110,1),(12,'P012','Desodorante',1.80,2.50,NULL,0.15,85,1),(13,'P013','Galletas Dulces',0.90,1.30,'2025-09-20',0.15,140,1),(14,'P014','Refresco en Polvo',0.30,0.60,'2025-10-05',0.15,160,1),(15,'P015','Agua Embotellada 600ml',0.40,0.70,NULL,0.15,200,1),(16,'P016','Cerveza Lata',0.80,1.50,'2025-11-01',0.15,90,1),(17,'P017','Yogur Natural',1.00,1.60,'2025-08-30',0.15,60,1),(18,'P018','Pan Blanco',0.50,0.90,'2025-05-01',0.15,75,1),(19,'P019','Salsa de Tomate',0.70,1.10,'2025-12-25',0.15,100,1),(20,'P020','Mayonesa 250g',0.90,1.40,'2025-10-15',0.15,85,1),(21,'P021','Mantequilla 200g',1.10,1.80,'2025-07-10',0.15,60,1),(22,'P022','Frijoles Rojos',0.55,0.85,NULL,0.15,180,1),(23,'P023','At├║n en Lata',1.20,1.90,'2027-02-15',0.15,75,1);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_ventas`
--

DROP TABLE IF EXISTS `productos_ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos_ventas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `numeroFactura` varchar(20) DEFAULT NULL,
  `idProducto` bigint(20) NOT NULL,
  `idVenta` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_ventas`
--

LOCK TABLES `productos_ventas` WRITE;
/*!40000 ALTER TABLE `productos_ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos_ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipopago`
--

DROP TABLE IF EXISTS `tipopago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipopago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('Efectivo','Contado') DEFAULT 'Efectivo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipopago`
--

LOCK TABLES `tipopago` WRITE;
/*!40000 ALTER TABLE `tipopago` DISABLE KEYS */;
INSERT INTO `tipopago` VALUES (1,'Efectivo','Pago realizado directamente con dinero f├¡sico','Efectivo','2025-10-06 00:27:27'),(2,'Tarjeta de D├®bito','Pago realizado con tarjeta de d├®bito bancaria','Contado','2025-10-06 00:27:27'),(3,'Tarjeta de Cr├®dito','Pago con tarjeta de cr├®dito','Contado','2025-10-06 00:27:27'),(4,'Transferencia Bancaria','Transferencia directa entre cuentas bancarias','Contado','2025-10-06 00:27:27'),(5,'Cheque','Pago mediante cheque bancario','Contado','2025-10-06 00:27:27'),(6,'Pago M├│vil','Pago usando aplicaciones m├│viles como Apple Pay, Google Pay o similares','Contado','2025-10-06 00:27:27'),(7,'PayPal','Pago realizado a trav├®s de la plataforma PayPal','Contado','2025-10-06 00:27:27'),(8,'Cr├®dito Comercial','Pago a cr├®dito otorgado por la empresa','Contado','2025-10-06 00:27:27'),(9,'Bitcoin','Pago con criptomoneda Bitcoin','Contado','2025-10-06 00:27:27'),(10,'Otro Criptoactivo','Pago con otras criptomonedas como Ethereum, USDT, etc.','Contado','2025-10-06 00:27:27'),(11,'POS','Pago mediante Punto de Venta bancario','Contado','2025-10-06 00:27:27'),(12,'Transferencia ACH','Transferencia por sistema ACH','Contado','2025-10-06 00:27:27'),(13,'Dep├│sito Bancario','Dep├│sito directo en cuenta bancaria','Contado','2025-10-06 00:27:27'),(14,'Pago Contra Entrega','Pago realizado en el momento de la entrega del producto o servicio','Contado','2025-10-06 00:27:27'),(15,'Vales','Pago mediante vales de empresa u otros convenios','Contado','2025-10-06 00:27:27'),(16,'Saldo a Favor','Uso de saldo disponible en el sistema a favor del cliente','Contado','2025-10-06 00:27:27'),(17,'Cuotas','Pago fraccionado en cuotas','Contado','2025-10-06 00:27:27'),(18,'D├®bito Autom├ítico','Cargo autom├ítico a cuenta bancaria o tarjeta recurrente','Contado','2025-10-06 00:27:27');
/*!40000 ALTER TABLE `tipopago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidadpeso`
--

DROP TABLE IF EXISTS `unidadpeso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidadpeso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `simbolo` varchar(10) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidadpeso`
--

LOCK TABLES `unidadpeso` WRITE;
/*!40000 ALTER TABLE `unidadpeso` DISABLE KEYS */;
INSERT INTO `unidadpeso` VALUES (1,'Miligramo','mg','activo','2025-10-06 00:27:27'),(2,'Centigramo','cg','activo','2025-10-06 00:27:27'),(3,'Decigramo','dg','activo','2025-10-06 00:27:27'),(4,'Gramo','g','activo','2025-10-06 00:27:27'),(5,'Decagramo','dag','activo','2025-10-06 00:27:27'),(6,'Hectogramo','hg','activo','2025-10-06 00:27:27'),(7,'Kilogramo','kg','activo','2025-10-06 00:27:27'),(8,'Tonelada','t','activo','2025-10-06 00:27:27'),(9,'Microgramo','┬Ág','activo','2025-10-06 00:27:27'),(10,'Onza','oz','activo','2025-10-06 00:27:27'),(11,'Libra','lb','activo','2025-10-06 00:27:27'),(12,'Tonelada corta','short ton','activo','2025-10-06 00:27:27'),(13,'Tonelada larga','long ton','activo','2025-10-06 00:27:27'),(14,'Stone','st','activo','2025-10-06 00:27:27'),(15,'Slug','slug','activo','2025-10-06 00:27:27'),(16,'Carat','ct','activo','2025-10-06 00:27:27'),(17,'Grano','gr','activo','2025-10-06 00:27:27'),(18,'U.S. hundredweight','cwt (US)','activo','2025-10-06 00:27:27'),(19,'Imperial hundredweight','cwt (Imp)','activo','2025-10-06 00:27:27');
/*!40000 ALTER TABLE `unidadpeso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo_usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT 'Administrador',
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `descuento` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rol` enum('admin','editor','usuario') NOT NULL DEFAULT 'usuario',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'moises','Aaron Moises Carrasco Thomas','081-030301-1009B','88090180','Nowhere',0.00,'usuario','maaroncarrasco@gmail.com','$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q',NULL),(2,'jdoe','Juan Doe','1234567890123','123-456-7890','Calle Ficticia 123, Managua',10.00,'usuario','jdoe@example.com','password123',NULL),(3,'mgarcia','Maria Garcia','9876543210987','321-654-0987','Avenida Central 456, Le├│n',15.00,'admin','mgarcia@example.com','adminpass456',NULL),(4,'lsanchez','Luis S├ínchez','4567891234567','555-555-5555','Calle Real 789, Chinandega',5.00,'editor','lsanchez@example.com','editorpass789',NULL),(5,'mperez','Maria P├®rez','6543219876543','777-777-7777','Avenida Libertad 101, Estel├¡',12.00,'usuario','mperez@example.com','password234',NULL),(6,'cpineda','Carlos Pineda','2135468790321','888-888-8888','Callej├│n del Sol 202, Masaya',8.50,'editor','cpineda@example.com','editorpass012',NULL),(7,'osolis','Oscar Sol├¡s','8765432109876','999-999-9999','Zona 1, Carretera Norte, Managua',20.00,'admin','osolis@example.com','adminpass789',NULL),(8,'lmartinez','Laura Mart├¡nez','4321098765432','111-222-3333','Calle Madero 303, Granada',7.50,'usuario','lmartinez@example.com','password345',NULL),(9,'rgarcia','Ricardo Garc├¡a','3216549876543','444-444-4444','Calle Las Palmas 404, Rivas',10.00,'editor','rgarcia@example.com','editorpass567',NULL),(10,'nlopez','Nina L├│pez','7654321098765','555-666-7777','Avenida Jos├® Dolores 505, Boaco',18.00,'admin','nlopez@example.com','adminpass234',NULL),(11,'jramirez','Jos├® Ram├¡rez','1237894561237','666-777-8888','Calle Real 606, Jinotega',6.00,'usuario','jramirez@example.com','password456',NULL),(12,'aperez','Ana P├®rez','7894561237894','777-888-9999','Avenida Nueva 707, Ocotal',10.50,'editor','aperez@example.com','editorpass890',NULL),(13,'bvalle','Beatriz Valle','8901234567890','999-000-1111','Calle San Juan 808, Carazo',5.00,'usuario','bvalle@example.com','password567',NULL),(14,'fhernandez','Felipe Hern├índez','2345678901234','222-333-4444','Avenida Norte 909, Chontales',12.00,'admin','fhernandez@example.com','adminpass567',NULL),(15,'vmoncada','Ver├│nica Moncada','5678901234567','333-444-5555','Calle Independencia 1010, Masaya',10.00,'usuario','vmoncada@example.com','password678',NULL),(16,'cmorales','Carlos Morales','8765432102345','444-555-6666','Calle Los Pinos 1111, Le├│n',15.00,'editor','cmorales@example.com','editorpass345',NULL),(17,'pmoreno','Pablo Moreno','2345678909876','555-666-7777','Avenida del Sol 1212, Estel├¡',7.00,'usuario','pmoreno@example.com','password789',NULL),(18,'jvargas','Julio Vargas','5432109876543','666-777-8888','Calle Las Rosas 1313, Managua',9.00,'admin','jvargas@example.com','adminpass890',NULL),(19,'nmorales','Nayeli Morales','6543210987654','777-888-9999','Avenida Central 1414, Chinandega',6.00,'usuario','nmorales@example.com','password234',NULL),(20,'marcelo','Marcelo L├│pez','3216549876543','555-444-3333','Calle Santa Teresa 1515, Boaco',8.00,'editor','marcelo@example.com','editorpass678',NULL),(21,'mflores','Marta Flores','7894561236547','444-555-6666','Avenida Los Robles 1616, Jinotega',11.00,'usuario','mflores@example.com','password345',NULL),(22,'srojas','Samuel Rojas','8901234567891','999-888-7777','Calle Los Alpes 1717, Carazo',10.00,'admin','srojas@example.com','adminpass012',NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(9,2) NOT NULL,
  `descuento` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_devuelto` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_pagado_cliente` decimal(5,2) NOT NULL DEFAULT 0.00,
  `numeroFactura` varchar(20) DEFAULT NULL,
  `idUsuario` bigint(20) NOT NULL,
  `idCliente` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numeroFactura` (`numeroFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-05 18:28:25
