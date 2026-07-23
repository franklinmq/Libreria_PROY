USE libreria_inventario;

CREATE TABLE marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

-- Insertar marcas existentes desde los productos
INSERT IGNORE INTO marcas (nombre) 
SELECT DISTINCT marca FROM productos WHERE marca IS NOT NULL AND marca != '';

-- Actualizar tabla categorias
ALTER TABLE categorias ADD COLUMN parent_id INT NULL;
ALTER TABLE categorias ADD FOREIGN KEY (parent_id) REFERENCES categorias(id) ON DELETE SET NULL;

-- Actualizar tabla productos para relacionar con marcas
ALTER TABLE productos ADD COLUMN marca_id INT NULL;

-- Vincular productos con los IDs de las marcas
UPDATE productos p 
JOIN marcas m ON p.marca = m.nombre 
SET p.marca_id = m.id;

-- Eliminar la columna de texto antigua
ALTER TABLE productos DROP COLUMN marca;

-- Añadir llave foránea
ALTER TABLE productos ADD FOREIGN KEY (marca_id) REFERENCES marcas(id) ON DELETE SET NULL;
