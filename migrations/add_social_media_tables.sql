-- =====================================================
-- MIGRACIÓN: Agregar soporte para Redes Sociales
-- El Tiempo de Hidalgo - v2.6
-- =====================================================

USE tiempo_hidalgo;

-- =====================================================
-- 1. AGREGAR COLUMNAS A TABLA NOTICIAS
-- =====================================================

ALTER TABLE noticias ADD COLUMN IF NOT EXISTS publicado_redes BOOLEAN DEFAULT false AFTER fecha_publicacion;
ALTER TABLE noticias ADD COLUMN IF NOT EXISTS fecha_publicacion_redes TIMESTAMP NULL AFTER publicado_redes;

-- =====================================================
-- 2. CREAR TABLA DE PUBLICACIONES EN REDES SOCIALES
-- =====================================================

CREATE TABLE IF NOT EXISTS social_media_publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    red_social ENUM('facebook', 'instagram', 'twitter', 'linkedin') NOT NULL,
    post_id VARCHAR(255),
    url_publicacion VARCHAR(500),
    exitoso BOOLEAN DEFAULT true,
    estado ENUM('publicado', 'fallido', 'revisión') DEFAULT 'publicado',
    mensaje_error TEXT,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_noticia_id (noticia_id),
    INDEX idx_red_social (red_social),
    INDEX idx_exitoso (exitoso),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. CREAR TABLA DE CREDENCIALES DE REDES SOCIALES
-- =====================================================

CREATE TABLE IF NOT EXISTS social_media_credenciales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    red_social ENUM('facebook', 'instagram', 'twitter', 'linkedin') NOT NULL UNIQUE,
    access_token VARCHAR(1000),
    page_id VARCHAR(255),
    account_id VARCHAR(255),
    bearer_token VARCHAR(1000),
    refresh_token VARCHAR(1000),
    fecha_expiracion TIMESTAMP NULL,
    activo BOOLEAN DEFAULT false,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_red_social (red_social),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. REGISTROS INICIALES DE REDES SOCIALES
-- =====================================================

INSERT IGNORE INTO social_media_credenciales (red_social, activo) VALUES 
('facebook', false),
('instagram', false),
('twitter', false),
('linkedin', false);

-- =====================================================
-- NOTAS:
-- - Las credenciales se pueden actualizar desde un panel de introducción
-- - Los registros de publicaciones ayudan a auditar qué se publicó y cuándo
-- - Los tokens se deben almacenar de forma segura (encriptados en producción)
-- =====================================================
