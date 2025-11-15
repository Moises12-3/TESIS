DROP DATABASE IF EXISTS ventas_php;

CREATE DATABASE ventas_php
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


USE ventas_php;

-- Tabla empresa
CREATE TABLE IF NOT EXISTS empresa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    fax VARCHAR(50) NOT NULL,
    codigo_interno VARCHAR(100) UNIQUE,
    identidad_juridica VARCHAR(100),
    foto_perfil VARCHAR(255) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabla usuario (administrador)
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    nombre_usuario VARCHAR(100) NOT NULL,
    correo_usuario VARCHAR(255) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'Administrador',
    FOREIGN KEY (id_empresa) REFERENCES empresa(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE usuarios(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    cedula VARCHAR(50) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descuento DECIMAL(5,2) NOT NULL DEFAULT 0, 
    rol ENUM('admin', 'editor', 'usuario') NOT NULL DEFAULT 'usuario',
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,

    foto_perfil VARCHAR(255) DEFAULT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE clientes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    cedula VARCHAR(50) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descuento DECIMAL(5,2) NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


CREATE TABLE ventas(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(9,2) NOT NULL,
    descuento DECIMAL(5,2) NOT NULL DEFAULT 0,
    monto_devuelto DECIMAL(5,2) NOT NULL DEFAULT 0,
    monto_pagado_cliente DECIMAL(5,2) NOT NULL DEFAULT 0,
    numeroFactura VARCHAR(20) UNIQUE,
    idUsuario BIGINT NOT NULL,
    idCliente BIGINT
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE productos_ventas(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio DECIMAL(8,2) NOT NULL,
    numeroFactura VARCHAR(20),
    idProducto BIGINT NOT NULL,
    idVenta BIGINT NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE Moneda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    simbolo VARCHAR(10) NOT NULL,
    tipo ENUM('nacional', 'extranjera') NOT NULL,
    pais VARCHAR(100),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    valor DECIMAL(10,2) DEFAULT NULL, -- Solo se usa si la moneda es extranjera
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE TipoPago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,  -- Ejemplo: Efectivo, Tarjeta de Crédito, Transferencia
    descripcion TEXT,
    estado ENUM('Efectivo', 'Contado') DEFAULT 'Efectivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE UnidadPeso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,  -- Ejemplo: Kilogramo, Gramo, Libra
    simbolo VARCHAR(10) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE Impuesto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,  -- Ejemplo: IVA, ISV
    porcentaje DECIMAL(5,2) NOT NULL,  -- Porcentaje del impuesto, ejemplo: 15.00
    descripcion TEXT,
    tipo_impuesto ENUM('Porcentaje', 'Fijo') DEFAULT 'porcentaje',
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS proveedores (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    cedula VARCHAR(50),
    telefono VARCHAR(25),
    correo VARCHAR(255),
    direccion VARCHAR(255),
    empresa VARCHAR(255),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE productos(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    compra DECIMAL(8,2) NOT NULL,
    venta DECIMAL(8,2) NOT NULL,
    fecha_vencimiento DATE NULL,
    iva DECIMAL(8,2) NOT NULL,
    existencia INT NOT NULL,
    idMoneda INT,
    nombre_moneda VARCHAR(100),
    id_UnidadPeso INT,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',    
    nombre_UnidadPeso VARCHAR(100),

    idProveedor INT,
    nombre_proveedor VARCHAR(255)

) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS devoluciones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,

    idVenta BIGINT NOT NULL,              -- Relación con la venta
    numeroFactura VARCHAR(20) NOT NULL,   -- Número de factura original

    idProducto BIGINT NOT NULL,           -- Producto devuelto
    cantidad_vendida INT NOT NULL,        -- Cantidad que se vendió originalmente
    cantidad_devuelta INT NOT NULL,       -- Cantidad devuelta en esta operación
    cantidad_devuelta_previa INT NOT NULL DEFAULT 0,  -- Cantidad devuelta anteriormente

    motivo VARCHAR(255) DEFAULT NULL,     -- Motivo de la devolución
    fecha_devolucion DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- Llaves foráneas opcionales (descoméntalas si existen las tablas referenciadas)
    -- FOREIGN KEY (idVenta) REFERENCES ventas(id),
    -- FOREIGN KEY (idProducto) REFERENCES productos(id)
    
    INDEX(idVenta),
    INDEX(idProducto),
    INDEX(numeroFactura)
);




 ALTER TABLE ventas ENGINE=InnoDB;
 ALTER TABLE productos ENGINE=InnoDB;
 ALTER TABLE devoluciones ENGINE=InnoDB;

-- INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, descuento, email, password) VALUES ("maaroncarrasco@gmail.com", "081-030301-1009B", "maaroncarrasco@gmail.com", "6667771234", "Nowhere", "0", "maaroncarrasco@gmail.com","$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q");
INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, descuento, email, password) VALUES ("moises", "Aaron Moises Carrasco Thomas", "081-030301-1009B", "88090180", "Nowhere", 0.00, "maaroncarrasco@gmail.com","$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q");

INSERT INTO `empresa` (`id`, `nombre`, `direccion`, `correo`, `telefono`, `fax`, `codigo_interno`, `identidad_juridica`, `foto_perfil`, `fecha_registro`) VALUES
(1, 'UNIVERSIDAD', 'Universidad Nacional Comandante Padre Gaspar Garcia Laviana', 'maaroncarrasco@gmail.com', '88090180', '3232', 'EMP_68d4dcef4f446', '32432', 'images/logo_empresa/UNIVERSIDAD_68d4dd13678f5.png', '2025-09-25 06:10:55');

-- CREATE DATABASE IF NOT EXISTS ConvertidorMedidas;
-- USE ConvertidorMedidas;

CREATE TABLE IF NOT EXISTS convertidor_medidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_medida VARCHAR(50) NOT NULL,
    unidad_origen VARCHAR(50) NOT NULL,
    unidad_destino VARCHAR(50) NOT NULL,
    factor_conversion DECIMAL(15,10) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Inserción de datos comunes
INSERT INTO convertidor_medidas (tipo_medida, unidad_origen, unidad_destino, factor_conversion) VALUES
-- Longitud
('Longitud', 'Metro', 'Centímetro', 100),
('Longitud', 'Metro', 'Milímetro', 1000),
('Longitud', 'Kilómetro', 'Metro', 1000),
('Longitud', 'Pulgada', 'Centímetro', 2.54),
('Longitud', 'Pie', 'Metro', 0.3048),
('Longitud', 'Yarda', 'Metro', 0.9144),
('Longitud', 'Milla', 'Kilómetro', 1.60934),

-- Peso
('Peso', 'Kilogramo', 'Gramo', 1000),
('Peso', 'Kilogramo', 'Libra', 2.20462),
('Peso', 'Gramo', 'Miligramo', 1000),
('Peso', 'Libra', 'Onza', 16),
('Peso', 'Tonelada', 'Kilogramo', 1000),

-- Volumen
('Volumen', 'Litro', 'Mililitro', 1000),
('Volumen', 'Metro cúbico', 'Litro', 1000),
('Volumen', 'Galón', 'Litro', 3.78541),
('Volumen', 'Pinta', 'Litro', 0.473176),

-- Temperatura (diferentes fórmulas, factor de conversión referencial)
('Temperatura', 'Celsius', 'Fahrenheit', 1.8),   -- (°C × 1.8) + 32 = °F
('Temperatura', 'Fahrenheit', 'Celsius', 0.5556), -- (°F - 32) × 0.5556 = °C
('Temperatura', 'Celsius', 'Kelvin', 1),          -- °C + 273.15 = K
('Temperatura', 'Kelvin', 'Celsius', 1);          -- K - 273.15 = °C

-- Consultar la tabla
SELECT * FROM convertidor_medidas;
