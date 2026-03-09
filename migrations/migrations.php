<?php
/**
 * Script de Migración - El Tiempo de Hidalgo
 * Este script crea la estructura completa de la base de datos
 * 
 * INSTRUCCIONES DE USO:
 * 1. Asegúrate de que MySQL está corriendo
 * 2. Accede a: http://localhost/tiempo_hidalgo/migrations/migrations.php
 * 3. El script creará automáticamente toda la BD
 */

// Configuración de conexión
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tiempo_hidalgo');

// Variables para almacenar resultados
$mensajes = array();
$errores = array();

// Conectar a MySQL sin base de datos especificada
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// ============================================
// 1. CREAR BASE DE DATOS
// ============================================
$sql_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($conn->query($sql_db) === TRUE) {
    $mensajes[] = "✅ Base de datos '" . DB_NAME . "' creada correctamente";
} else {
    $errores[] = "❌ Error al crear base de datos: " . $conn->error;
    mostrar_resultados($mensajes, $errores);
    exit();
}

// Seleccionar la base de datos
if (!$conn->select_db(DB_NAME)) {
    $errores[] = "❌ Error al seleccionar base de datos: " . $conn->error;
    mostrar_resultados($mensajes, $errores);
    exit();
}

$mensajes[] = "✅ Base de datos seleccionada: " . DB_NAME;

// ============================================
// 2. CREAR TABLA DE USUARIOS
// ============================================
$sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_usuarios) === TRUE) {
    $mensajes[] = "✅ Tabla 'usuarios' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla usuarios: " . $conn->error;
}

// ============================================
// 3. CREAR TABLA DE NOTICIAS
// ============================================
$sql_noticias = "CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    estado ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
    autor_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_publicacion TIMESTAMP NULL,
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_estado (estado),
    INDEX idx_autor_id (autor_id),
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_noticias) === TRUE) {
    $mensajes[] = "✅ Tabla 'noticias' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla noticias: " . $conn->error;
}

// ============================================
// 4. CREAR TABLA DE BLOQUES
// ============================================
$sql_bloques = "CREATE TABLE IF NOT EXISTS bloques (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_bloques) === TRUE) {
    $mensajes[] = "✅ Tabla 'bloques' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla bloques: " . $conn->error;
}

// ============================================
// 5. CREAR TABLA DE GALERÍA (Opcional)
// ============================================
$sql_galeria = "CREATE TABLE IF NOT EXISTS galeria (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_galeria) === TRUE) {
    $mensajes[] = "✅ Tabla 'galeria' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla galeria: " . $conn->error;
}

// ============================================
// 6. CREAR TABLA DE CATEGORÍAS (Opcional)
// ============================================
$sql_categorias = "CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    slug VARCHAR(100) UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_categorias) === TRUE) {
    $mensajes[] = "✅ Tabla 'categorias' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla categorias: " . $conn->error;
}

// ============================================
// 7. CREAR TABLA DE RELACIÓN NOTICIA-CATEGORÍA
// ============================================
$sql_noticia_categoria = "CREATE TABLE IF NOT EXISTS noticia_categoria (
    noticia_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (noticia_id, categoria_id),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_noticia_categoria) === TRUE) {
    $mensajes[] = "✅ Tabla 'noticia_categoria' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla noticia_categoria: " . $conn->error;
}

// ============================================
// 8. CREAR TABLA DE CONTACTOS
// ============================================
$sql_contactos = "CREATE TABLE IF NOT EXISTS contactos (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_contactos) === TRUE) {
    $mensajes[] = "✅ Tabla 'contactos' creada correctamente";
} else {
    $errores[] = "❌ Error al crear tabla contactos: " . $conn->error;
}

// ============================================
// 9. INSERTAR USUARIO ADMIN POR DEFECTO
// ============================================
$usuario_admin = 'adminb';
$password_admin = '123456';
$password_hash = md5($password_admin);

// Verificar si el usuario ya existe
$sql_check = "SELECT id FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param('s', $usuario_admin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $sql_insert = "INSERT INTO usuarios (usuario, contrasena, email, estado) 
                   VALUES (?, ?, ?, 'activo')";
    $stmt = $conn->prepare($sql_insert);
    $email_admin = 'admin@tiempoHidalgo.local';
    $stmt->bind_param('sss', $usuario_admin, $password_hash, $email_admin);
    
    if ($stmt->execute()) {
        $mensajes[] = "✅ Usuario administrador creado: adminb / 123456";
    } else {
        $errores[] = "❌ Error al crear usuario admin: " . $conn->error;
    }
} else {
    $mensajes[] = "ℹ️  Usuario administrador ya existe";
}

// ============================================
// 10. CREAR CARPETAS NECESARIAS
// ============================================
$carpetas = array(
    '../uploads',
    '../uploads/noticias',
    '../uploads/galeria',
    '../assets/images'
);

foreach ($carpetas as $carpeta) {
    $ruta_completa = __DIR__ . '/' . $carpeta;
    if (!is_dir($ruta_completa)) {
        if (mkdir($ruta_completa, 0777, true)) {
            $mensajes[] = "✅ Carpeta creada: " . $carpeta;
        } else {
            $errores[] = "❌ Error al crear carpeta: " . $carpeta;
        }
    } else {
        $mensajes[] = "ℹ️  Carpeta ya existe: " . $carpeta;
    }
}

// Cerrar conexión
$conn->close();

// ============================================
// MOSTRAR RESULTADOS
// ============================================
mostrar_resultados($mensajes, $errores);

/**
 * Función para mostrar los resultados
 */
function mostrar_resultados($mensajes, $errores) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Migración - El Tiempo de Hidalgo</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .container {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 700px;
                width: 100%;
                padding: 40px;
            }

            .header {
                text-align: center;
                margin-bottom: 40px;
                border-bottom: 3px solid #667eea;
                padding-bottom: 20px;
            }

            .header h1 {
                color: #333;
                font-size: 32px;
                margin-bottom: 10px;
            }

            .header p {
                color: #666;
                font-size: 14px;
            }

            .resultado {
                margin-bottom: 15px;
                padding: 12px 15px;
                border-radius: 5px;
                border-left: 4px solid;
                font-size: 14px;
                line-height: 1.5;
            }

            .resultado.exito {
                background-color: #d4edda;
                color: #155724;
                border-left-color: #28a745;
            }

            .resultado.error {
                background-color: #f8d7da;
                color: #721c24;
                border-left-color: #dc3545;
            }

            .resultado.info {
                background-color: #d1ecf1;
                color: #0c5460;
                border-left-color: #17a2b8;
            }

            .sección {
                margin-top: 30px;
            }

            .sección-título {
                font-weight: bold;
                color: #333;
                margin: 20px 0 15px 0;
                padding: 10px 0;
                border-bottom: 2px solid #eee;
                font-size: 14px;
                text-transform: uppercase;
            }

            .resumen {
                background: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                margin-top: 30px;
                text-align: center;
                border: 2px solid #667eea;
            }

            .resumen h3 {
                color: #667eea;
                margin-bottom: 10px;
                font-size: 18px;
            }

            .resumen p {
                color: #666;
                margin: 5px 0;
                font-size: 14px;
            }

            .btn-container {
                text-align: center;
                margin-top: 30px;
            }

            .btn {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 30px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                transition: transform 0.3s;
                border: none;
                cursor: pointer;
                margin: 0 10px;
            }

            .btn:hover {
                transform: translateY(-2px);
            }

            .btn.secondary {
                background: #666;
                margin-top: 10px;
                display: block;
                width: 100%;
            }

            .tabla-info {
                width: 100%;
                margin-top: 20px;
                background: white;
            }

            .tabla-info table {
                width: 100%;
                border-collapse: collapse;
            }

            .tabla-info th,
            .tabla-info td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #eee;
                font-size: 13px;
            }

            .tabla-info th {
                background: #f5f5f5;
                font-weight: bold;
                color: #333;
            }

            .tabla-info tr:hover {
                background: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🚀 Migración de Base de Datos</h1>
                <p>El Tiempo de Hidalgo - Setup Inicial</p>
            </div>

            <!-- Mensajes de Éxito -->
            <?php if (!empty($mensajes)): ?>
                <div class="sección">
                    <div class="sección-título">✅ Operaciones Exitosas (<?php echo count($mensajes); ?>)</div>
                    <?php foreach ($mensajes as $mensaje): ?>
                        <div class="resultado exito"><?php echo htmlspecialchars($mensaje); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Mensajes de Error -->
            <?php if (!empty($errores)): ?>
                <div class="sección">
                    <div class="sección-título">❌ Errores (<?php echo count($errores); ?>)</div>
                    <?php foreach ($errores as $error): ?>
                        <div class="resultado error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Información de la Base de Datos -->
            <div class="resumen">
                <h3>📊 Base de Datos Creada</h3>
                <p><strong>Nombre:</strong> <?php echo DB_NAME; ?></p>
                <p><strong>Usuario Admin:</strong> adminb</p>
                <p><strong>Contraseña Admin:</strong> 123456</p>
                <p style="margin-top: 10px; color: #999; font-size: 12px;">⚠️ Cambia la contraseña después de tu primer login</p>
            </div>

            <!-- Tabla de Tablas Creadas -->
            <div class="tabla-info">
                <h3 style="margin-bottom: 15px; color: #333;">📋 Tablas de la Base de Datos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tabla</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>usuarios</strong></td>
                            <td>Gestión de acceso administrativo</td>
                        </tr>
                        <tr>
                            <td><strong>noticias</strong></td>
                            <td>Almacenamiento de noticias y reportajes</td>
                        </tr>
                        <tr>
                            <td><strong>bloques</strong></td>
                            <td>Contenido flexible (imágenes y párrafos)</td>
                        </tr>
                        <tr>
                            <td><strong>galeria</strong></td>
                            <td>Galería de imágenes</td>
                        </tr>
                        <tr>
                            <td><strong>categorias</strong></td>
                            <td>Categorización de noticias</td>
                        </tr>
                        <tr>
                            <td><strong>noticia_categoria</strong></td>
                            <td>Relación noticia-categoría</td>
                        </tr>
                        <tr>
                            <td><strong>contactos</strong></td>
                            <td>Formulario de contacto</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Botones de Acción -->
            <div class="btn-container">
                <a href="../index.php" class="btn">🏠 Ir al Inicio</a>
                <a href="../files/login.php" class="btn">🔐 Panel de Login</a>
            </div>

            <button onclick="location.href='migrations.php'" class="btn secondary">🔄 Ejecutar Migración Nuevamente</button>
        </div>
    </body>
    </html>
    <?php
}
?>
