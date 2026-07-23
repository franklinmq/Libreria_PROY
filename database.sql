-- Base de datos: Sistema de Inventario Multipropósito
CREATE DATABASE IF NOT EXISTS libreria_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libreria_inventario;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT NULL,
    categoria_id INT NULL,
    precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    marca_id INT NULL,
    imagen VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (marca_id) REFERENCES marcas(id) ON DELETE SET NULL
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

INSERT INTO categorias (nombre, parent_id) VALUES
    ('Material Escolar', NULL), 
    ('Material de Escritorio', NULL), 
    ('Adornos de Carnaval', NULL), 
    ('Libros', NULL), 
    ('Regalos', NULL),
    ('Cuadernos', 1),
    ('Bolígrafos', 2);

INSERT INTO marcas (nombre) VALUES
    ('Faber-Castell'),
    ('BIC'),
    ('Rey Momo'),
    ('Sudamericana'),
    ('Report');

INSERT INTO productos (nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, marca_id) VALUES
    ('Cuaderno Espiral 100 hojas', 'Cuaderno tamaño carta cuadriculado', 6, 10.00, 15.00, 50, 1),
    ('Bolígrafo Azul', 'Caja de 50 bolígrafos trazo fino', 7, 45.00, 60.00, 10, 2),
    ('Espuma de Carnaval', 'Lata grande 500ml', 3, 8.00, 12.00, 100, 3),
    ('Cien años de soledad', 'Libro edición especial', 4, 150.00, 220.00, 5, 4),
    ('Papel Bond Resma', 'Resma de 500 hojas tamaño carta', 2, 30.00, 40.00, 20, 5);
