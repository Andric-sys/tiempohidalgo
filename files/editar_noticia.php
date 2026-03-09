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

$mensaje = '';
$tipo_mensaje = '';

// Procesar actualización de título
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_titulo') {
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';

    if (empty($titulo)) {
        $mensaje = 'El título es obligatorio';
        $tipo_mensaje = 'error';
    } else {
        $sql = "UPDATE noticias SET titulo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $titulo, $noticia_id);

        if ($stmt->execute()) {
            $mensaje = 'Título actualizado correctamente';
            $tipo_mensaje = 'exito';
            $noticia['titulo'] = $titulo;
        } else {
            $mensaje = 'Error al actualizar el título';
            $tipo_mensaje = 'error';
        }
    }
}

// Procesar actualización de bloque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_bloque') {
    $bloque_id = isset($_POST['bloque_id']) ? intval($_POST['bloque_id']) : 0;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';

    if ($bloque_id > 0) {
        if ($tipo === 'imagen') {
            if (!empty($_FILES['contenido']['name'])) {
                $sql = "SELECT contenido FROM bloques WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $bloque_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $bloque_actual = $result->fetch_assoc();

                if ($bloque_actual && file_exists('../' . $bloque_actual['contenido'])) {
                    unlink('../' . $bloque_actual['contenido']);
                }

                $upload_dir = '../uploads/noticias/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $nombre_archivo = time() . '_' . basename($_FILES['contenido']['name']);
                $ruta_archivo = $upload_dir . $nombre_archivo;

                if (move_uploaded_file($_FILES['contenido']['tmp_name'], $ruta_archivo)) {
                    $contenido = 'uploads/noticias/' . $nombre_archivo;
                    
                    $sql = "UPDATE bloques SET contenido = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('si', $contenido, $bloque_id);
                    
                    if ($stmt->execute()) {
                        $mensaje = 'Imagen actualizada correctamente';
                        $tipo_mensaje = 'exito';
                    }
                } else {
                    $mensaje = 'Error al subir la imagen';
                    $tipo_mensaje = 'error';
                }
            }
        } else {
            $sql = "UPDATE bloques SET contenido = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $contenido, $bloque_id);

            if ($stmt->execute()) {
                $mensaje = 'Bloque actualizado correctamente';
                $tipo_mensaje = 'exito';
            } else {
                $mensaje = 'Error al actualizar el bloque';
                $tipo_mensaje = 'error';
            }
        }
    }
}

// Procesar eliminación de bloque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar_bloque') {
    $bloque_id = isset($_POST['bloque_id']) ? intval($_POST['bloque_id']) : 0;

    if ($bloque_id > 0) {
        $sql = "SELECT contenido, tipo FROM bloques WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $bloque_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bloque = $result->fetch_assoc();

        if ($bloque && $bloque['tipo'] === 'imagen' && file_exists('../' . $bloque['contenido'])) {
            unlink('../' . $bloque['contenido']);
        }

        $sql = "DELETE FROM bloques WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $bloque_id);

        if ($stmt->execute()) {
            $mensaje = 'Bloque eliminado correctamente';
            $tipo_mensaje = 'exito';
        }
    }
}

// Procesar adición de nuevo bloque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar_bloque') {
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

    if ($tipo === 'imagen') {
        if (!empty($_FILES['contenido']['name'])) {
            $upload_dir = '../uploads/noticias/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $nombre_archivo = time() . '_' . basename($_FILES['contenido']['name']);
            $ruta_archivo = $upload_dir . $nombre_archivo;

            if (move_uploaded_file($_FILES['contenido']['tmp_name'], $ruta_archivo)) {
                $contenido = 'uploads/noticias/' . $nombre_archivo;
                
                $sql = "SELECT MAX(orden) as max_orden FROM bloques WHERE noticia_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $noticia_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $nuevo_orden = ($row['max_orden'] !== null) ? $row['max_orden'] + 1 : 0;

                $sql = "INSERT INTO bloques (noticia_id, tipo, contenido, orden) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('issi', $noticia_id, $tipo, $contenido, $nuevo_orden);

                if ($stmt->execute()) {
                    $mensaje = 'Imagen agregada correctamente';
                    $tipo_mensaje = 'exito';
                }
            } else {
                $mensaje = 'Error al subir la imagen';
                $tipo_mensaje = 'error';
            }
        }
    } else {
        $contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';
        
        if (!empty($contenido)) {
            $sql = "SELECT MAX(orden) as max_orden FROM bloques WHERE noticia_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $noticia_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $nuevo_orden = ($row['max_orden'] !== null) ? $row['max_orden'] + 1 : 0;

            $sql = "INSERT INTO bloques (noticia_id, tipo, contenido, orden) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issi', $noticia_id, $tipo, $contenido, $nuevo_orden);

            if ($stmt->execute()) {
                $mensaje = 'Párrafo agregado correctamente';
                $tipo_mensaje = 'exito';
            }
        }
    }
}

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
    <title>Editar Noticia - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .editar-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border: 1px solid #d0d0d0;
        }
        
        .section h2 {
            border-bottom: 3px solid #000000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-family: 'Georgia', serif;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333333;
        }
        
        input[type="text"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 0;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
            background-color: #ffffff;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus,
        input[type="file"]:focus {
            outline: none;
            border: 1px solid #000000;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        button {
            background-color: #333333;
            color: white;
            padding: 10px 20px;
            border: 1px solid #555555;
            border-radius: 0;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #555555;
        }
        
        .btn-danger {
            background-color: #555555;
            border: 1px solid #777777;
        }
        
        .btn-danger:hover {
            background-color: #777777;
        }
        
        .btn-secondary {
            background-color: #666666;
            border: 1px solid #888888;
        }
        
        .btn-secondary:hover {
            background-color: #888888;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .mensaje {
            padding: 15px;
            border-radius: 0;
            margin-bottom: 20px;
            display: none;
        }
        
        .mensaje.exito {
            background-color: #f0f0f0;
            color: #155724;
            border: 1px solid #cccccc;
            display: block;
        }
        
        .mensaje.error {
            background-color: #f0f0f0;
            color: #721c24;
            border: 1px solid #cccccc;
            display: block;
        }
        
        .bloque-item {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #333333;
            border-radius: 0;
            border: 1px solid #e0e0e0;
            border-left-width: 4px;
        }
        
        .bloque-tipo {
            display: inline-block;
            background: #333333;
            color: white;
            padding: 4px 8px;
            border-radius: 0;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .bloque-tipo.imagen {
            background: #666666;
        }
        
        .bloque-preview {
            margin-bottom: 10px;
        }
        
        .bloque-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 0;
        }
        
        .bloque-preview p {
            color: #666666;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .bloque-acciones {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        
        .bloque-editar {
            display: none;
            background: white;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #cccccc;
            border-radius: 0;
        }
        
        .bloque-editar.activo {
            display: block;
        }
        
        .agregar-bloque-form {
            display: none;
            background: #f0f0f0;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        
        .agregar-bloque-form.activo {
            display: block;
        }
        
        .agregar-acciones {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0275d8;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        small {
            display: block;
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include_once '../header.php'; ?>

    <div class="editar-container">
        <a href="admin.php?tab=noticias" class="back-link">← Volver a Noticias</a>

        <?php if ($tipo_mensaje && $mensaje): ?>
            <div class="mensaje <?php echo htmlspecialchars($tipo_mensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <!-- Sección: Editar Título -->
        <div class="section">
            <h2>Editar Título</h2>
            <form method="POST">
                <input type="hidden" name="accion" value="actualizar_titulo">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
                </div>
                <button type="submit">Actualizar Título</button>
            </form>
        </div>

        <!-- Sección: Bloques Existentes -->
        <div class="section">
            <h2>Contenido (Bloques)</h2>
            
            <?php if ($bloques->num_rows > 0): ?>
                <?php $bloque_num = 1; while ($bloque = $bloques->fetch_assoc()): ?>
                    <div class="bloque-item">
                        <div class="bloque-tipo <?php echo $bloque['tipo']; ?>">
                            <?php echo $bloque['tipo'] === 'imagen' ? 'Imagen' : 'Párrafo'; ?> #<?php echo $bloque_num; ?>
                        </div>
                        
                        <!-- Vista Previa -->
                        <div class="bloque-preview">
                            <?php if ($bloque['tipo'] === 'imagen'): ?>
                                <img src="../<?php echo htmlspecialchars($bloque['contenido']); ?>" alt="Imagen bloque">
                            <?php else: ?>
                                <p><?php echo htmlspecialchars($bloque['contenido']); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="bloque-acciones">
                            <button type="button" class="btn-secondary btn-small" onclick="toggleEditarBloque(<?php echo $bloque['id']; ?>)">
                                Editar
                            </button>
                            <form method="POST" style="display: inline; margin: 0;">
                                <input type="hidden" name="accion" value="eliminar_bloque">
                                <input type="hidden" name="bloque_id" value="<?php echo $bloque['id']; ?>">
                                <button type="submit" class="btn-danger btn-small" onclick="return confirm('¿Estás seguro de que deseas eliminar este bloque?');">
                                    Eliminar
                                </button>
                            </form>
                        </div>

                        <!-- Formulario de Edición (Oculto) -->
                        <div class="bloque-editar" id="editar-<?php echo $bloque['id']; ?>">
                            <h4>Editar Bloque</h4>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="accion" value="actualizar_bloque">
                                <input type="hidden" name="bloque_id" value="<?php echo $bloque['id']; ?>">
                                <input type="hidden" name="tipo" value="<?php echo $bloque['tipo']; ?>">

                                <div class="form-group">
                                    <?php if ($bloque['tipo'] === 'imagen'): ?>
                                        <label for="contenido-<?php echo $bloque['id']; ?>">Nueva Imagen (opcional):</label>
                                        <input type="file" id="contenido-<?php echo $bloque['id']; ?>" name="contenido" accept="image/*">
                                        <small>Deja vacío si no quieres cambiar la imagen</small>
                                    <?php else: ?>
                                        <label for="contenido-<?php echo $bloque['id']; ?>">Texto:</label>
                                        <textarea id="contenido-<?php echo $bloque['id']; ?>" name="contenido" required><?php echo htmlspecialchars($bloque['contenido']); ?></textarea>
                                    <?php endif; ?>
                                </div>

                                <div class="bloque-acciones">
                                    <button type="submit">Guardar Cambios</button>
                                    <button type="button" class="btn-secondary" onclick="toggleEditarBloque(<?php echo $bloque['id']; ?>)">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php $bloque_num++; endwhile; ?>
            <?php else: ?>
                <p style="color: #999;">No hay bloques aún. Agrega contenido usando el formulario de abajo.</p>
            <?php endif; ?>
        </div>

        <!-- Sección: Agregar Nuevo Bloque -->
        <div class="section">
            <h2>Agregar Nuevo Contenido</h2>
            
            <div class="agregar-acciones">
                <button type="button" onclick="toggleAgregarBloque('parrafo')">
                    + Agregar Párrafo
                </button>
                <button type="button" onclick="toggleAgregarBloque('imagen')">
                    + Agregar Imagen
                </button>
            </div>

            <!-- Formulario Agregar Párrafo -->
            <div class="agregar-bloque-form" id="agregar-parrafo">
                <h4>Nuevo Párrafo</h4>
                <form method="POST">
                    <input type="hidden" name="accion" value="agregar_bloque">
                    <input type="hidden" name="tipo" value="parrafo">
                    
                    <div class="form-group">
                        <label for="contenido-parrafo">Texto:</label>
                        <textarea id="contenido-parrafo" name="contenido" required></textarea>
                    </div>
                    
                    <div class="bloque-acciones">
                        <button type="submit">Agregar Párrafo</button>
                        <button type="button" class="btn-secondary" onclick="toggleAgregarBloque('parrafo')">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- Formulario Agregar Imagen -->
            <div class="agregar-bloque-form" id="agregar-imagen">
                <h4>Nueva Imagen</h4>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="agregar_bloque">
                    <input type="hidden" name="tipo" value="imagen">
                    
                    <div class="form-group">
                        <label for="contenido-imagen">Seleccionar Imagen:</label>
                        <input type="file" id="contenido-imagen" name="contenido" accept="image/*" required>
                    </div>
                    
                    <div class="bloque-acciones">
                        <button type="submit">Agregar Imagen</button>
                        <button type="button" class="btn-secondary" onclick="toggleAgregarBloque('imagen')">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once '../pie_pagina.php'; ?>

    <script>
        function toggleEditarBloque(bloqueId) {
            const elem = document.getElementById('editar-' + bloqueId);
            if (elem) {
                elem.classList.toggle('activo');
            }
        }

        function toggleAgregarBloque(tipo) {
            const elem = document.getElementById('agregar-' + tipo);
            if (elem) {
                elem.classList.toggle('activo');
            }
        }
    </script>
</body>
</html>
