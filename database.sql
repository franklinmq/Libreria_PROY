-- Base de datos: Inventario de Librería
CREATE DATABASE IF NOT EXISTS libreria_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libreria_inventario;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(30) NOT NULL UNIQUE,
    categoria_id INT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    editorial VARCHAR(150) NULL,
    imagen VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Datos de ejemplo
INSERT INTO categorias (nombre) VALUES
    ('Ficción'), ('Ciencia'), ('Historia'), ('Infantil'), ('Autoayuda');

INSERT INTO libros (titulo, autor, isbn, categoria_id, precio, stock, editorial) VALUES
    ('Cien años de soledad', 'Gabriel García Márquez', '978-0307474728', 1, 25.50, 12, 'Sudamericana'),
    ('Breve historia del tiempo', 'Stephen Hawking', '978-0553380163', 2, 22.00, 4, 'Bantam'),
    ('Sapiens', 'Yuval Noah Harari', '978-0062316097', 3, 28.75, 8, 'Debate'),
    ('El principito', 'Antoine de Saint-Exupéry', '978-0156012195', 4, 15.00, 20, 'Reynal & Hitchcock'),
    ('Hábitos atómicos', 'James Clear', '978-0735211292', 5, 24.90, 3, 'Avery');
