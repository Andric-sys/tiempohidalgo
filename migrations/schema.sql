-- =====================================================
-- SCRIPT SQL - El Tiempo de Hidalgo
-- Base de Datos Completa
-- =====================================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS tiempo_hidalgo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tiempo_hidalgo;

-- =====================================================
-- 1. TABLA DE USUARIOS
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. TABLA DE NOTICIAS
-- =====================================================
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    estado ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
    autor_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_publicacion TIMESTAMP NULL,
    vistas INT NOT NULL DEFAULT 0,
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_vistas (vistas),
    INDEX idx_estado (estado),
    INDEX idx_autor_id (autor_id),
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. TABLA DE BLOQUES (Imagen + Párrafo Flexible)
-- =====================================================
CREATE TABLE IF NOT EXISTS bloques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    tipo ENUM('imagen', 'parrafo', 'titulo', 'subtitulo') DEFAULT 'parrafo',
    contenido LONGTEXT NOT NULL,
    orden INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_noticia_id (noticia_id),
    INDEX idx_tipo (tipo),
    INDEX idx_orden (orden),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. TABLA DE GALERÍA
-- =====================================================
CREATE TABLE IF NOT EXISTS galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT,
    titulo VARCHAR(255),
    imagen VARCHAR(255) NOT NULL,
    descripcion TEXT,
    orden INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_noticia_id (noticia_id),
    INDEX idx_orden (orden),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. TABLA DE CATEGORÍAS
-- =====================================================
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    slug VARCHAR(100) UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. TABLA DE RELACIÓN NOTICIA-CATEGORÍA
-- =====================================================
CREATE TABLE IF NOT EXISTS noticia_categoria (
    noticia_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (noticia_id, categoria_id),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. TABLA DE CONTACTOS
-- =====================================================
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('nuevo', 'respondido', 'cerrado') DEFAULT 'nuevo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTAR USUARIO ADMIN POR DEFECTO
-- =====================================================
-- Usuario: adminb
-- Contraseña: 123456 (MD5: e807f1fcf82d132f9bb018ca6738a19f)
INSERT INTO usuarios (usuario, contrasena, email, estado) 
VALUES ('adminb', 'e807f1fcf82d132f9bb018ca6738a19f', 'admin@tiempoHidalgo.local', 'activo')
ON DUPLICATE KEY UPDATE id=id;

-- =====================================================
-- DATOS DE EJEMPLO (Opcional)
-- =====================================================

-- Insertar categorías de ejemplo
INSERT INTO categorias (nombre, descripcion, slug) VALUES 
('Local', 'Noticias locales de Hidalgo', 'local'),
('Deportes', 'Cobertura de eventos deportivos', 'deportes'),
('Política', 'Noticias políticas y gubernamentales', 'politica'),
('Cultura', 'Eventos y noticias culturales', 'cultura'),
('Economía', 'Noticias de economía y negocios', 'economia')
ON DUPLICATE KEY UPDATE id=id;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
