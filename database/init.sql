SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dinero DECIMAL(10, 2) DEFAULT 500.00,
    alquiler_deuda DECIMAL(10, 2) DEFAULT 0.00,
    dia_actual INT DEFAULT 1,
    creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS catalogo_objetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    valor_base DECIMAL(10, 2) NOT NULL,
    es_ilegal BOOLEAN DEFAULT FALSE,
    rareza ENUM('comun', 'raro', 'epico', 'legendario') DEFAULT 'comun',
    imagen VARCHAR(100) DEFAULT 'Icon14_01.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    objeto_id INT NOT NULL,
    precio_compra_real DECIMAL(10, 2) NOT NULL,
    estado_desgaste INT DEFAULT 100,
    ubicacion ENUM('almacen', 'mostrador', 'vendido') DEFAULT 'almacen',
    CONSTRAINT fk_inventario_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_inventario_objeto FOREIGN KEY (objeto_id) REFERENCES catalogo_objetos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO catalogo_objetos (nombre, descripcion, valor_base, es_ilegal, rareza, imagen) VALUES
('Reloj de bolsillo oxidado', 'Un viejo reloj mecánico que aún hace tic-tac.', 45.00, FALSE, 'comun', 'Icon14_01.png'),
('Cartucho de juego retro', 'Una reliquia de plástico de una consola antigua.', 30.00, FALSE, 'comun', 'Icon14_02.png'),
('Chip de datos militar encriptado', 'Procedente de dudosa procedencia. Los cuerpos de seguridad lo persiguen.', 250.00, TRUE, 'raro', 'Icon14_03.png'),
('Estatua de bronze pequeña', 'Pesada y con inscripciones extrañas.', 120.00, FALSE, 'raro', 'Icon14_04.png'),
('Pistola de plasma modificada', 'Arma de mano alterada ilegalmente en los callejones.', 600.00, TRUE, 'epico', 'Icon14_05.png');