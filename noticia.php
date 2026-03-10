<?php
require 'config.php';

$noticia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($noticia_id === 0) {
    include 'header.php';
    ?>
    <section class="noticias-section">
        <p class="sin-noticias">Noticia no encontrada</p>
    </section>
    <?php
    include 'pie_pagina.php';
    exit();
}

// Obtener noticia
$sql = "SELECT * FROM noticias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $noticia_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    include 'header.php';
    ?>
    <section class="noticias-section">
        <p class="sin-noticias">Noticia no encontrada</p>
    </section>
    <?php
    include 'pie_pagina.php';
    exit();
}

$noticia = $result->fetch_assoc();

// Obtener bloques para la meta descripción
$sql = "SELECT * FROM bloques WHERE noticia_id = ? ORDER BY orden ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $noticia_id);
$stmt->execute();
$bloques = $stmt->get_result();

// Obtener primera imagen para Open Graph
$noticia_og_imagen = '';
$bloques_temp = $bloques->fetch_all(MYSQLI_ASSOC);
foreach ($bloques_temp as $bloque) {
    if ($bloque['tipo'] === 'imagen') {
        $noticia_og_imagen = APP_URL . '/' . $bloque['contenido'];
        break;
    }
}

// Obtener descripción
$noticia_og_descripcion = '';
foreach ($bloques_temp as $bloque) {
    if ($bloque['tipo'] === 'parrafo') {
        $noticia_og_descripcion = substr(strip_tags($bloque['contenido']), 0, 200);
        break;
    }
}

// Preparar datos para Open Graph en header
$noticia_og_titulo = $noticia['titulo'];
$noticia_og_url = APP_URL . '/noticia.php?id=' . $noticia_id;
if (empty($noticia_og_descripcion)) {
    $noticia_og_descripcion = $noticia['titulo'];
}

// AHORA SÍ incluir header con los meta tags ya listos
include 'header.php';
?>
// Incrementar contador de vistas
$sql = "UPDATE noticias SET vistas = COALESCE(vistas, 0) + 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $noticia_id);
$stmt->execute();
$noticia['vistas'] = isset($noticia['vistas']) ? ((int)$noticia['vistas'] + 1) : 1;
?>

<style>
    .noticia-completa-container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border: 1px solid #d0d0d0;
    }

    .noticia-completa-header {
        margin-bottom: 30px;
        border-bottom: 3px solid #000000;
        padding-bottom: 20px;
    }

    .noticia-completa-header h1 {
        color: #000000;
        font-size: 36px;
        margin: 0 0 15px 0;
        line-height: 1.3;
        font-family: 'Georgia', serif;
        font-weight: bold;
    }

    .noticia-completa-meta {
        color: #666666;
        font-size: 14px;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .noticia-completa-meta i {
        color: #333333;
        margin-right: 5px;
    }

    .noticia-completa-body {
        line-height: 1.8;
        color: #333333;
        font-size: 16px;
    }

    .bloque-imagen {
        margin: 30px 0;
        text-align: center;
    }

    .bloque-imagen img {
        max-width: 100%;
        height: auto;
        border-radius: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .bloque-parrafo {
        margin: 25px 0;
        text-align: justify;
    }

    .noticia-share {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #eee;
    }

    .share-buttons {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .share-buttons label {
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        text-decoration: none;
        font-size: 18px;
        transition: transform 0.3s;
    }

    .noticia-share p {
        margin: 0 0 15px 0;
    }
    
    /* Estilos para botones de compartir */
    .noticia-share a {
        transition: all 0.3s ease !important;
    }
    
    .noticia-share a:hover {
        text-decoration: none !important;
    }
    
    @media (max-width: 768px) {
        .noticia-share div {
            flex-direction: column;
        }
        
        .noticia-share a {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: bold;
        margin-bottom: 30px;
        transition: color 0.3s;
    }

    .back-button:hover {
        color: #764ba2;
    }

    .noticias-relacionadas {
        max-width: 800px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .noticias-relacionadas h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .related-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .related-card {
        background: #f9f9f9;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s;
    }

    .related-card:hover {
        transform: translateY(-5px);
    }

    .related-card a {
        color: #667eea;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .related-card a:hover {
        text-decoration: underline;
    }
</style>

<a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver a noticias</a>

<div class="noticia-completa-container">
    <div class="noticia-completa-header">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <div class="noticia-completa-meta">
            <span><i class="fas fa-calendar"></i> Publicado: <?php echo date('d \\d\\e F \\d\\e Y', strtotime($noticia['fecha_creacion'])); ?></span>
            <?php if ($noticia['fecha_actualizacion'] !== $noticia['fecha_creacion']): ?>
                <span><i class="fas fa-sync"></i> Actualizado: <?php echo date('d \\d\\e F \\d\\e Y', strtotime($noticia['fecha_actualizacion'])); ?></span>
            <?php endif; ?>
            <span><i class="fas fa-eye"></i> Vistas: <?php echo (int)$noticia['vistas']; ?></span>
        </div>
    </div>

    <div class="noticia-completa-body">
        <?php
        if (count($bloques_temp) > 0) {
            foreach ($bloques_temp as $bloque) {
                if ($bloque['tipo'] === 'imagen') {
                    ?>
                    <div class="bloque-imagen">
                        <img src="<?php echo htmlspecialchars($bloque['contenido']); ?>" alt="Imagen noticia">
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

    <div class="noticia-share">
        <p style="color: #333; margin-bottom: 15px; font-weight: bold;">
            <i class="fas fa-share"></i> Compartir en Redes Sociales:
        </p>
        
        <?php 
        $url_actual = htmlspecialchars(APP_URL . '/noticia.php?id=' . (int)$noticia_id);
        $titulo_noticia = htmlspecialchars($noticia['titulo']);
        ?>
        
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_actual); ?>" 
               target="_blank" rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #1877F2; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; cursor: pointer;"
               onmouseover="this.style.background='#165ac3'; this.style.transform='scale(1.05)'"
               onmouseout="this.style.background='#1877F2'; this.style.transform='scale(1)'">
                <i class="fab fa-facebook" style="font-size: 18px;"></i> Facebook
            </a>
            
            <!-- Instagram -->
            <a href="https://www.instagram.com/" 
               target="_blank" rel="noopener noreferrer"
               title="Copia el enlace y comparte en Instagram"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #E4405F; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; cursor: pointer;"
               onmouseover="this.style.background='#d63250'; this.style.transform='scale(1.05)'"
               onmouseout="this.style.background='#E4405F'; this.style.transform='scale(1)'"
               onclick="copiarEnlace(event, '<?php echo $url_actual; ?>')">
                <i class="fab fa-instagram" style="font-size: 18px;"></i> Instagram
            </a>
            
            <!-- Twitter/X -->
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url_actual); ?>&text=<?php echo urlencode($titulo_noticia); ?>" 
               target="_blank" rel="noopener noreferrer"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #000000; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; cursor: pointer;"
               onmouseover="this.style.background='#333333'; this.style.transform='scale(1.05)'"
               onmouseout="this.style.background='#000000'; this.style.transform='scale(1)'">
                <i class="fab fa-x-twitter" style="font-size: 18px;"></i> X (Twitter)
            </a>
        </div>
        
        <script>
            function copiarEnlace(event, url) {
                event.preventDefault();
                // Copiar al portapapeles
                navigator.clipboard.writeText(url).then(function() {
                    alert('Enlace copiado! Pegalo en tu historia de Instagram');
                }).catch(function() {
                    alert('No se pudo copiar. Copia este enlace manualmente: ' + url);
                });
            }
        </script>
    </div>
</div>

<!-- Noticias Relacionadas -->
<div class="noticias-relacionadas">
    <h2>Otras Noticias</h2>
    <div class="related-cards">
        <?php
        $sql = "SELECT id, titulo FROM noticias WHERE id != ? ORDER BY fecha_creacion DESC LIMIT 3";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $noticia_id);
        $stmt->execute();
        $related = $stmt->get_result();

        if ($related->num_rows > 0) {
            while ($row = $related->fetch_assoc()) {
                ?>
                <div class="related-card">
                    <a href="noticia.php?id=<?php echo $row['id']; ?>">
                        <p><?php echo htmlspecialchars($row['titulo']); ?></p>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<p style="text-align: center; color: #999; grid-column: 1 / -1;">No hay otras noticias disponibles</p>';
        }
        ?>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>
