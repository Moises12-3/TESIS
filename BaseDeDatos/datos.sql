
INSERT INTO productos (codigo, nombre, compra, venta, fecha_vencimiento, iva, existencia, idMoneda) VALUES
('P001', 'Leche Entera', 0.80, 1.20, '2025-06-30', 0.15, 150, 1),
('P002', 'Arroz Blanco', 0.50, 0.75, NULL, 0.15, 200, 1),
('P003', 'Café Molido', 2.50, 3.50, '2026-01-15', 0.15, 80, 1),
('P004', 'Aceite Vegetal', 1.20, 1.80, '2025-12-10', 0.15, 90, 1),
('P005', 'Azúcar Refinada', 0.45, 0.70, NULL, 0.15, 120, 1),
('P006', 'Harina de Trigo', 0.60, 0.90, NULL, 0.15, 130, 1),
('P007', 'Sal de Mesa', 0.20, 0.40, NULL, 0.15, 250, 1),
('P008', 'Jabón de Baño', 0.70, 1.00, NULL, 0.15, 100, 1),
('P009', 'Pasta Dental', 1.00, 1.50, NULL, 0.15, 95, 1),
('P010', 'Shampoo 400ml', 2.20, 3.00, NULL, 0.15, 70, 1),
('P011', 'Papel Higiénico 4pzs', 1.50, 2.30, NULL, 0.15, 110, 1),
('P012', 'Desodorante', 1.80, 2.50, NULL, 0.15, 85, 1),
('P013', 'Galletas Dulces', 0.90, 1.30, '2025-09-20', 0.15, 140, 1),
('P014', 'Refresco en Polvo', 0.30, 0.60, '2025-10-05', 0.15, 160, 1),
('P015', 'Agua Embotellada 600ml', 0.40, 0.70, NULL, 0.15, 200, 1),
('P016', 'Cerveza Lata', 0.80, 1.50, '2025-11-01', 0.15, 90, 1),
('P017', 'Yogur Natural', 1.00, 1.60, '2025-08-30', 0.15, 60, 1),
('P018', 'Pan Blanco', 0.50, 0.90, '2025-05-01', 0.15, 75, 1),
('P019', 'Salsa de Tomate', 0.70, 1.10, '2025-12-25', 0.15, 100, 1),
('P020', 'Mayonesa 250g', 0.90, 1.40, '2025-10-15', 0.15, 85, 1),
('P021', 'Mantequilla 200g', 1.10, 1.80, '2025-07-10', 0.15, 60, 1),
('P022', 'Frijoles Rojos', 0.55, 0.85, NULL, 0.15, 180, 1),
('P023', 'Atún en Lata', 1.20, 1.90, '2027-02-15', 0.15, 75, 1);

INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, descuento, rol, email, password, foto_perfil) VALUES
('jdoe', 'Juan Doe', '1234567890123', '123-456-7890', 'Calle Ficticia 123, Managua', 10.00, 'usuario', 'jdoe@example.com', 'password123', NULL),
('mgarcia', 'Maria Garcia', '9876543210987', '321-654-0987', 'Avenida Central 456, León', 15.00, 'admin', 'mgarcia@example.com', 'adminpass456', NULL),
('lsanchez', 'Luis Sánchez', '4567891234567', '555-555-5555', 'Calle Real 789, Chinandega', 5.00, 'editor', 'lsanchez@example.com', 'editorpass789', NULL),
('mperez', 'Maria Pérez', '6543219876543', '777-777-7777', 'Avenida Libertad 101, Estelí', 12.00, 'usuario', 'mperez@example.com', 'password234', NULL),
('cpineda', 'Carlos Pineda', '2135468790321', '888-888-8888', 'Callejón del Sol 202, Masaya', 8.50, 'editor', 'cpineda@example.com', 'editorpass012', NULL),
('osolis', 'Oscar Solís', '8765432109876', '999-999-9999', 'Zona 1, Carretera Norte, Managua', 20.00, 'admin', 'osolis@example.com', 'adminpass789', NULL),
('lmartinez', 'Laura Martínez', '4321098765432', '111-222-3333', 'Calle Madero 303, Granada', 7.50, 'usuario', 'lmartinez@example.com', 'password345', NULL),
('rgarcia', 'Ricardo García', '3216549876543', '444-444-4444', 'Calle Las Palmas 404, Rivas', 10.00, 'editor', 'rgarcia@example.com', 'editorpass567', NULL),
('nlopez', 'Nina López', '7654321098765', '555-666-7777', 'Avenida José Dolores 505, Boaco', 18.00, 'admin', 'nlopez@example.com', 'adminpass234', NULL),
('jramirez', 'José Ramírez', '1237894561237', '666-777-8888', 'Calle Real 606, Jinotega', 6.00, 'usuario', 'jramirez@example.com', 'password456', NULL),
('aperez', 'Ana Pérez', '7894561237894', '777-888-9999', 'Avenida Nueva 707, Ocotal', 10.50, 'editor', 'aperez@example.com', 'editorpass890', NULL),
('bvalle', 'Beatriz Valle', '8901234567890', '999-000-1111', 'Calle San Juan 808, Carazo', 5.00, 'usuario', 'bvalle@example.com', 'password567', NULL),
('fhernandez', 'Felipe Hernández', '2345678901234', '222-333-4444', 'Avenida Norte 909, Chontales', 12.00, 'admin', 'fhernandez@example.com', 'adminpass567', NULL),
('vmoncada', 'Verónica Moncada', '5678901234567', '333-444-5555', 'Calle Independencia 1010, Masaya', 10.00, 'usuario', 'vmoncada@example.com', 'password678', NULL),
('cmorales', 'Carlos Morales', '8765432102345', '444-555-6666', 'Calle Los Pinos 1111, León', 15.00, 'editor', 'cmorales@example.com', 'editorpass345', NULL),
('pmoreno', 'Pablo Moreno', '2345678909876', '555-666-7777', 'Avenida del Sol 1212, Estelí', 7.00, 'usuario', 'pmoreno@example.com', 'password789', NULL),
('jvargas', 'Julio Vargas', '5432109876543', '666-777-8888', 'Calle Las Rosas 1313, Managua', 9.00, 'admin', 'jvargas@example.com', 'adminpass890', NULL),
('nmorales', 'Nayeli Morales', '6543210987654', '777-888-9999', 'Avenida Central 1414, Chinandega', 6.00, 'usuario', 'nmorales@example.com', 'password234', NULL),
('marcelo', 'Marcelo López', '3216549876543', '555-444-3333', 'Calle Santa Teresa 1515, Boaco', 8.00, 'editor', 'marcelo@example.com', 'editorpass678', NULL),
('mflores', 'Marta Flores', '7894561236547', '444-555-6666', 'Avenida Los Robles 1616, Jinotega', 11.00, 'usuario', 'mflores@example.com', 'password345', NULL),
('srojas', 'Samuel Rojas', '8901234567891', '999-888-7777', 'Calle Los Alpes 1717, Carazo', 10.00, 'admin', 'srojas@example.com', 'adminpass012', NULL);

INSERT INTO clientes (nombre, cedula, telefono, direccion, descuento) VALUES
('Juan Pérez', '1234567890123', '123-456-7890', 'Calle Ficticia 123, Managua', 10.00),
('Maria García', '9876543210987', '321-654-0987', 'Avenida Central 456, León', 15.00),
('Luis Sánchez', '4567891234567', '555-555-5555', 'Calle Real 789, Chinandega', 5.00),
('Carlos Pineda', '2135468790321', '888-888-8888', 'Callejón del Sol 202, Masaya', 8.50),
('Oscar Solís', '8765432109876', '999-999-9999', 'Zona 1, Carretera Norte, Managua', 20.00),
('Laura Martínez', '4321098765432', '111-222-3333', 'Calle Madero 303, Granada', 7.50),
('Ricardo García', '3216549876543', '444-444-4444', 'Calle Las Palmas 404, Rivas', 10.00),
('Nina López', '7654321098765', '555-666-7777', 'Avenida José Dolores 505, Boaco', 18.00),
('José Ramírez', '1237894561237', '666-777-8888', 'Calle Real 606, Jinotega', 6.00),
('Ana Pérez', '7894561237894', '777-888-9999', 'Avenida Nueva 707, Ocotal', 10.50),
('Beatriz Valle', '8901234567890', '999-000-1111', 'Calle San Juan 808, Carazo', 5.00),
('Felipe Hernández', '2345678901234', '222-333-4444', 'Avenida Norte 909, Chontales', 12.00),
('Verónica Moncada', '5678901234567', '333-444-5555', 'Calle Independencia 1010, Masaya', 10.00),
('Carlos Morales', '8765432102345', '444-555-6666', 'Calle Los Pinos 1111, León', 15.00),
('Pablo Moreno', '2345678909876', '555-666-7777', 'Avenida del Sol 1212, Estelí', 7.00),
('Julio Vargas', '5432109876543', '666-777-8888', 'Calle Las Rosas 1313, Managua', 9.00),
('Nayeli Morales', '6543210987654', '777-888-9999', 'Avenida Central 1414, Chinandega', 6.00),
('Marcelo López', '3216549876543', '555-444-3333', 'Calle Santa Teresa 1515, Boaco', 8.00),
('Marta Flores', '7894561236547', '444-555-6666', 'Avenida Los Robles 1616, Jinotega', 11.00),
('Samuel Rojas', '8901234567891', '999-888-7777', 'Calle Los Alpes 1717, Carazo', 10.00),
('Antonio López', '4561237894562', '222-333-4445', 'Avenida San Juan 1818, León', 7.00),
('Pedro Pérez', '6541239876543', '333-444-5556', 'Calle Sol 1919, Estelí', 5.00),
('Patricia Gómez', '5678904321234', '444-555-6667', 'Avenida La Paz 2020, Jinotega', 6.00);








