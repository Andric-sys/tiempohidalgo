<?php
require 'config.php';
include 'header.php';
?>

    <!-- Carrusel de últimas noticias -->
    <section class="carousel-section">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php
                // Obtener las 5 últimas noticias con imágenes
                $sql = "SELECT n.id, n.titulo, n.fecha_creacion, n.fecha_actualizacion,
                        GROUP_CONCAT(b.contenido SEPARATOR '|') as imagenes
                        FROM noticias n
                        LEFT JOIN bloques b ON n.id = b.noticia_id AND b.tipo = 'imagen'
                        GROUP BY n.id
                        HAVING imagenes IS NOT NULL
                        ORDER BY n.fecha_creacion DESC
                        LIMIT 5";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imagenes = explode('|', $row['imagenes']);
                        $primera_imagen = $imagenes[0];
                        ?>
                        <div class="swiper-slide">
                            <div class="carousel-item">
                                <img src="<?php echo htmlspecialchars($primera_imagen); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                                <div class="carousel-caption">
                                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                                    <p><?php 
                                        $fecha_mostrar = ($row['fecha_actualizacion'] !== $row['fecha_creacion']) ? $row['fecha_actualizacion'] : $row['fecha_creacion'];
                                        echo date('d \d\e F \d\e Y', strtotime($fecha_mostrar)); 
                                    ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- Búsqueda y filtros -->
    <section class="search-section">
        <div class="search-wrapper">
            <input type="text" id="mainSearchInput" class="main-search-input" placeholder="Filtrar noticias por título...">
        </div>
    </section>

    <!-- Cards de noticias -->
    <section class="noticias-section" id="noticias">
        <h2>Últimas Noticias</h2>
        <div class="noticias-grid" id="noticiasGrid">
            <?php
            // Obtener todas las noticias ordenadas por fecha (más reciente primero)
            $sql = "SELECT n.id, n.titulo, n.fecha_creacion, n.fecha_actualizacion,
                    GROUP_CONCAT(DISTINCT b.contenido ORDER BY b.orden LIMIT 1) as primera_imagen
                    FROM noticias n
                    LEFT JOIN bloques b ON n.id = b.noticia_id AND b.tipo = 'imagen'
                    GROUP BY n.id
                    ORDER BY n.fecha_creacion DESC";
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="noticia-card" data-titulo="<?php echo htmlspecialchars(strtolower($row['titulo'])); ?>">
                        <div class="noticia-imagen">
                            <?php if ($row['primera_imagen']): ?>
                                <img src="<?php echo htmlspecialchars($row['primera_imagen']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                            <?php else: ?>
                                <img src="assets/images/placeholder.png" alt="Imagen no disponible">
                            <?php endif; ?>
                        </div>
                        <div class="noticia-contenido">
                            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                            <p class="noticia-fecha"><?php 
                                $fecha_mostrar = ($row['fecha_actualizacion'] !== $row['fecha_creacion']) ? $row['fecha_actualizacion'] : $row['fecha_creacion'];
                                echo date('d \d\e F \d\e Y', strtotime($fecha_mostrar)); 
                            ?></p>
                            <a href="noticia.php?id=<?php echo $row['id']; ?>" class="leer-mas">Leer más →</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <p class="sin-noticias">No hay noticias disponibles aún.</p>
                <?php
            }
            ?>
        </div>
    </section>

<?php include 'pie_pagina.php'; ?>

<script>
    // Inicializar búsqueda
    document.getElementById('mainSearchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.noticia-card');
        
        cards.forEach(card => {
            const titulo = card.getAttribute('data-titulo');
            if (titulo.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Búsqueda desde el navbar
    document.querySelector('.search-btn').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        document.getElementById('mainSearchInput').value = searchTerm;
        document.getElementById('mainSearchInput').dispatchEvent(new Event('keyup'));
    });
</script>
