<?php
/**
 * CONFIGURACIÓN - El Tiempo de Hidalgo
 * 
 * IMPORTANTE: Ejecuta las migraciones primero en:
 * http://localhost/tiempo_hidalgo/migrations/migrations.php
 */

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// CONFIGURACIÓN DE ZONA HORARIA
// ============================================
date_default_timezone_set('America/Mexico_City');

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tiempo_hidalgo');

// Opciones de charset
define('DB_CHARSET', 'utf8mb4');

// ============================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ============================================
define('APP_NAME', 'El Tiempo de Hidalgo');
define('APP_URL', 'http://localhost/tiempo_hidalgo');
define('UPLOADS_PATH', __DIR__ . '/uploads');
define('UPLOADS_URL', APP_URL . '/uploads');

// ============================================
// CONECTAR A LA BASE DE DATOS
// ============================================
try {
    // Crear conexión con mysqli
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar charset
    if (!$conn->set_charset(DB_CHARSET)) {
        throw new Exception("Error al establecer charset: " . $conn->error);
    }
    
    // Configurar zona horaria para MySQL (México - Hidalgo: UTC-6)
    $conn->query("SET time_zone = '-06:00'");

    // Compatibilidad: asegurar columna de contador de vistas en noticias
    $conn->query("ALTER TABLE noticias ADD COLUMN IF NOT EXISTS vistas INT NOT NULL DEFAULT 0");
    
    // Verificar que la base de datos está creada
    $result = $conn->query("SELECT DATABASE()");
    if (!$result || $result->num_rows === 0) {
        throw new Exception("Base de datos no disponible. Ejecuta: http://localhost/tiempo_hidalgo/migrations/migrations.php");
    }
    
} catch (Exception $e) {
    // En caso de error, mostrar mensaje de ayuda
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error de Conexión</title>
        <style>
            body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
            .container { background: white; padding: 40px; border-radius: 10px; max-width: 600px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
            .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
            .title { color: #333; margin: 20px 0; }
            .code { background: #f9f9f9; padding: 15px; border-left: 4px solid #667eea; margin: 15px 0; }
            a { color: #667eea; text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="title">⚠️ Error de Conexión a Base de Datos</h1>
            <div class="error">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <p>Para resolver este problema:</p>
            <ol>
                <li>Asegúrate de que <strong>XAMPP</strong> está corriendo (Apache + MySQL)</li>
                <li>Accede a <a href="http://localhost/tiempo_hidalgo/migrations/migrations.php" target="_blank">Ejecutar Migraciones</a></li>
                <li>Esto creará automáticamente la base de datos y todas las tablas</li>
            </ol>
            <div class="code">
                <strong>URL:</strong> http://localhost/tiempo_hidalgo/migrations/migrations.php
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// ============================================
// FUNCIONES AUXILIARES
// ============================================

/**
 * Escapa caracteres especiales para evitar inyección SQL
 */
function escape_string($string) {
    global $conn;
    return $conn->real_escape_string($string);
}

/**
 * Prepara una consulta parametrizada
 */
function prepare_query($sql) {
    global $conn;
    return $conn->prepare($sql);
}

/**
 * Ejecuta una consulta y retorna resultado
 */
function execute_query($sql) {
    global $conn;
    return $conn->query($sql);
}

/**
 * Obtiene el último ID insertado
 */
function get_last_id() {
    global $conn;
    return $conn->insert_id;
}

/**
 * Obtiene el número de filas afectadas
 */
function get_affected_rows() {
    global $conn;
    return $conn->affected_rows;
}

// ============================================
// CREAR CARPETAS SI NO EXISTEN
// ============================================
$carpetas_necesarias = array(
    UPLOADS_PATH,
    UPLOADS_PATH . '/noticias',
    UPLOADS_PATH . '/galeria'
);

foreach ($carpetas_necesarias as $carpeta) {
    if (!is_dir($carpeta)) {
        @mkdir($carpeta, 0777, true);
    }
}

?>
