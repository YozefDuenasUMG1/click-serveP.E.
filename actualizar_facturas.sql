
ALTER TABLE facturas
DROP COLUMN hora,
MODIFY COLUMN fecha DATETIME NOT NULL,
ADD COLUMN items TEXT NOT NULL AFTER total,
ADD COLUMN datos_restaurante TEXT NOT NULL AFTER items,
ADD COLUMN estado ENUM('activa', 'anulada') DEFAULT 'activa' AFTER datos_restaurante,
CHANGE COLUMN iva impuesto DECIMAL(10,2) NOT NULL;

