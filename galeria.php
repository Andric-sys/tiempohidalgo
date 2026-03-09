<?php
require 'config.php';
include 'header.php';
?>

<style>
    .galeria-section {
        max-width: 1200px;
        margin: 40px auto;
    }

    .galeria-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .galeria-header h2 {
        color: #000000;
        font-size: 32px;
        margin-bottom: 10px;
        font-family: 'Georgia', serif;
        font-weight: bold;
    }

    .galeria-header p {
        color: #666666;
        font-size: 16px;
    }

    .galeria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .galeria-item {
        background: white;
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid #d0d0d0;
    }

    .galeria-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    }

    .galeria-imagen-container {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .galeria-imagen-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .galeria-item:hover .galeria-imagen-container img {
        transform: scale(1.05);
    }

    .galeria-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .galeria-item:hover .galeria-overlay {
        opacity: 1;
    }

    .galeria-overlay-icon {
        color: white;
        font-size: 48px;
    }

    .galeria-contenido {
        padding: 15px;
    }

    .galeria-titulo {
        font-weight: bold;
        color: #000000;
        margin-bottom: 8px;
        font-size: 16px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .galeria-descripcion {
        color: #666666;
        font-size: 13px;
        margin-bottom: 8px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.4;
    }

    .galeria-fecha {
        color: #999999;
        font-size: 12px;
    }

    .sin-imagenes {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 0;
        color: #999999;
        border: 1px solid #d0d0d0;
    }

    .sin-imagenes i {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    /* Modal para ampliar imagen */
    .modal-imagen {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-imagen.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-contenido {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
    }

    .modal-imagen img {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 10px;
    }

    .modal-info {
        color: white;
        margin-top: 20px;
        text-align: center;
    }

    .modal-info h3 {
        margin-bottom: 10px;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 40px;
        color: white;
        font-size: 40px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .modal-close:hover {
        color: #ddd;
    }

    .modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 40px;
        cursor: pointer;
        padding: 20px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 5px;
        transition: background 0.3s;
    }

    .modal-nav:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .modal-prev {
        left: 10px;
    }

    .modal-next {
        right: 10px;
    }

    .filtro-categorias {
        text-align: center;
        margin-bottom: 30px;
    }

    .filtro-btn {
        background: #f0f0f0;
        border: 2px solid #ddd;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: bold;
        color: #333;
    }

    .filtro-btn:hover,
    .filtro-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    @media (max-width: 768px) {
        .galeria-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .galeria-imagen-container {
            height: 150px;
        }

        .modal-nav {
            font-size: 30px;
            padding: 15px;
        }

        .modal-close {
            right: 20px;
            top: 10px;
        }
    }
</style>

<div class="galeria-section">
    <div class="galeria-header">
        <h2>🖼️ Galería de Imágenes</h2>
        <p>Todas nuestras imágenes ordenadas por fecha</p>
    </div>

    <div class="filtro-categorias">
        <button class="filtro-btn active" onclick="filtrarGaleria('todas')">
            Todas (<?php 
            // Contar imágenes de ambas fuentes
            $sql1 = "SELECT COUNT(*) as total FROM galeria";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();
            $count1 = $row1['total'];
            
            $sql2 = "SELECT COUNT(*) as total FROM bloques WHERE tipo = 'imagen'";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $count2 = $row2['total'];
            
            echo ($count1 + $count2);
            ?>)
        </button>
    </div>

    <div class="galeria-grid" id="galeriaGrid">
        <?php
        // Obtener imágenes de galeria
        $imagenes = [];
        
        // Query 1: Imágenes de la tabla galeria
        $sql1 = "SELECT g.id, g.titulo, g.imagen, g.descripcion, g.fecha_creacion,
                        n.titulo as noticia_titulo
                 FROM galeria g
                 LEFT JOIN noticias n ON g.noticia_id = n.id
                 ORDER BY g.fecha_creacion DESC";
        
        $result1 = $conn->query($sql1);
        if ($result1 && $result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                $imagenes[] = $row;
            }
        }
        
        // Query 2: Imágenes de la tabla bloques (de noticias)
        $sql2 = "SELECT b.id, '' as titulo, b.contenido as imagen, '' as descripcion, 
                        n.fecha_creacion, n.titulo as noticia_titulo
                 FROM bloques b
                 INNER JOIN noticias n ON b.noticia_id = n.id
                 WHERE b.tipo = 'imagen'
                 ORDER BY n.fecha_creacion DESC";
        
        $result2 = $conn->query($sql2);
        if ($result2 && $result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {
                $imagenes[] = $row;
            }
        }
        
        // Ordenar todas las imágenes por fecha (más recientes primero)
        usort($imagenes, function($a, $b) {
            return strtotime($b['fecha_creacion']) - strtotime($a['fecha_creacion']);
        });
        
        if (count($imagenes) > 0) {
            $contador = 0;
            foreach ($imagenes as $row) {
                $contador++;
                ?>
                <div class="galeria-item" onclick="abrirModal(<?php echo $contador; ?>)">
                    <div class="galeria-imagen-container">
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                        <div class="galeria-overlay">
                            <div class="galeria-overlay-icon">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="galeria-contenido">
                        <div class="galeria-titulo"><?php echo htmlspecialchars($row['titulo'] ?: 'Sin título'); ?></div>
                        <div class="galeria-descripcion"><?php echo htmlspecialchars($row['descripcion'] ?: 'Sin descripción'); ?></div>
                        <div class="galeria-fecha">
                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($row['fecha_creacion'])); ?>
                        </div>
                        <?php if ($row['noticia_titulo']): ?>
                            <div class="galeria-fecha" style="margin-top: 5px;">
                                <i class="fas fa-newspaper"></i> <?php echo htmlspecialchars($row['noticia_titulo']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="sin-imagenes" style="grid-column: 1 / -1;">
                <i class="fas fa-image"></i>
                <h3>No hay imágenes en la galería</h3>
                <p>Las imágenes aparecerán aquí cuando se suban en las noticias</p>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Modal para ver imagen ampliada -->
<div class="modal-imagen" id="modalImagen">
    <span class="modal-close" onclick="cerrarModal()">&times;</span>
    <span class="modal-nav modal-prev" onclick="navModal(-1)">&#10094;</span>
    <span class="modal-nav modal-next" onclick="navModal(1)">&#10095;</span>
    
    <div class="modal-contenido">
        <img id="modalImagenImg" src="" alt="">
        <div class="modal-info">
            <h3 id="modalImagenTitulo"></h3>
            <p id="modalImagenDescripcion"></p>
            <p id="modalImagenFecha"></p>
        </div>
    </div>
</div>

<script>
    let galeriaActual = 1;
    let totalImagenes = <?php echo isset($contador) ? $contador : 0; ?>;

    function abrirModal(numero) {
        galeriaActual = numero;
        mostrarImagen();
        document.getElementById('modalImagen').classList.add('show');
    }

    function cerrarModal() {
        document.getElementById('modalImagen').classList.remove('show');
    }

    function navModal(direccion) {
        galeriaActual += direccion;
        if (galeriaActual > totalImagenes) galeriaActual = 1;
        if (galeriaActual < 1) galeriaActual = totalImagenes;
        mostrarImagen();
    }

    function mostrarImagen() {
        const items = document.querySelectorAll('.galeria-item');
        if (galeriaActual - 1 < items.length) {
            const item = items[galeriaActual - 1];
            const img = item.querySelector('img');
            const titulo = item.querySelector('.galeria-titulo').textContent;
            const descripcion = item.querySelector('.galeria-descripcion').textContent;
            const fecha = item.querySelector('.galeria-fecha').textContent;

            document.getElementById('modalImagenImg').src = img.src;
            document.getElementById('modalImagenTitulo').textContent = titulo;
            document.getElementById('modalImagenDescripcion').textContent = descripcion;
            document.getElementById('modalImagenFecha').textContent = fecha;
        }
    }

    function filtrarGaleria(categoria) {
        // Actualizar botones activos
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Mostrar todos los items (en futuro se puede expandir para categorías)
        document.querySelectorAll('.galeria-item').forEach(item => {
            item.style.display = 'block';
        });
    }

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModal();
        }
        if (event.key === 'ArrowRight') {
            navModal(1);
        }
        if (event.key === 'ArrowLeft') {
            navModal(-1);
        }
    });
</script>

<?php include 'pie_pagina.php'; ?>
