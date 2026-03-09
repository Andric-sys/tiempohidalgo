<?php
session_start();
require_once '../config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Determinar la pestaña activa
$tab_activo = isset($_GET['tab']) ? $_GET['tab'] : 'noticias';

// Procesar eliminación de noticia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $noticia_id = isset($_POST['noticia_id']) ? intval($_POST['noticia_id']) : 0;
    
    if ($noticia_id > 0) {
        // Obtener las imágenes para eliminarlas
        $sql = "SELECT contenido FROM bloques WHERE noticia_id = ? AND tipo = 'imagen'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $noticia_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $archivo = '../' . $row['contenido'];
            if (file_exists($archivo)) {
                unlink($archivo);
            }
        }
        
        // Eliminar noticia (los bloques se eliminarán por CASCADE)
        $sql = "DELETE FROM noticias WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $noticia_id);
        $stmt->execute();
        
        $_SESSION['mensaje'] = 'Noticia eliminada correctamente';
        $_SESSION['tipo_mensaje'] = 'exito';
    }
}

// Procesar eliminación de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar_contacto') {
    $contacto_id = isset($_POST['contacto_id']) ? intval($_POST['contacto_id']) : 0;
    
    if ($contacto_id > 0) {
        $sql = "DELETE FROM contactos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $contacto_id);
        $stmt->execute();
        
        $_SESSION['mensaje'] = 'Contacto eliminado correctamente';
        $_SESSION['tipo_mensaje'] = 'exito';
        $tab_activo = 'contactos';
    }
}

// Procesar creación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear_usuario') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
    
    if (!empty($usuario) && !empty($contrasena)) {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $usuario, $contrasena_hash);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = 'Usuario creado correctamente';
            $_SESSION['tipo_mensaje'] = 'exito';
            $tab_activo = 'usuarios';
        } else {
            $_SESSION['mensaje'] = 'Error al crear el usuario. Verifique que el usuario no exista.';
            $_SESSION['tipo_mensaje'] = 'error';
        }
    } else {
        $_SESSION['mensaje'] = 'Por favor complete todos los campos';
        $_SESSION['tipo_mensaje'] = 'error';
    }
}

// Procesar desactivación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'desactivar_usuario') {
    $usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;
    
    if ($usuario_id > 0) {
        $sql = "UPDATE usuarios SET estado = 'inactivo' WHERE id = ? AND usuario != 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        
        $_SESSION['mensaje'] = 'Usuario desactivado correctamente';
        $_SESSION['tipo_mensaje'] = 'exito';
        $tab_activo = 'usuarios';
    }
}

// Procesar actualización de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_usuario') {
    $usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : 0;
    $nuevo_usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $nueva_contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
    
    if ($usuario_id > 0 && !empty($nuevo_usuario)) {
        // Verificar que no sea el admin
        $sql = "SELECT usuario FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        
        if ($fila && $fila['usuario'] !== 'admin') {
            if (!empty($nueva_contrasena) && strlen($nueva_contrasena) > 0) {
                // Actualizar usuario y contraseña
                $contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET usuario = ?, contrasena = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssi', $nuevo_usuario, $contrasena_hash, $usuario_id);
                
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = 'Usuario y contraseña actualizados correctamente';
                    $_SESSION['tipo_mensaje'] = 'exito';
                    $tab_activo = 'usuarios';
                } else {
                    $_SESSION['mensaje'] = 'Error al actualizar el usuario. Verifique que el nombre no esté en uso.';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
            } else {
                // Actualizar solo usuario
                $sql = "UPDATE usuarios SET usuario = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $nuevo_usuario, $usuario_id);
                
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = 'Usuario actualizado correctamente (contraseña sin cambios)';
                    $_SESSION['tipo_mensaje'] = 'exito';
                    $tab_activo = 'usuarios';
                } else {
                    $_SESSION['mensaje'] = 'Error al actualizar el usuario. Verifique que el nombre no esté en uso.';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
            }
        } else {
            $_SESSION['mensaje'] = 'No se puede editar el usuario admin principal';
            $_SESSION['tipo_mensaje'] = 'error';
        }
    } else {
        $_SESSION['mensaje'] = 'Por favor complete el nombre de usuario';
        $_SESSION['tipo_mensaje'] = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #ffffff;
        }

        .admin-panel {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .panel-header {
            background: #000000;
            color: white;
            padding: 30px;
            border-radius: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid #333333;
        }

        .panel-header h1 {
            margin: 0;
            font-size: 32px;
            font-family: 'Georgia', serif;
        }

        .panel-header-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-logout {
            background: #333333;
            color: white;
            padding: 10px 20px;
            border-radius: 0;
            text-decoration: none;
            transition: background 0.3s;
            border: 1px solid #555555;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-logout:hover {
            background: #555555;
        }

        .btn-nueva-noticia {
            background: #333333;
            color: white;
            padding: 12px 25px;
            border-radius: 0;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
            border: 1px solid #555555;
            cursor: pointer;
        }

        .btn-nueva-noticia:hover {
            background: #555555;
        }

        .btn-nuevo-usuario {
            background: #333333;
            color: white;
            padding: 12px 25px;
            border-radius: 0;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
            border: 1px solid #555555;
            cursor: pointer;
        }

        .btn-nuevo-usuario:hover {
            background: #555555;
        }

        .mensaje {
            padding: 15px;
            border-radius: 0;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #cccccc;
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

        .noticias-container {
            background: white;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid #d0d0d0;
        }

        .noticias-table {
            width: 100%;
            border-collapse: collapse;
        }

        .noticias-table thead {
            background: #1a1a1a;
            color: white;
        }

        .noticias-table th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        .noticias-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background 0.2s;
        }

        .noticias-table tbody tr:hover {
            background: #f9f9f9;
        }

        .noticias-table td {
            padding: 15px;
        }

        .noticia-titulo {
            font-weight: bold;
            color: #000000;
            max-width: 300px;
            word-break: break-word;
        }

        .noticia-fecha {
            color: #666666;
            font-size: 14px;
        }

        .acciones {
            display: flex;
            gap: 10px;
        }

        .btn-ver {
            background: #333333;
            color: white;
            padding: 8px 12px;
            border-radius: 0;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.3s;
            border: 1px solid #555555;
        }

        .btn-ver:hover {
            background: #555555;
        }

        .btn-editar {
            background: #666666;
            color: white;
            padding: 8px 12px;
            border-radius: 0;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.3s;
            border: 1px solid #888888;
        }

        .btn-editar:hover {
            background: #888888;
        }

        .btn-eliminar {
            background: #555555;
            color: white;
            padding: 8px 12px;
            border-radius: 0;
            border: 1px solid #777777;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.3s;
        }

        .btn-eliminar:hover {
            background: #777777;
        }

        .sin-noticias {
            padding: 40px;
            text-align: center;
            color: #999999;
            font-size: 16px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            text-align: center;
            border: 1px solid #d0d0d0;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #666666;
            font-size: 14px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #000000;
        }

        .stat-card .icon {
            font-size: 32px;
            color: #333333;
            margin-bottom: 10px;
        }

        /* Estilos para pestañas */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #d0d0d0;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            font-weight: bold;
            color: #666666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-size: 14px;
        }

        .tab-btn:hover {
            color: #000000;
        }

        .tab-btn.active {
            color: #000000;
            border-bottom-color: #000000;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .contacto-estado {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 0;
            font-size: 12px;
            font-weight: bold;
        }

        .estado-nuevo {
            background: #f0f0f0;
            color: #333333;
            border: 1px solid #999999;
        }

        .estado-respondido {
            background: #f0f0f0;
            color: #333333;
            border: 1px solid #999999;
        }

        .estado-cerrado {
            background: #f0f0f0;
            color: #333333;
            border: 1px solid #999999;
        }

        .btn-ver-contacto {
            background: #333333;
            color: white;
            padding: 8px 12px;
            border-radius: 0;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.3s;
            border: 1px solid #555555;
        }

        .btn-ver-contacto:hover {
            background: #555555;
        }

        .btn-responder {
            background: #333333;
            color: white;
            padding: 8px 12px;
            border-radius: 0;
            border: 1px solid #555555;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.3s;
        }

        .btn-responder:hover {
            background: #555555;
        }

        .sin-contactos {
            padding: 40px;
            text-align: center;
            color: #999999;
            font-size: 16px;
            background: white;
            border-radius: 0;
            border: 1px solid #d0d0d0;
        }

        .sin-contactos i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
            display: block;
        }

        /* Estilos para formulario de usuarios */
        .form-usuario {
            background: white;
            padding: 30px;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid #d0d0d0;
            margin-bottom: 30px;
        }

        .form-grupo {
            margin-bottom: 20px;
        }

        .form-grupo label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333333;
        }

        .form-grupo input,
        .form-grupo select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 0;
            font-size: 14px;
            background-color: #ffffff;
        }

        .form-grupo input:focus,
        .form-grupo select:focus {
            outline: none;
            border: 1px solid #000000;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .btn-guardar {
            background: #333333;
            color: white;
            padding: 12px 30px;
            border-radius: 0;
            border: 1px solid #555555;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-guardar:hover {
            background: #555555;
        }

        .btn-cancelar {
            background: #f0f0f0;
            color: #333333;
            padding: 12px 30px;
            border-radius: 0;
            border: 1px solid #cccccc;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancelar:hover {
            background: #e0e0e0;
        }

        .sin-usuarios {
            padding: 40px;
            text-align: center;
            color: #999999;
            font-size: 16px;
            background: white;
            border-radius: 0;
            border: 1px solid #d0d0d0;
        }

        .usuarios-tabla tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* Estilos para Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            position: relative;
            border: 1px solid #d0d0d0;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000000;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            margin: 0;
            color: #000000;
            font-family: 'Georgia', serif;
            font-size: 24px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #333333;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #000000;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            justify-content: flex-end;
        }

        .btn-icon {
            background: #333333;
            color: white;
            border: 1px solid #555555;
            border-radius: 0;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
        }

        .btn-icon:hover {
            background: #555555;
        }

        .btn-icon.btn-edit {
            background: #666666;
            border: 1px solid #888888;
        }

        .btn-icon.btn-edit:hover {
            background: #888888;
        }

        .btn-icon.btn-disable {
            background: #999999;
            border: 1px solid #aaaaaa;
        }

        .btn-icon.btn-disable:hover {
            background: #aaaaaa;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <!-- Header del Panel -->
        <div class="panel-header">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Panel Administrativo</h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">El Tiempo de Hidalgo</p>
            </div>
            <div class="panel-header-right">
                <div class="user-info">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                </div>
                <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje <?php echo $_SESSION['tipo_mensaje']; ?>">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="stats">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-newspaper"></i></div>
                <h3>Total de Noticias</h3>
                <div class="number">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM noticias";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </div>
            </div>
        </div>

        <!-- Botón Agregar Noticia y Usuario -->
        <div style="margin-bottom: 20px; display: flex; gap: 10px;">
            <a href="alta_noticia.php" class="btn-nueva-noticia">
                <i class="fas fa-plus-circle"></i> Nueva Noticia
            </a>
            <button type="button" class="btn-nuevo-usuario" onclick="abrirModalUsuario()">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </button>
        </div>

        <!-- Pestañas -->
        <div class="tabs">
            <button class="tab-btn <?php echo $tab_activo === 'noticias' ? 'active' : ''; ?>" onclick="cambiarTab('noticias', this)">
                <i class="fas fa-newspaper"></i> Noticias
            </button>
            <button class="tab-btn <?php echo $tab_activo === 'contactos' ? 'active' : ''; ?>" onclick="cambiarTab('contactos', this)">
                <i class="fas fa-envelope"></i> Contactos
                <span style="background: #333333; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-left: 5px;">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM contactos WHERE estado = 'nuevo'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </span>
            </button>
            <button class="tab-btn <?php echo $tab_activo === 'usuarios' ? 'active' : ''; ?>" onclick="cambiarTab('usuarios', this)">
                <i class="fas fa-users"></i> Usuarios
            </button>
        </div>

        <!-- Contenido de Noticias -->
        <div class="tab-content <?php echo $tab_activo === 'noticias' ? 'active' : ''; ?>" id="tab-noticias">
            <div class="noticias-container">
                <table class="noticias-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Fecha de Creación</th>
                            <th>Bloques</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT n.id, n.titulo, n.fecha_creacion, 
                                COUNT(b.id) as num_bloques
                                FROM noticias n
                                LEFT JOIN bloques b ON n.id = b.noticia_id
                                GROUP BY n.id
                                ORDER BY n.fecha_creacion DESC";
                        
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="noticia-titulo"><?php echo htmlspecialchars($row['titulo']); ?></td>
                                    <td class="noticia-fecha"><?php echo date('d/m/Y H:i', strtotime($row['fecha_creacion'])); ?></td>
                                    <td><span style="background: #e7f3ff; color: #0066cc; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;"><?php echo $row['num_bloques']; ?> bloques</span></td>
                                    <td class="acciones">
                                        <a href="ver_noticia.php?id=<?php echo $row['id']; ?>" class="btn-ver" title="Ver">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="editar_noticia.php?id=<?php echo $row['id']; ?>" class="btn-editar" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta noticia?');">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="noticia_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn-eliminar" title="Eliminar">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="sin-noticias">
                                    <i class="fas fa-inbox"></i> No hay noticias aún. <a href="alta_noticia.php" style="color: #667eea; text-decoration: none;">Crear primera noticia</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contenido de Contactos -->
        <div class="tab-content <?php echo $tab_activo === 'contactos' ? 'active' : ''; ?>" id="tab-contactos">
            <?php
            $sql = "SELECT c.id, c.nombre, c.email, c.asunto, c.estado, c.fecha_creacion,
                    COUNT(*) OVER() as total
                    FROM contactos c
                    ORDER BY 
                        CASE WHEN c.estado = 'nuevo' THEN 0 WHEN c.estado = 'respondido' THEN 1 ELSE 2 END,
                        c.fecha_creacion DESC";
            
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                ?>
                <div class="noticias-container">
                    <table class="noticias-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Asunto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $estado_class = 'estado-' . $row['estado'];
                                ?>
                                <tr>
                                    <td class="noticia-titulo"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                                    <td class="noticia-titulo"><?php echo htmlspecialchars($row['asunto']); ?></td>
                                    <td>
                                        <span class="contacto-estado <?php echo $estado_class; ?>">
                                            <?php 
                                            $estados = ['nuevo' => 'Nuevo', 'respondido' => 'Respondido', 'cerrado' => 'Cerrado'];
                                            echo $estados[$row['estado']] ?? 'Desconocido';
                                            ?>
                                        </span>
                                    </td>
                                    <td class="noticia-fecha"><?php echo date('d/m/Y H:i', strtotime($row['fecha_creacion'])); ?></td>
                                    <td class="acciones">
                                        <a href="ver_contacto.php?id=<?php echo $row['id']; ?>" class="btn-ver-contacto" title="Ver">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este contacto?');">
                                            <input type="hidden" name="accion" value="eliminar_contacto">
                                            <input type="hidden" name="contacto_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn-eliminar" title="Eliminar">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="sin-contactos">
                    <i class="fas fa-inbox"></i>
                    <h3>No hay contactos aún</h3>
                    <p>Los mensajes de contacto aparecerán aquí cuando los usuarios utilicen el formulario</p>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Contenido de Usuarios -->
        <div class="tab-content <?php echo $tab_activo === 'usuarios' ? 'active' : ''; ?>" id="tab-usuarios">
            <!-- Lista de usuarios -->
            <div class="noticias-container">
                <table class="noticias-table usuarios-tabla">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, usuario, estado, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC";
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="noticia-titulo">
                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($row['usuario']); ?>
                                    </td>
                                    <td>
                                        <span style="background: #e0e0e0; color: #333333; padding: 4px 8px; border-radius: 0; font-size: 12px; font-weight: bold;">
                                            <?php echo ucfirst(htmlspecialchars($row['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="noticia-fecha"><?php echo date('d/m/Y H:i', strtotime($row['fecha_creacion'])); ?></td>
                                    <td class="acciones">
                                        <?php if ($row['usuario'] !== 'admin'): ?>
                                            <button type="button" class="btn-icon btn-edit" title="Editar" onclick="editarUsuario(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['usuario']); ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de desactivar este usuario?');">
                                                <input type="hidden" name="accion" value="desactivar_usuario">
                                                <input type="hidden" name="usuario_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn-icon btn-disable" title="Desactivar">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #999999; font-size: 12px;"><i class="fas fa-lock"></i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="sin-noticias">
                                    <i class="fas fa-users"></i> No hay usuarios registrados
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para Nuevo Usuario -->
    <div class="modal" id="modalNuevoUsuario">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Nuevo Usuario</h2>
                <button type="button" class="modal-close" onclick="cerrarModalUsuario()">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="crear_usuario">
                
                <div class="form-grupo">
                    <label for="nuevoUsuario">Nombre de Usuario:</label>
                    <input type="text" id="nuevoUsuario" name="usuario" required placeholder="Ingrese el nombre de usuario">
                </div>
                
                <div class="form-grupo">
                    <label for="nuevoContrasena">Contraseña:</label>
                    <input type="password" id="nuevoContrasena" name="contrasena" required placeholder="Ingrese una contraseña segura">
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalUsuario()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Editar Usuario -->
    <div class="modal" id="modalEditarUsuario">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Editar Usuario</h2>
                <button type="button" class="modal-close" onclick="cerrarModalEditarUsuario()">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="actualizar_usuario">
                <input type="hidden" id="editUsuarioId" name="usuario_id" value="">
                
                <div class="form-grupo">
                    <label for="editUsuario">Nombre de Usuario:</label>
                    <input type="text" id="editUsuario" name="usuario" required placeholder="Ingrese el nombre de usuario">
                </div>
                
                <div class="form-grupo">
                    <label for="editContrasena">Nueva Contraseña (opcional):</label>
                    <input type="password" id="editContrasena" name="contrasena" placeholder="Dejar vacío para no cambiar">
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalEditarUsuario()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-guardar">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalUsuario() {
            document.getElementById('modalNuevoUsuario').classList.add('active');
            document.getElementById('nuevoUsuario').focus();
        }

        function cerrarModalUsuario() {
            document.getElementById('modalNuevoUsuario').classList.remove('active');
            // Limpiar formulario
            document.querySelector('#modalNuevoUsuario form').reset();
        }

        function abrirModalEditarUsuario() {
            document.getElementById('modalEditarUsuario').classList.add('active');
            document.getElementById('editUsuario').focus();
        }

        function cerrarModalEditarUsuario() {
            document.getElementById('modalEditarUsuario').classList.remove('active');
            // Limpiar formulario
            document.querySelector('#modalEditarUsuario form').reset();
            document.getElementById('editContrasena').value = '';
        }

        function editarUsuario(id, nombre) {
            // Cargar datos en el modal de edición
            document.getElementById('editUsuarioId').value = id;
            document.getElementById('editUsuario').value = nombre;
            document.getElementById('editContrasena').value = '';
            // Abrir modal
            abrirModalEditarUsuario();
        }

        // Cerrar modal al hacer clic fuera del contenido
        document.addEventListener('click', function(event) {
            const modals = [document.getElementById('modalNuevoUsuario'), document.getElementById('modalEditarUsuario')];
            modals.forEach(modal => {
                if (event.target === modal) {
                    if (modal.id === 'modalNuevoUsuario') {
                        cerrarModalUsuario();
                    } else if (modal.id === 'modalEditarUsuario') {
                        cerrarModalEditarUsuario();
                    }
                }
            });
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalUsuario();
                cerrarModalEditarUsuario();
            }
        });

        function cambiarTab(tabName, element) {
            // Ocultar todos los tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Desactivar todos los botones
            const btns = document.querySelectorAll('.tab-btn');
            btns.forEach(btn => btn.classList.remove('active'));

            // Mostrar el tab seleccionado
            document.getElementById('tab-' + tabName).classList.add('active');
            element.classList.add('active');

            // Actualizar URL sin recargar
            window.history.pushState({}, '', '?tab=' + tabName);
        }
    </script>
</body>
</html>
