<?php
session_start();
require_once '../config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$noticia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($noticia_id === 0) {
    header('Location: admin.php');
    exit();
}

// Obtener noticia
$sql = "SELECT * FROM noticias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $noticia_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: admin.php');
    exit();
}

$noticia = $result->fetch_assoc();

// Obtener bloques
$sql = "SELECT * FROM bloques WHERE noticia_id = ? ORDER BY orden ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $noticia_id);
$stmt->execute();
$bloques = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?> - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .ver-noticia-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .noticia-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }

        .noticia-header h1 {
            color: #333;
            font-size: 32px;
            margin: 0 0 10px 0;
        }

        .noticia-meta {
            color: #666;
            font-size: 14px;
        }

        .noticia-body {
            line-height: 1.8;
            color: #555;
        }

        .bloque-imagen {
            margin: 20px 0;
        }

        .bloque-imagen img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bloque-parrafo {
            margin: 20px 0;
            text-align: justify;
        }

        .back-link {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="ver-noticia-container">
        <div class="noticia-header">
            <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
            <div class="noticia-meta">
                <i class="fas fa-calendar"></i> Publicado: <?php echo date('d \d\e F \d\e Y', strtotime($noticia['fecha_creacion'])); ?>
                <?php if ($noticia['fecha_actualizacion'] !== $noticia['fecha_creacion']): ?>
                    <br><i class="fas fa-sync"></i> Actualizado: <?php echo date('d \d\e F \d\e Y', strtotime($noticia['fecha_actualizacion'])); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="noticia-body">
            <?php
            if ($bloques->num_rows > 0) {
                while ($bloque = $bloques->fetch_assoc()) {
                    if ($bloque['tipo'] === 'imagen') {
                        ?>
                        <div class="bloque-imagen">
                            <img src="../<?php echo htmlspecialchars($bloque['contenido']); ?>" alt="Imagen noticia">
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="bloque-parrafo">
                            <?php echo nl2br(htmlspecialchars($bloque['contenido'])); ?>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>

        <div class="back-link">
            <a href="admin.php"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>
    </div>
</body>
</html>
