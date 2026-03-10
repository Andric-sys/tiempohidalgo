<?php
session_start();
require_once '../config.php';
require_once '../social_media_api.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$tipo_mensaje = '';
$url_noticia_publicada = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    
    if (empty($titulo)) {
        $mensaje = 'El título es obligatorio';
        $tipo_mensaje = 'error';
    } else {
        // Contar cuántos bloques se envían
        $numero_bloques = 0;
        for ($i = 1; isset($_POST["bloque_tipo_$i"]); $i++) {
            $numero_bloques++;
        }

        if ($numero_bloques === 0) {
            $mensaje = 'Debes agregar al menos un bloque (imagen o párrafo)';
            $tipo_mensaje = 'error';
        } else {
            // Insertar noticia
            $sql = "INSERT INTO noticias (titulo) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $titulo);
            
            if ($stmt->execute()) {
                $noticia_id = $conn->insert_id;
                $bloques_insertados = 0;

                // Recolectar todos los IDs de bloques que existen
                $bloques_ids = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'bloque_tipo_') === 0) {
                        $id = str_replace('bloque_tipo_', '', $key);
                        $bloques_ids[] = $id;
                    }
                }
                
                // Ordenar por ID para mantener el orden original
                sort($bloques_ids, SORT_NUMERIC);

                // Insertar bloques
                $orden = 1;
                foreach ($bloques_ids as $id) {
                    $tipo = isset($_POST["bloque_tipo_$id"]) ? $_POST["bloque_tipo_$id"] : '';
                    
                    if ($tipo === 'imagen') {
                        if (isset($_FILES["bloque_archivo_$id"]) && $_FILES["bloque_archivo_$id"]['error'] === UPLOAD_ERR_OK) {
                            $file = $_FILES["bloque_archivo_$id"];
                            $nombre_archivo = time() . '_' . $id . '_' . basename($file['name']);
                            $ruta_uploads = '../uploads/';
                            
                            if (!is_dir($ruta_uploads)) {
                                mkdir($ruta_uploads, 0777, true);
                            }
                            
                            $ruta_destino = $ruta_uploads . $nombre_archivo;
                            
                            if (move_uploaded_file($file['tmp_name'], $ruta_destino)) {
                                $ruta_relativa = 'uploads/' . $nombre_archivo;
                                $sql_bloque = "INSERT INTO bloques (noticia_id, tipo, contenido, orden) VALUES (?, 'imagen', ?, ?)";
                                $stmt_bloque = $conn->prepare($sql_bloque);
                                $stmt_bloque->bind_param('isi', $noticia_id, $ruta_relativa, $orden);
                                
                                if ($stmt_bloque->execute()) {
                                    $bloques_insertados++;
                                    $orden++;
                                }
                            }
                        }
                    } elseif ($tipo === 'parrafo') {
                        $contenido = isset($_POST["bloque_contenido_$id"]) ? trim($_POST["bloque_contenido_$id"]) : '';
                        
                        if (!empty($contenido)) {
                            $sql_bloque = "INSERT INTO bloques (noticia_id, tipo, contenido, orden) VALUES (?, 'parrafo', ?, ?)";
                            $stmt_bloque = $conn->prepare($sql_bloque);
                            $stmt_bloque->bind_param('isi', $noticia_id, $contenido, $orden);
                            
                            if ($stmt_bloque->execute()) {
                                $bloques_insertados++;
                                $orden++;
                            }
                        }
                    }
                }

                if ($bloques_insertados > 0) {
                    $mensaje = 'Noticia agregada correctamente con ' . $bloques_insertados . ' bloque(s)';
                    $tipo_mensaje = 'exito';
                    $url_noticia_publicada = APP_URL . '/noticia.php?id=' . $noticia_id;
                    
                    // ============================================
                    // INTEGRACIÓN CON REDES SOCIALES
                    // ============================================
                    $publicar_en_redes = [
                        'facebook' => isset($_POST['publicar_facebook']) && $_POST['publicar_facebook'] === 'on',
                        'instagram' => isset($_POST['publicar_instagram']) && $_POST['publicar_instagram'] === 'on',
                        'twitter' => isset($_POST['publicar_twitter']) && $_POST['publicar_twitter'] === 'on'
                    ];

                    // Solo procesar si al menos una red está seleccionada
                    if (in_array(true, $publicar_en_redes)) {
                        // Obtener la primera imagen de los bloques
                        $sql_imagen = "SELECT contenido FROM bloques WHERE noticia_id = ? AND tipo = 'imagen' ORDER BY orden LIMIT 1";
                        $stmt_img = $conn->prepare($sql_imagen);
                        $stmt_img->bind_param('i', $noticia_id);
                        $stmt_img->execute();
                        $resultado_img = $stmt_img->get_result();
                        $imagen_url = '';
                        
                        if ($fila_img = $resultado_img->fetch_assoc()) {
                            // Construir URL completa de la imagen
                            $imagen_url = APP_URL . '/' . $fila_img['contenido'];
                        }

                        // Obtener descripción (primer párrafo o un resumen)
                        $sql_desc = "SELECT contenido FROM bloques WHERE noticia_id = ? AND tipo = 'parrafo' ORDER BY orden LIMIT 1";
                        $stmt_desc = $conn->prepare($sql_desc);
                        $stmt_desc->bind_param('i', $noticia_id);
                        $stmt_desc->execute();
                        $resultado_desc = $stmt_desc->get_result();
                        $descripcion = '';
                        
                        if ($fila_desc = $resultado_desc->fetch_assoc()) {
                            $descripcion = SocialMediaAPI::generarResumen($titulo, $fila_desc['contenido']);
                        } else {
                            $descripcion = substr($titulo, 0, 150) . '...';
                        }

                        // Publicar en todas las redes seleccionadas
                        $resultados_redes = publicarEnTodasLasRedes(
                            $noticia_id,
                            $titulo,
                            $descripcion,
                            $imagen_url,
                            $url_noticia_publicada,
                            $publicar_en_redes
                        );

                        // Actualizar flag de publicación en redes
                        $sql_update = "UPDATE noticias SET publicado_redes = true, fecha_publicacion_redes = NOW() WHERE id = ?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param('i', $noticia_id);
                        $stmt_update->execute();

                        // Agregar información de publicación al mensaje
                        $redes_publicadas = [];
                        foreach ($resultados_redes as $red => $resultado) {
                            if (isset($resultado['success']) && $resultado['success']) {
                                $redes_publicadas[] = ucfirst($red);
                            }
                        }
                        
                        if (!empty($redes_publicadas)) {
                            $mensaje .= ' y publicado en: ' . implode(', ', $redes_publicadas);
                        }
                    }
                    
                    // Limpiar el formulario
                    $_POST = array();
                } else {
                    // Eliminar noticia si no se insertaron bloques
                    $sql_delete = "DELETE FROM noticias WHERE id = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param('i', $noticia_id);
                    $stmt_delete->execute();
                    
                    $mensaje = 'Error al guardar los bloques de la noticia';
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = 'Error al crear la noticia: ' . $conn->error;
                $tipo_mensaje = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Noticia - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid #d0d0d0;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000000;
            padding-bottom: 20px;
        }

        .admin-header h1 {
            color: #000000;
            font-size: 28px;
            font-family: 'Georgia', serif;
            font-weight: bold;
        }

        .admin-header a {
            background: #333333;
            color: white;
            padding: 10px 20px;
            border-radius: 0;
            text-decoration: none;
            transition: background 0.3s;
            border: 1px solid #555555;
        }

        .admin-header a:hover {
            background: #555555;
        }

        .mensaje {
            padding: 15px;
            border-radius: 0;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .mensaje.exito {
            background: #f0f0f0;
            color: #155724;
            border: 1px solid #cccccc;
        }

        .mensaje.error {
            background: #f0f0f0;
            color: #721c24;
            border: 1px solid #cccccc;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333333;
            font-weight: bold;
            font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #cccccc;
            border-radius: 0;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
            transition: border-color 0.3s;
            background-color: #ffffff;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #000000;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .bloques-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .bloques-container h3 {
            color: #333333;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-family: 'Georgia', serif;
        }

        .bloque-item {
            background: white;
            padding: 20px;
            border-radius: 0;
            margin-bottom: 15px;
            border-left: 4px solid #333333;
            position: relative;
            border: 1px solid #d0d0d0;
            border-left-width: 4px;
        }

        .bloque-tipo {
            display: inline-block;
            background: #333333;
            color: white;
            padding: 5px 10px;
            border-radius: 0;
            font-size: 12px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .bloque-tipo.parrafo {
            background: #666666;
        }

        .bloque-tipo.imagen {
            background: #333333;
        }

        .bloque-item input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #cccccc;
            border-radius: 0;
            cursor: pointer;
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: 1px solid #555555;
            border-radius: 0;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-agregar {
            background: #333333;
            color: white;
            border: 1px solid #555555;
        }

        .btn-agregar:hover {
            background: #555555;
        }

        .btn-imagen {
            background: #666666;
            color: white;
            margin-right: auto;
            border: 1px solid #888888;
        }

        .btn-imagen:hover {
            background: #888888;
        }

        .btn-parrafo {
            background: #999999;
            color: #ffffff;
            border: 1px solid #aaaaaa;
        }

        .btn-parrafo:hover {
            background: #aaaaaa;
        }

        .btn-eliminar {
            background: #555555;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            position: absolute;
            top: 10px;
            right: 10px;
            border: 1px solid #777777;
        }

        .btn-eliminar:hover {
            background: #777777;
        }

        .btn-enviar {
            background: #333333;
            color: white;
            padding: 12px 40px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #555555;
        }

        .btn-enviar:hover {
            background: #555555;
        }

        .sin-bloques {
            color: #999999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-container label {
            margin: 0;
            cursor: pointer;
        }

        .preview-imagen {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body style="background-color: #f5f5f5;">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-plus-circle"></i> Agregar Nueva Noticia</h1>
            <a href="admin.php"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
            <?php if ($tipo_mensaje === 'exito' && !empty($url_noticia_publicada)): ?>
                <div class="btn-container" style="margin-bottom: 20px;">
                    <a class="btn btn-parrafo" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_noticia_publicada); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook"></i> Publicar en Facebook
                    </a>
                    <a class="btn btn-imagen" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url_noticia_publicada); ?>&text=<?php echo urlencode('Nueva noticia publicada'); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-twitter"></i> Publicar en X
                    </a>
                    <a class="btn btn-agregar" href="https://api.whatsapp.com/send?text=<?php echo urlencode('Nueva noticia: ' . $url_noticia_publicada); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i> Publicar en WhatsApp
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="formularioNoticia">
            <div class="form-group">
                <label for="titulo">Título de la Noticia:</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Ingresa el título aquí..." value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
            </div>

            <div class="bloques-container">
                <h3><i class="fas fa-layer-group"></i> Bloques (Imágenes y Párrafos)</h3>
                <div id="bloquesContainer">
                    <!-- Los bloques se agregarán aquí dinámicamente -->
                </div>
                
                <div class="btn-container" style="margin-top: 20px;">
                    <button type="button" class="btn btn-imagen" onclick="agregarBloque('imagen')">
                        <i class="fas fa-image"></i> Agregar Imagen
                    </button>
                    <button type="button" class="btn btn-parrafo" onclick="agregarBloque('parrafo')">
                        <i class="fas fa-paragraph"></i> Agregar Párrafo
                    </button>
                </div>
            </div>

            <!-- SECCIÓN DE REDES SOCIALES -->
            <div class="bloques-container" style="background: #f0f8ff;">
                <h3><i class="fas fa-share-alt"></i> Publicar en Redes Sociales</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                    Selecciona las redes en las que deseas publicar automáticamente esta noticia:
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <div class="checkbox-container" style="margin: 0; padding: 12px; background: white; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="checkbox" id="publicar_facebook" name="publicar_facebook" value="on">
                        <label for="publicar_facebook" style="margin: 0;">
                            <i class="fab fa-facebook" style="color: #1877F2;"></i> Facebook
                        </label>
                    </div>
                    
                    <div class="checkbox-container" style="margin: 0; padding: 12px; background: white; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="checkbox" id="publicar_instagram" name="publicar_instagram" value="on">
                        <label for="publicar_instagram" style="margin: 0;">
                            <i class="fab fa-instagram" style="color: #E4405F;"></i> Instagram
                        </label>
                    </div>
                    
                    <div class="checkbox-container" style="margin: 0; padding: 12px; background: white; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="checkbox" id="publicar_twitter" name="publicar_twitter" value="on">
                        <label for="publicar_twitter" style="margin: 0;">
                            <i class="fab fa-x-twitter" style="color: #000;"></i> X (Twitter)
                        </label>
                    </div>
                </div>

                <div style="background: #fff3cd; padding: 10px; margin-top: 15px; border-left: 4px solid #ffc107; border-radius: 4px; font-size: 13px; color: #856404;">
                    <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Asegúrate de haber configurado las credenciales de las redes sociales antes de activar esta opción.
                </div>
            </div>

            <button type="submit" class="btn btn-enviar">
                <i class="fas fa-save"></i> Publicar Noticia
            </button>
        </form>
    </div>

    <script>
        let bloqueCounter = 0;

        function agregarBloque(tipo) {
            bloqueCounter++;
            const container = document.getElementById('bloquesContainer');
            
            // Crear elemento div en lugar de usar innerHTML +=
            const bloqueDiv = document.createElement('div');
            bloqueDiv.className = `bloque-item ${tipo}`;
            bloqueDiv.id = `bloque_${bloqueCounter}`;
            
            let html = `
                <button type="button" class="btn btn-eliminar" onclick="eliminarBloque(${bloqueCounter})">
                    <i class="fas fa-trash"></i>
                </button>
                <span class="bloque-tipo ${tipo}">${tipo === 'imagen' ? 'Imagen' : 'Párrafo'}</span>
            `;

            if (tipo === 'imagen') {
                html += `
                    <div class="form-group">
                        <label for="bloque_archivo_${bloqueCounter}">Selecciona una imagen:</label>
                        <input type="file" id="bloque_archivo_${bloqueCounter}" name="bloque_archivo_${bloqueCounter}" accept="image/*" required onchange="previewImagen(this)">
                        <img class="preview-imagen" id="preview_${bloqueCounter}" style="display:none;">
                    </div>
                `;
            } else {
                html += `
                    <div class="form-group">
                        <label for="bloque_contenido_${bloqueCounter}">Contenido del párrafo:</label>
                        <textarea id="bloque_contenido_${bloqueCounter}" name="bloque_contenido_${bloqueCounter}" placeholder="Escribe el contenido del párrafo aquí..." required></textarea>
                    </div>
                `;
            }

            html += `<input type="hidden" name="bloque_tipo_${bloqueCounter}" value="${tipo}">`;
            
            bloqueDiv.innerHTML = html;
            container.appendChild(bloqueDiv);
            actualizarOrdenes();
        }

        function eliminarBloque(id) {
            const bloque = document.getElementById('bloque_' + id);
            if (bloque) {
                bloque.remove();
                actualizarOrdenes();
            }
        }

        function actualizarOrdenes() {
            const bloques = document.querySelectorAll('.bloque-item');
            bloques.forEach((bloque, index) => {
                // Los órdenes se mantienen por el orden en el HTML
            });
        }

        function previewImagen(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview_' + input.id.split('_')[2]);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Agregar primer bloque al cargar la página
        window.addEventListener('load', function() {
            const container = document.getElementById('bloquesContainer');
            if (container.innerHTML.trim() === '') {
                agregarBloque('imagen');
                agregarBloque('parrafo');
            }
        });
    </script>
</body>
</html>
