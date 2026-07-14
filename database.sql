-- Base de datos: Sistema de Inventario Multipropósito
CREATE DATABASE IF NOT EXISTS libreria_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libreria_inventario;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_barras VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT NULL,
    categoria_id INT NULL,
    precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    marca VARCHAR(150) NULL,
    imagen VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos de ejemplo
INSERT INTO usuarios (nombre, email, password) VALUES
    ('Administrador', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

INSERT INTO categorias (nombre) VALUES
    ('Material Escolar'), 
    ('Material de Escritorio'), 
    ('Adornos de Carnaval'), 
    ('Libros'), 
    ('Regalos');

INSERT INTO productos (codigo_barras, nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, marca) VALUES
    ('7701234567890', 'Cuaderno Espiral 100 hojas', 'Cuaderno tamaño carta cuadriculado', 1, 10.00, 15.00, 50, 'Faber-Castell'),
    ('7701234567891', 'Bolígrafo Azul', 'Caja de 50 bolígrafos trazo fino', 2, 45.00, 60.00, 10, 'BIC'),
    ('7701234567892', 'Espuma de Carnaval', 'Lata grande 500ml', 3, 8.00, 12.00, 100, 'Rey Momo'),
    ('7701234567893', 'Cien años de soledad', 'Libro edición especial', 4, 150.00, 220.00, 5, 'Sudamericana'),
    ('7701234567894', 'Papel Bond Resma', 'Resma de 500 hojas tamaño carta', 2, 30.00, 40.00, 20, 'Report');
