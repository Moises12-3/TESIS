
-- Elimina la base de datos 'ventas_php' si existe, para crearla desde cero
DROP DATABASE IF EXISTS ventas_php;

-- Crea la base de datos 'ventas_php' con codificación UTF8MB4

CREATE DATABASE ventas_php
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


USE ventas_php;

-- ========================================
-- TABLA: empresa
-- Almacena información de las empresas
-- ========================================
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

-- ========================================
-- TABLA: usuarios
-- Almacena los usuarios que usan el sistema
-- ========================================
CREATE TABLE IF NOT EXISTS usuarios(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    cedula VARCHAR(50) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    
    -- CAMBIO REALIZADO: ahora es VARCHAR en vez de ENUM
    rol VARCHAR(50) NOT NULL DEFAULT 'VENTAS',
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,

    foto_perfil VARCHAR(255) DEFAULT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: clientes
-- Almacena información de los clientes
-- ========================================
CREATE TABLE IF NOT EXISTS clientes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    cedula VARCHAR(50) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descuento DECIMAL(5,2) NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: TipoPago
-- Almacena los tipos de pago disponibles
-- ========================================
CREATE TABLE IF NOT EXISTS TipoPago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,  
    descripcion TEXT,
    -- estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    estado VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: ventas
-- Almacena todas las ventas realizadas
-- ========================================
CREATE TABLE IF NOT EXISTS ventas(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(9,2) NOT NULL,
    descuento DECIMAL(5,2) NOT NULL DEFAULT 0,
    monto_devuelto DECIMAL(5,2) NOT NULL DEFAULT 0,
    monto_pagado_cliente DECIMAL(5,2) NOT NULL DEFAULT 0,
    -- numeroFactura VARCHAR(20) UNIQUE,
    numeroFactura VARCHAR(20) NOT NULL,
    idUsuario BIGINT UNSIGNED NOT NULL,
    idCliente BIGINT UNSIGNED,
    id_empresa INT NOT NULL,
    id_tipoPago INT NOT NULL,

    FOREIGN KEY (idUsuario) REFERENCES usuarios(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT,

    FOREIGN KEY (idCliente) REFERENCES clientes(id)
        ON UPDATE CASCADE ON DELETE SET NULL,

    FOREIGN KEY (id_empresa) REFERENCES empresa(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    FOREIGN KEY (id_tipoPago) REFERENCES TipoPago(id)
        ON UPDATE CASCADE ON DELETE RESTRICT

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: Moneda
-- Almacena monedas disponibles
-- ========================================
CREATE TABLE IF NOT EXISTS Moneda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    simbolo VARCHAR(10) NOT NULL,
    tipo ENUM('nacional', 'extranjera') NOT NULL,
    pais VARCHAR(100),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    valor DECIMAL(10,2) DEFAULT NULL, -- Solo se usa si la moneda es extranjera
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: UnidadPeso
-- Almacena las unidades de peso
-- ========================================
CREATE TABLE IF NOT EXISTS UnidadPeso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,  -- Ejemplo: Kilogramo, Gramo, Libra
    simbolo VARCHAR(10) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: proveedores
-- Almacena información de proveedores
-- ========================================
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

-- ========================================
-- TABLA: productos
-- Almacena información de productos
-- ========================================
CREATE TABLE IF NOT EXISTS productos(
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

    idProveedor BIGINT UNSIGNED,
    nombre_proveedor VARCHAR(255),


    FOREIGN KEY (id_UnidadPeso) REFERENCES UnidadPeso(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    
    FOREIGN KEY (idProveedor) REFERENCES proveedores(id)   -- Clave foránea a proveedores
        ON UPDATE CASCADE ON DELETE RESTRICT,

    FOREIGN KEY (idMoneda) REFERENCES Moneda(id)
        ON UPDATE CASCADE ON DELETE RESTRICT        

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: productos_ventas
-- Relación entre productos y ventas
-- ========================================
CREATE TABLE IF NOT EXISTS productos_ventas(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio DECIMAL(8,2) NOT NULL,
    numeroFactura VARCHAR(20),
    idProducto BIGINT UNSIGNED NOT NULL,
    idVenta BIGINT UNSIGNED NOT NULL,


    FOREIGN KEY (idProducto) REFERENCES productos(id) 
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (idVenta) REFERENCES ventas(id)
        ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ========================================
-- TABLA: devoluciones
-- Almacena devoluciones de productos
-- ========================================
CREATE TABLE IF NOT EXISTS devoluciones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,

    idVenta BIGINT UNSIGNED NOT NULL,              -- Relación con la venta
    numeroFactura VARCHAR(20) NOT NULL,   -- Número de factura original

    idProducto BIGINT UNSIGNED NOT NULL,           -- Producto devuelto
    cantidad_vendida INT NOT NULL,        -- Cantidad que se vendió originalmente
    cantidad_devuelta INT NOT NULL,       -- Cantidad devuelta en esta operación
    cantidad_devuelta_previa INT NOT NULL DEFAULT 0,  -- Cantidad devuelta anteriormente

    motivo VARCHAR(255) DEFAULT NULL,     -- Motivo de la devolución
    fecha_devolucion DATETIME DEFAULT CURRENT_TIMESTAMP,

    
    INDEX(idVenta),
    INDEX(idProducto),
    INDEX(numeroFactura),

    FOREIGN KEY (idProducto) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    FOREIGN KEY (idVenta) REFERENCES ventas(id)
        ON UPDATE CASCADE ON DELETE CASCADE

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Empresa inicial
INSERT INTO `empresa` (`id`, `nombre`, `direccion`, `correo`, `telefono`, `fax`, `codigo_interno`, `identidad_juridica`, `foto_perfil`, `fecha_registro`) VALUES
(1, 'UNIVERSIDAD', 'Universidad Nacional Comandante Padre Gaspar Garcia Laviana', 'maaroncarrasco@gmail.com', '88090180', '3232', 'EMP_68d4dcef4f446', '32432', 'images/logo_empresa/UNIVERSIDAD_68d4dd13678f5.png', '2025-09-25 06:10:55');



-- ========================================
-- TABLA: paginas_projectos
-- Guardará los permisos individuales por usuario
-- ========================================

CREATE TABLE IF NOT EXISTS paginas_projectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modulo VARCHAR(100) NOT NULL,
    pagina VARCHAR(255) NOT NULL,
    acceso ENUM('permitido','denegado') NOT NULL DEFAULT 'permitido',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- ========================================
-- TABLA: permisos_usuario
-- Relaciona usuarios con permisos
-- ========================================
CREATE TABLE IF NOT EXISTS permisos_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT UNSIGNED NOT NULL,
    id_permiso INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    FOREIGN KEY (id_permiso) REFERENCES paginas_projectos(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;



INSERT INTO paginas_projectos (modulo, pagina, acceso) VALUES
('accesos', 'accesos.php', 'permitido'),
('clientes', 'AgregarClientes.php', 'permitido'),
('productos', 'AgregarProductos.php', 'permitido'),
('usuarios', 'AgregarUsuario.php', 'permitido'),
('moneda', 'AjusteMoneda.php', 'permitido'),
('impuestos', 'AjustesImpuestos.php', 'permitido'),
('tipo_pago', 'AjustesTipoPago.php', 'permitido'),
('unidad_peso', 'AjusteUnidad.php', 'permitido'),
('backup', 'backup.php', 'permitido'),
('sesion', 'cerrar_sesion.php', 'permitido'),
('empresa', 'ConfigurarEmpresas.php', 'permitido'),
('reportes', 'descargar_pdf.php', 'permitido'),
('ventas', 'Devolucion.php', 'permitido'),
('clientes', 'EditarCliente.php', 'permitido'),
('productos', 'EditarProducto.php', 'permitido'),
('usuarios', 'EditarUsuario.php', 'permitido'),
('ventas', 'factura.php', 'permitido'),
('tools', 'hash.php', 'permitido'),
('dashboard', 'index.php', 'permitido'),
('dashboard', 'index_copy.php', 'permitido'),
('auth', 'login.php', 'permitido'),
('auth', 'logout.php', 'permitido'),
('perfil', 'MyProfile.php', 'permitido'),
('graficos', 'obtener_datos_graficos.php', 'permitido'),
('auth', 'page-login.php', 'permitido'),
('proveedores', 'Proveedor.php', 'permitido'),
('test', 'prueba.php', 'permitido'),
('tablas', 'tables-data.php', 'permitido'),
('ventas', 'Ventas.php', 'permitido'),
('ventas', 'ventas_select.php', 'permitido'),
('clientes', 'VerClientes.php', 'permitido'),
('ventas', 'VerDevolucion.php', 'permitido'),
('productos', 'VerFechaVencimiento.php', 'permitido'),
('productos', 'VerProductos.php', 'permitido'),
('reportes', 'VerReportes.php', 'permitido'),
('usuarios', 'VerUsuario.php', 'permitido'),
('ventas', 'ver_detalle_factura.php', 'permitido'),
('ventas', 'ver_facturas.php', 'permitido'),
('accesos', 'url.php', 'permitido');




-- ========================================
-- Usuario administrador inicial con permisos 
-- ========================================
INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, rol, email, password)
VALUES (
    "Moises", 
    "Aaron Moises Carrasco Thomas", 
    "081-03030301-1009B", 
    "+50588090180", 
    "Chinandega, Chinandega, NI", 
    "ADMINISTRADOR", 
    "maaroncarrasco@gmail.com", 
    "$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q"
);

-- Obtener el ID del usuario recién creado (supongamos que es 1, ajusta si es necesario)
SET @usuario_id = LAST_INSERT_ID();

-- Insertar permisos para el usuario administrador
INSERT INTO permisos_usuario (id_usuario, id_permiso)
SELECT @usuario_id, id FROM paginas_projectos;

-- ========================================
-- Usuario ventas inicial con permisos 
-- ========================================
INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, rol, email, password)
VALUES ("ventas1", "Usuario Ventas Inicial", "001-000000-0000V", "88090000", "Nowhere", "VENTAS", "ventas1@example.com", "$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q");

-- Obtener el ID del usuario recién creado
SET @usuario_ventas_id = LAST_INSERT_ID();

-- Insertar permisos solo para el rol VENTAS
INSERT INTO permisos_usuario (id_usuario, id_permiso)
SELECT @usuario_ventas_id, id
FROM paginas_projectos
WHERE pagina IN (
    'Ventas.php',
    'ventas_select.php',
    'VerClientes.php',
    'VerDevolucion.php',
    'VerFechaVencimiento.php',
    'VerProductos.php',
    'VerReportes.php',
    'VerUsuario.php',
    'ver_detalle_factura.php',
    'ver_facturas.php',
    'MyProfile.php',
    'url.php'
);
