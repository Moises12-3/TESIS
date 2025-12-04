USE ventas_php;


-- ========================================
-- TABLA: Moneda (30 monedas reales para licorería en Nicaragua)
-- ========================================
INSERT INTO Moneda (nombre, simbolo, tipo, pais, estado, valor) VALUES    
('Córdoba Oro', 'NIO', 'nacional', 'Nicaragua', 'activo', 1.00),
('Dólar Estadounidense', 'USD', 'extranjera', 'Estados Unidos', 'activo', 36.50),
('Euro', 'EUR', 'extranjera', 'Unión Europea', 'activo', 39.25),
('Peso Mexicano', 'MXN', 'extranjera', 'México', 'activo', 2.15),
('Colón Costarricense', 'CRC', 'extranjera', 'Costa Rica', 'activo', 0.062),
('Quetzal Guatemalteco', 'GTQ', 'extranjera', 'Guatemala', 'activo', 4.70),
('Lempira Hondureña', 'HNL', 'extranjera', 'Honduras', 'activo', 1.48),
('Colón Salvadoreño', 'SVC', 'extranjera', 'El Salvador', 'activo', 0.42),
('Balboa Panameño', 'PAB', 'extranjera', 'Panamá', 'activo', 1.00),
('Dólar Canadiense', 'CAD', 'extranjera', 'Canadá', 'activo', 26.80),
('Libra Esterlina', 'GBP', 'extranjera', 'Reino Unido', 'activo', 46.20),
('Yen Japonés', 'JPY', 'extranjera', 'Japón', 'activo', 0.24),
('Franco Suizo', 'CHF', 'extranjera', 'Suiza', 'activo', 40.80),
('Dólar Australiano', 'AUD', 'extranjera', 'Australia', 'activo', 24.10),
('Dólar Neozelandés', 'NZD', 'extranjera', 'Nueva Zelanda', 'activo', 22.30),
('Peso Chileno', 'CLP', 'extranjera', 'Chile', 'activo', 0.042),
('Peso Colombiano', 'COP', 'extranjera', 'Colombia', 'activo', 0.009),
('Sol Peruano', 'PEN', 'extranjera', 'Perú', 'activo', 9.80),
('Real Brasileño', 'BRL', 'extranjera', 'Brasil', 'activo', 7.20),
('Peso Argentino', 'ARS', 'extranjera', 'Argentina', 'activo', 0.11),
('Boliviano', 'BOB', 'extranjera', 'Bolivia', 'activo', 5.30),
('Guaraní Paraguayo', 'PYG', 'extranjera', 'Paraguay', 'activo', 0.005),
('Peso Uruguayo', 'UYU', 'extranjera', 'Uruguay', 'activo', 0.95),
('Bolívar Venezolano', 'VES', 'extranjera', 'Venezuela', 'activo', 0.000001),
('Dólar del Caribe Oriental', 'XCD', 'extranjera', 'Caribe Oriental', 'activo', 13.50),
('Dólar Bermudeño', 'BMD', 'extranjera', 'Bermudas', 'activo', 1.00),
('Dólar de las Bahamas', 'BSD', 'extranjera', 'Bahamas', 'activo', 1.00),
('Peso Dominicano', 'DOP', 'extranjera', 'República Dominicana', 'activo', 0.65),
('Gourde Haitiano', 'HTG', 'extranjera', 'Haití', 'activo', 0.27),
('Dólar Jamaiquino', 'JMD', 'extranjera', 'Jamaica', 'activo', 0.24);


-- ========================================
-- TABLA: UnidadPeso (30 unidades para licorería)
-- ========================================
INSERT INTO UnidadPeso (nombre, simbolo, estado) VALUES
('Mililitro', 'ml', 'activo'),           -- Para bebidas alcohólicas
('Centilitro', 'cl', 'activo'),          -- Común en licores importados
('Decilitro', 'dl', 'activo'),           -- Para mediciones de cócteles
('Litro', 'L', 'activo'),                -- Para botellas grandes
('Onza líquida', 'fl oz', 'activo'),     -- Para recetas de coctelería
('Galón', 'gal', 'activo'),              -- Para productos a granel
('Pulgada cúbica', 'in³', 'activo'),     -- Para contenedores especiales
('Pinta', 'pt', 'activo'),               -- Sistema inglés
('Cuarto', 'qt', 'activo'),              -- Sistema inglés
('Barril', 'bbl', 'activo'),             -- Para cerveza
('Miligramo', 'mg', 'activo'),           -- Para ingredientes secos
('Gramo', 'g', 'activo'),                -- Para frutos secos y snacks
('Kilogramo', 'kg', 'activo'),           -- Para hielo y productos grandes
('Onza', 'oz', 'activo'),                -- Sistema inglés para sólidos
('Libra', 'lb', 'activo'),               -- Sistema inglés
('Quintal', 'qq', 'activo'),             -- Para compras a granel
('Tonelada', 't', 'activo'),             -- Para proveedores mayoristas
('Arroba', '@', 'activo'),               -- Unidad tradicional
('Docena', 'dz', 'activo'),              -- Para botellas individuales
('Unidad', 'u', 'activo'),               -- Para botellas sueltas
('Caja', 'caja', 'activo'),              -- Para empaques
('Pack', 'pack', 'activo'),              -- Para promociones
('Sixpack', '6pk', 'activo'),            -- Para cervezas
('Cartón', 'cartón', 'activo'),          -- Para cervezas y refrescos
('Botella', 'bot', 'activo'),            -- Unidad común en licorería
('Fardo', 'fardo', 'activo'),            -- Para envases retornables
('Estuche', 'est', 'activo'),            -- Para licores premium
('Garrafa', 'gar', 'activo'),            -- Para vinos y licores grandes
('Cubeta', 'cub', 'activo'),             -- Para hielo y productos
('Sobre', 'sob', 'activo');              -- Para mezclas y aderezos


-- ========================================
-- TABLA: TipoPago (18 métodos para licorería)
-- ========================================
INSERT INTO TipoPago (nombre, descripcion, estado) VALUES
('Efectivo', 'Pago en moneda local (Córdobas)', 'Activo'),
('Tarjeta de Débito', 'Pago con tarjeta de débito local', 'Activo'),
('Tarjeta de Crédito', 'Pago con tarjeta de crédito nacional/internacional', 'Activo'),
('Transferencia BAC', 'Transferencia bancaria por BAC', 'Activo'),
('Transferencia Banpro', 'Transferencia bancaria por Banpro', 'Activo'),
('Transferencia Lafise', 'Transferencia bancaria por Lafise', 'Activo'),
('Pago Móvil (ENLACE)', 'Pago mediante banca móvil ENLACE', 'Activo'),
('Cheque', 'Pago con cheque certificado', 'Activo'),
('Crédito Comercial', 'Crédito a clientes corporativos (30 días)', 'Activo'),
('Pago con SINPE', 'Transferencia inmediata SINPE móvil', 'Activo'),
('PayPal', 'Pago internacional vía PayPal', 'Activo'),
('Bitcoin', 'Pago con criptomoneda Bitcoin', 'Activo'),
('Zelle', 'Transferencia Zelle para clientes en USA', 'Activo'),
('Western Union', 'Pago mediante giro internacional', 'Activo'),
('Vales de Consumo', 'Vales corporativos de empresas', 'Activo'),
('Puntos de Fidelidad', 'Redención de puntos de programa de fidelidad', 'Activo'),
('Mixto (Efectivo/Tarjeta)', 'Pago combinado en efectivo y tarjeta', 'Activo'),
('Apartado', 'Señal para apartar productos especiales', 'Activo');


-- ========================================
-- TABLA: usuarios (30 usuarios para licorería)
-- NOTA: La columna 'descuento' no existe en tu tabla usuarios, la omito
-- Rol solo puede ser "ADMINISTRADOR" o "VENTAS"
-- ========================================
INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, rol, email, password, foto_perfil) VALUES
('admin_licor', 'Carlos Mendoza Rojas', '001-280785-1001A', '88551234', 'Residencial Bolonia, Managua', 'ADMINISTRADOR', 'carlos.mendoza@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas1', 'Ana Lucía González', '001-150992-1002B', '87765432', 'Barrio Martha Quezada, Managua', 'VENTAS', 'ana.gonzalez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('jperez', 'José Pérez Martínez', '001-200887-1003C', '88812345', 'Reparto San Juan, Managua', 'VENTAS', 'jose.perez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('mrodriguez', 'María Rodríguez Silva', '001-100195-1004D', '88998765', 'Las Colinas, Managua', 'ADMINISTRADOR', 'maria.rodriguez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas2', 'Luis Alberto Chávez', '001-050490-1005E', '87651234', 'Barrio Cuba, Managua', 'VENTAS', 'luis.chavez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('cgarcia', 'Carmen García López', '001-120880-1006F', '88554321', 'Residencial Las Praderas, León', 'VENTAS', 'carmen.garcia@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_inv', 'Roberto Jiménez Paz', '001-300475-1007G', '87771234', 'Camino de Oriente, Managua', 'ADMINISTRADOR', 'roberto.jimenez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas3', 'Sofía Hernández Ruiz', '001-180993-1008H', '88887654', 'Barrio Monseñor Lezcano, Managua', 'VENTAS', 'sofia.hernandez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('jmorales', 'Javier Morales Castro', '001-220785-1009I', '88991234', 'Residencial Los Robles, Managua', 'VENTAS', 'javier.morales@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_ger', 'Patricia Vargas Salinas', '001-080270-1010J', '87659876', 'Planes de Altamira, Managua', 'ADMINISTRADOR', 'patricia.vargas@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas4', 'Diego Ramírez Mejía', '001-250895-1011K', '88556789', 'Barrio San Judas, Managua', 'VENTAS', 'diego.ramirez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('atorres', 'Andrea Torres Rivas', '001-140987-1012L', '87775678', 'Residencial Las Colinas, Managua', 'VENTAS', 'andrea.torres@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas5', 'Miguel Ángel Sandoval', '001-300392-1013M', '88889012', 'Barrio Larreynaga, Managua', 'VENTAS', 'miguel.sandoval@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_sis', 'Gabriela Flores Ortega', '001-120775-1014N', '88993456', 'Camino de Oriente, Managua', 'ADMINISTRADOR', 'gabriela.flores@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas6', 'Fernando Castro Díaz', '001-180198-1015O', '87652345', 'Residencial Bolonia, Managua', 'VENTAS', 'fernando.castro@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('lgonzalez', 'Laura González Mendoza', '001-220890-1016P', '88557890', 'Barrio Martha Quezada, Managua', 'VENTAS', 'laura.gonzalez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas7', 'Ricardo Juárez López', '001-050195-1017Q', '87776789', 'Reparto San Juan, Managua', 'VENTAS', 'ricardo.juarez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_fin', 'Karla Martínez Rivera', '001-100680-1018R', '88880123', 'Las Colinas, Managua', 'ADMINISTRADOR', 'karla.martinez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas8', 'Oscar Herrera Salgado', '001-150493-1019S', '88994567', 'Barrio Cuba, Managua', 'VENTAS', 'oscar.herrera@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('sruiz', 'Silvia Ruiz Vargas', '001-280788-1020T', '87653456', 'Residencial Las Praderas, León', 'VENTAS', 'silvia.ruiz@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas9', 'Alberto Méndez Cruz', '001-120991-1021U', '88558901', 'Camino de Oriente, Managua', 'VENTAS', 'alberto.mendez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_ope', 'Raúl Solís Rocha', '001-030785-1022V', '87777890', 'Barrio Monseñor Lezcano, Managua', 'ADMINISTRADOR', 'raul.solis@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas10', 'Natalia Peña Gutiérrez', '001-170894-1023W', '88881234', 'Residencial Los Robles, Managua', 'VENTAS', 'natalia.pena@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('cdiaz', 'Cristian Díaz Mora', '001-220195-1024X', '88995678', 'Planes de Altamira, Managua', 'VENTAS', 'cristian.diaz@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas11', 'Valeria Romero Sánchez', '001-080392-1025Y', '87654567', 'Barrio San Judas, Managua', 'VENTAS', 'valeria.romero@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_com', 'Esteban Guzmán Torres', '001-150785-1026Z', '88559012', 'Residencial Las Colinas, Managua', 'ADMINISTRADOR', 'esteban.guzman@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas12', 'Jimena Castro Rivas', '001-200993-1027AA', '87778901', 'Barrio Larreynaga, Managua', 'VENTAS', 'jimena.castro@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('jlopez', 'Jorge López Salinas', '001-100490-1028AB', '88882345', 'Camino de Oriente, Managua', 'VENTAS', 'jorge.lopez@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('ventas13', 'Diana Mejía Flores', '001-250191-1029AC', '88996789', 'Residencial Bolonia, Managua', 'VENTAS', 'diana.mejia@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL),
('admin_sup', 'Mario Rojas Chávez', '001-180685-1030AD', '87655678', 'Barrio Martha Quezada, Managua', 'ADMINISTRADOR', 'mario.rojas@licorerianica.com', '$2y$10$T5D81rjO/yQWY3vP0isjquwxMr4gnGRFloeCFRz72U97OV9Zb0i1q', NULL);


-- ========================================
-- TABLA: clientes (30 clientes reales para licorería)
-- ========================================
INSERT INTO clientes (nombre, cedula, telefono, direccion, descuento) VALUES
('Juan Carlos Bolaños', '001-150780-2001A', '88551001', 'Residencial Bolonia, Managua', 5.00),
('María Elena Salazar', '001-220985-2002B', '87762002', 'Barrio Martha Quezada, Managua', 10.00),
('Roberto José Gutiérrez', '001-300475-2003C', '88883003', 'Reparto San Juan, Managua', 3.00),
('Ana Patricia Silva', '001-120888-2004D', '88994004', 'Las Colinas, Managua', 8.00),
('Luis Fernando Mendoza', '001-050390-2005E', '87655005', 'Barrio Cuba, Managua', 15.00),
('Carmen Rosa López', '001-180195-2006F', '88556006', 'Residencial Las Praderas, León', 7.00),
('Francisco Javier Rivas', '001-250880-2007G', '87767007', 'Camino de Oriente, Managua', 12.00),
('Sofía Alejandra Ruiz', '001-090993-2008H', '88888008', 'Barrio Monseñor Lezcano, Managua', 5.00),
('Miguel Ángel Torres', '001-170785-2009I', '88999009', 'Residencial Los Robles, Managua', 10.00),
('Patricia Isabel Vargas', '001-220290-2010J', '87651010', 'Planes de Altamira, Managua', 20.00),
('Diego Andrés Ramírez', '001-150495-2011K', '88551111', 'Barrio San Judas, Managua', 6.00),
('Andrea Carolina Mejía', '001-280887-2012L', '87761212', 'Residencial Las Colinas, Managua', 9.00),
('Ricardo Antonio Sandoval', '001-120392-2013M', '88881313', 'Barrio Larreynaga, Managua', 4.00),
('Gabriela Fernanda Flores', '001-050775-2014N', '88991414', 'Camino de Oriente, Managua', 11.00),
('Fernando José Castro', '001-190198-2015O', '87651515', 'Residencial Bolonia, Managua', 8.00),
('Laura Marcela González', '001-240890-2016P', '88551616', 'Barrio Martha Quezada, Managua', 13.00),
('Carlos Alberto Juárez', '001-080195-2017Q', '87761717', 'Reparto San Juan, Managua', 7.00),
('Karla Susana Martínez', '001-130680-2018R', '88881818', 'Las Colinas, Managua', 16.00),
('Oscar Eduardo Herrera', '001-170493-2019S', '88991919', 'Barrio Cuba, Managua', 5.00),
('Silvia Margarita Salgado', '001-300788-2020T', '87652020', 'Residencial Las Praderas, León', 10.00),
('Alberto Enrique Méndez', '001-140991-2021U', '88552121', 'Camino de Oriente, Managua', 14.00),
('Raúl Antonio Solís', '001-050785-2022V', '87762222', 'Barrio Monseñor Lezcano, Managua', 6.00),
('Natalia de los Ángeles Peña', '001-190894-2023W', '88882323', 'Residencial Los Robles, Managua', 9.00),
('Cristian Daniel Díaz', '001-240195-2024X', '88992424', 'Planes de Altamira, Managua', 12.00),
('Valeria Cristina Romero', '001-100392-2025Y', '87652525', 'Barrio San Judas, Managua', 8.00),
('Esteban Rafael Guzmán', '001-170785-2026Z', '88552626', 'Residencial Las Colinas, Managua', 15.00),
('Jimena Isabel Castro', '001-220993-2027AA', '87762727', 'Barrio Larreynaga, Managua', 7.00),
('Jorge Luis López', '001-120490-2028AB', '88882828', 'Camino de Oriente, Managua', 11.00),
('Diana Carolina Mejía', '001-270191-2029AC', '88992929', 'Residencial Bolonia, Managua', 6.00),
('Mario Ernesto Rojas', '001-200685-2030AD', '87653030', 'Barrio Martha Quezada, Managua', 10.00);


-- ========================================
-- TABLA: proveedores (30 proveedores reales para licorería en Nicaragua)
-- ========================================
INSERT INTO proveedores (nombre, cedula, telefono, correo, direccion, empresa, estado) VALUES
-- Distribuidoras de Licores Nacionales
('Carlos Mena Rocha', '001-150670-3001A', '22781234', 'carlos.mena@distribuidoralicores.com', 'Km 6.5 Carretera Norte, Managua', 'Distribuidora Nacional de Licores S.A.', 'activo'),
('María José Gutiérrez', '001-220875-3002B', '22782345', 'maria.gutierrez@flordecana.com', 'Comarca Las Banderas, Managua', 'Compañía Licorera de Nicaragua S.A.', 'activo'),
('Roberto Salazar Vásquez', '001-300575-3003C', '22783456', 'roberto.salazar@bebidasnic.com', 'Residencial Las Colinas, Managua', 'Bebidas de Nicaragua S.A.', 'activo'),
('Ana Lucía Ramírez', '001-120880-3004D', '22784567', 'ana.ramirez@distribuidoracentral.com', 'Barrio Martha Quezada, Managua', 'Distribuidora Central S.A.', 'activo'),
('Luis Fernando Ríos', '001-050490-3005E', '22785678', 'luis.rios@licoresexpress.com', 'Reparto San Juan, Managua', 'Licores Express Nicaragua', 'activo'),

-- Distribuidoras de Cerveza
('Francisco Javier Torres', '001-180195-3006F', '22786789', 'francisco.torres@cervcenic.com', 'Km 7 Carretera a Masaya, Managua', 'Cervecería Centroamericana S.A.', 'activo'),
('Sofía Patricia Mendoza', '001-250890-3007G', '22787890', 'sofia.mendoza@toñadistribuidora.com', 'Las Colinas, Managua', 'Distribuidora Toña S.A.', 'activo'),
('Miguel Ángel Herrera', '001-090995-3008H', '22788901', 'miguel.herrera@victoriadistribucion.com', 'Camino de Oriente, Managua', 'Distribución Victoria S.A.', 'activo'),
('Patricia Isabel López', '001-170790-3009I', '22789012', 'patricia.lopez@premiumbeerdist.com', 'Residencial Los Robles, Managua', 'Premium Beer Distributors', 'activo'),
('Diego Andrés Castro', '001-220295-3010J', '22780123', 'diego.castro@cervecerianacional.com', 'Planes de Altamira, Managua', 'Cervecería Nacional de Nicaragua', 'activo'),

-- Importadores de Licores Extranjeros
('Andrea Carolina Mejía', '001-150495-3011K', '22781234', 'andrea.mejia@importadoralider.com', 'Barrio San Judas, Managua', 'Importadora Líder S.A.', 'activo'),
('Ricardo Antonio Sandoval', '001-280890-3012L', '22782345', 'ricardo.sandoval@whiskyimports.com', 'Residencial Las Colinas, Managua', 'Whisky Imports Nicaragua', 'activo'),
('Gabriela Fernanda Flores', '001-120395-3013M', '22783456', 'gabriela.flores@vodkamundo.com', 'Barrio Larreynaga, Managua', 'Vodka Mundo Import S.A.', 'activo'),
('Fernando José Rivas', '001-050795-3014N', '22784567', 'fernando.rivas@ronimportador.com', 'Camino de Oriente, Managua', 'Ron Importador S.A.', 'activo'),
('Laura Marcela González', '001-190298-3015O', '22785678', 'laura.gonzalez@tequilamasters.com', 'Residencial Bolonia, Managua', 'Tequila Masters Nicaragua', 'activo'),

-- Distribuidores de Vinos
('Carlos Alberto Juárez', '001-240895-3016P', '22786789', 'carlos.juarez@vinosdelmundo.com', 'Barrio Martha Quezada, Managua', 'Vinos del Mundo S.A.', 'activo'),
('Karla Susana Martínez', '001-080195-3017Q', '22787890', 'karla.martinez@importadoradevinos.com', 'Reparto San Juan, Managua', 'Importadora de Vinos Selectos', 'activo'),
('Oscar Eduardo Solís', '001-130695-3018R', '22788901', 'oscar.solis@champagnenic.com', 'Las Colinas, Managua', 'Champagne Nicaragua Import', 'activo'),
('Silvia Margarita Ruiz', '001-170498-3019S', '22789012', 'silvia.ruiz@vinosdepremium.com', 'Barrio Cuba, Managua', 'Vinos de Premium S.A.', 'activo'),
('Alberto Enrique Vargas', '001-300798-3020T', '22780123', 'alberto.vargas@espumantesnic.com', 'Residencial Las Praderas, León', 'Espumantes de Nicaragua', 'activo'),

-- Proveedores de Refrescos y Bebidas no Alcohólicas
('Raúl Antonio Díaz', '001-140995-3021U', '22781234', 'raul.diaz@refrescosdist.com', 'Camino de Oriente, Managua', 'Distribuidora de Refrescos S.A.', 'activo'),
('Natalia de los Ángeles Peña', '001-050895-3022V', '22782345', 'natalia.pena@cocacoladist.com', 'Barrio Monseñor Lezcano, Managua', 'Embotelladora de Nicaragua S.A.', 'activo'),
('Cristian Daniel Romero', '001-190998-3023W', '22783456', 'cristian.romero@pepsidistribucion.com', 'Residencial Los Robles, Managua', 'Pepsi Distribución Nicaragua', 'activo'),
('Valeria Cristina Guzmán', '001-240295-3024X', '22784567', 'valeria.guzman@schweppesnic.com', 'Planes de Altamira, Managua', 'Schweppes de Nicaragua', 'activo'),
('Esteban Rafael Castro', '001-100395-3025Y', '22785678', 'esteban.castro@jugosnaturales.com', 'Barrio San Judas, Managua', 'Jugos Naturales Distribución', 'activo'),

-- Proveedores de Snacks y Aperitivos
('Jimena Isabel López', '001-170895-3026Z', '22786789', 'jimena.lopez@snacksdistribuidora.com', 'Residencial Las Colinas, Managua', 'Distribuidora de Snacks S.A.', 'activo'),
('Jorge Luis Mejía', '001-220498-3027AA', '22787890', 'jorge.mejia@papaslaysdist.com', 'Barrio Larreynaga, Managua', 'Papas Lays Distribución', 'activo'),
('Diana Carolina Rojas', '001-120195-3028AB', '22788901', 'diana.rojas@quesosproveedor.com', 'Camino de Oriente, Managua', 'Proveedor de Quesos Selectos', 'activo'),
('Mario Ernesto Salgado', '001-270698-3029AC', '22789012', 'mario.salgado@chicharronesdist.com', 'Residencial Bolonia, Managua', 'Distribuidora de Chicharrones', 'activo'),
('Ricardo José Mendoza', '001-200795-3030AD', '22780123', 'ricardo.mendoza@tostadasproveedor.com', 'Barrio Martha Quezada, Managua', 'Proveedor de Tostadas y Botanas', 'activo');


-- ========================================
-- TABLA: productos (30 productos para licorería)
-- CON TODAS LAS REFERENCIAS COMPLETAS
-- ========================================
INSERT INTO productos (codigo, nombre, compra, venta, fecha_vencimiento, iva, existencia, 
                       idMoneda, nombre_moneda, id_UnidadPeso, nombre_UnidadPeso,
                       idProveedor, nombre_proveedor, estado) VALUES
-- Licores Nacionales (Proveedores 1-2)
('P001', 'Flor de Caña 4 años 750ml', 150.00, 220.00, '2030-12-31', 15.00, 50, 
 1, 'Córdoba Oro', 25, 'Botella',
 2, 'María José Gutiérrez', 'activo'),
 
('P002', 'Flor de Caña 7 años 750ml', 220.00, 320.00, '2031-06-30', 15.00, 35, 
 1, 'Córdoba Oro', 25, 'Botella',
 2, 'María José Gutiérrez', 'activo'),
 
('P003', 'Flor de Caña 12 años 750ml', 350.00, 480.00, '2032-03-31', 15.00, 20, 
 1, 'Córdoba Oro', 25, 'Botella',
 2, 'María José Gutiérrez', 'activo'),
 
('P004', 'Ron Plata 1 Litro', 120.00, 180.00, '2029-11-30', 15.00, 60, 
 1, 'Córdoba Oro', 4, 'Litro',
 1, 'Carlos Mena Rocha', 'activo'),
 
('P005', 'Vodka Nacional 750ml', 85.00, 130.00, '2028-10-15', 15.00, 45, 
 1, 'Córdoba Oro', 25, 'Botella',
 3, 'Roberto Salazar Vásquez', 'activo'),

-- Cervezas Nacionales (Proveedores 6-10)
('P006', 'Toña Lata 12oz Pack 6', 60.00, 90.00, '2025-08-01', 15.00, 100, 
 1, 'Córdoba Oro', 23, 'Sixpack',
 7, 'Sofía Patricia Mendoza', 'activo'),
 
('P007', 'Victoria Lata 12oz Pack 6', 65.00, 95.00, '2025-07-15', 15.00, 80, 
 1, 'Córdoba Oro', 23, 'Sixpack',
 8, 'Miguel Ángel Herrera', 'activo'),
 
('P008', 'Premium Lata 12oz Pack 6', 70.00, 105.00, '2025-09-30', 15.00, 70, 
 1, 'Córdoba Oro', 23, 'Sixpack',
 9, 'Patricia Isabel López', 'activo'),
 
('P009', 'Toña Botella Retornable 620ml', 25.00, 40.00, '2025-06-20', 15.00, 150, 
 1, 'Córdoba Oro', 25, 'Botella',
 7, 'Sofía Patricia Mendoza', 'activo'),
 
('P010', 'Victoria Botella Retornable 620ml', 27.00, 42.00, '2025-06-30', 15.00, 120, 
 1, 'Córdoba Oro', 25, 'Botella',
 8, 'Miguel Ángel Herrera', 'activo'),

-- Licores Importados (Proveedores 11-15)
('P011', 'Johnnie Walker Red Label 750ml', 450.00, 650.00, '2030-05-20', 15.00, 15, 
 2, 'Dólar Estadounidense', 25, 'Botella',
 12, 'Ricardo Antonio Sandoval', 'activo'),
 
('P012', 'Jack Daniels 750ml', 500.00, 720.00, '2031-02-28', 15.00, 12, 
 2, 'Dólar Estadounidense', 25, 'Botella',
 11, 'Andrea Carolina Mejía', 'activo'),
 
('P013', 'Smirnoff Vodka 750ml', 180.00, 260.00, '2029-12-15', 15.00, 25, 
 2, 'Dólar Estadounidense', 25, 'Botella',
 13, 'Gabriela Fernanda Flores', 'activo'),
 
('P014', 'Bacardi Superior 750ml', 200.00, 290.00, '2030-08-22', 15.00, 20, 
 2, 'Dólar Estadounidense', 25, 'Botella',
 14, 'Fernando José Rivas', 'activo'),
 
('P015', 'Jose Cuervo Especial 750ml', 350.00, 500.00, '2030-10-12', 15.00, 18, 
 2, 'Dólar Estadounidense', 25, 'Botella',
 15, 'Laura Marcela González', 'activo'),

-- Vinos (Proveedores 16-20)
('P016', 'Vino Tinto Gato Negro 750ml', 120.00, 180.00, '2026-04-05', 15.00, 40, 
 1, 'Córdoba Oro', 25, 'Botella',
 16, 'Carlos Alberto Juárez', 'activo'),
 
('P017', 'Vino Blanco Casillero del Diablo 750ml', 280.00, 400.00, '2027-09-12', 15.00, 22, 
 1, 'Córdoba Oro', 25, 'Botella',
 17, 'Karla Susana Martínez', 'activo'),
 
('P018', 'Vino Rosé Santa Rita 750ml', 150.00, 220.00, '2026-02-28', 15.00, 30, 
 1, 'Córdoba Oro', 25, 'Botella',
 18, 'Oscar Eduardo Solís', 'activo'),
 
('P019', 'Champagne Freixenet 750ml', 400.00, 580.00, '2028-10-22', 15.00, 10, 
 1, 'Córdoba Oro', 25, 'Botella',
 19, 'Silvia Margarita Ruiz', 'activo'),
 
('P020', 'Vino Espumante Andre 750ml', 90.00, 140.00, '2025-06-30', 15.00, 35, 
 1, 'Córdoba Oro', 25, 'Botella',
 20, 'Alberto Enrique Vargas', 'activo'),

-- Refrescos y Mixers (Proveedores 21-25)
('P021', 'Coca-Cola 3 Litros', 45.00, 65.00, '2025-01-15', 15.00, 80, 
 1, 'Córdoba Oro', 4, 'Litro',
 22, 'Natalia de los Ángeles Peña', 'activo'),
 
('P022', 'Sprite 2.5 Litros', 40.00, 60.00, '2025-02-05', 15.00, 75, 
 1, 'Córdoba Oro', 4, 'Litro',
 21, 'Raúl Antonio Díaz', 'activo'),
 
('P023', 'Fanta Naranja 2 Litros', 38.00, 55.00, '2025-03-10', 15.00, 65, 
 1, 'Córdoba Oro', 4, 'Litro',
 21, 'Raúl Antonio Díaz', 'activo'),
 
('P024', 'Tónica Schweppes 1 Litro', 50.00, 75.00, '2025-04-20', 15.00, 50, 
 1, 'Córdoba Oro', 4, 'Litro',
 24, 'Valeria Cristina Guzmán', 'activo'),
 
('P025', 'Jugo de Naranja Tropicana 1 Litro', 55.00, 80.00, '2025-05-30', 15.00, 45, 
 1, 'Córdoba Oro', 4, 'Litro',
 25, 'Esteban Rafael Castro', 'activo'),

-- Snacks y Aperitivos (Proveedores 26-30)
('P026', 'Maní Salado Bolsa 200g', 15.00, 25.00, '2025-08-05', 15.00, 120, 
 1, 'Córdoba Oro', 12, 'Gramo',
 26, 'Jimena Isabel López', 'activo'),
 
('P027', 'Papas Fritas Lays Bolsa 150g', 18.00, 30.00, '2025-09-15', 15.00, 100, 
 1, 'Córdoba Oro', 12, 'Gramo',
 27, 'Jorge Luis Mejía', 'activo'),
 
('P028', 'Queso Seco Libra', 60.00, 90.00, '2025-07-10', 15.00, 40, 
 1, 'Córdoba Oro', 15, 'Libra',
 28, 'Diana Carolina Rojas', 'activo'),
 
('P029', 'Chicharrones Libra', 55.00, 85.00, '2025-08-25', 15.00, 35, 
 1, 'Córdoba Oro', 15, 'Libra',
 29, 'Mario Ernesto Salgado', 'activo'),
 
('P030', 'Tostadas Pack 10 unidades', 20.00, 35.00, '2025-06-18', 15.00, 60, 
 1, 'Córdoba Oro', 19, 'Docena',
 30, 'Ricardo José Mendoza', 'activo');

-- Para ADMINISTRADOR: asigna todos los permisos
INSERT INTO permisos_usuario (id_usuario, id_permiso)
SELECT u.id, p.id
FROM usuarios u
CROSS JOIN paginas_projectos p
WHERE u.rol = 'ADMINISTRADOR';

-- Para VENTAS: asigna solo permisos específicos
INSERT INTO permisos_usuario (id_usuario, id_permiso)
SELECT u.id, p.id
FROM usuarios u
JOIN paginas_projectos p
    ON p.pagina IN (
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
    )
WHERE u.rol = 'VENTAS';











