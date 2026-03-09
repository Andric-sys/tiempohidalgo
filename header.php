<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Detectar si estamos en una subcarpeta
$base_path = (strpos($_SERVER['PHP_SELF'], '/files/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Tiempo de Hidalgo - Noticias y Reportajes</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
</head>
<body>
    <!-- Header con título -->
    <div class="header-container">
        <h1 class="site-title">El Tiempo de Hidalgo</h1>
        <p class="site-subtitle">Noticias y Reportajes</p>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <ul class="nav-links">
                <li><a href="<?php echo $base_path; ?>index.php" class="nav-link">Inicio</a></li>
                <li><a href="<?php echo $base_path; ?>index.php#noticias" class="nav-link">Noticias</a></li>
                <li><a href="<?php echo $base_path; ?>galeria.php" class="nav-link">Galería</a></li>
                <li><a href="<?php echo $base_path; ?>files/contacto.php" class="nav-link">Contacto</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="<?php echo $base_path; ?>files/admin.php" class="nav-link admin-link">Panel Admin</a></li>
                    <li><a href="<?php echo $base_path; ?>files/logout.php" class="nav-link logout-link">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="<?php echo $base_path; ?>files/login.php" class="nav-link">Admin</a></li>
                <?php endif; ?>
            </ul>
            
            <!-- Búsqueda -->
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar noticias...">
                <button type="button" class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
