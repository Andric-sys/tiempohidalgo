<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

    if (empty($usuario) || empty($contrasena)) {
        $_SESSION['error'] = 'Por favor completa todos los campos';
        header('Location: login.php');
        exit();
    }

    // Buscar usuario por nombre
    $sql = "SELECT id, usuario, contrasena, estado FROM usuarios WHERE usuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar si el usuario está activo
        if (isset($row['estado']) && $row['estado'] === 'inactivo') {
            $_SESSION['error'] = 'Este usuario ha sido desactivado';
            header('Location: login.php');
            exit();
        }
        
        // Verificar contraseña con password_verify (para password_hash) o md5 (para contraseñas antiguas)
        $password_valido = false;
        
        // Primero intentar con password_verify (para contraseñas nuevas con bcrypt)
        if (password_verify($contrasena, $row['contrasena'])) {
            $password_valido = true;
        } 
        // Si falla, intentar con md5 (para contraseñas antiguas)
        elseif ($row['contrasena'] === md5($contrasena)) {
            $password_valido = true;
            
            // Actualizar el hash a bcrypt automáticamente
            $nuevo_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $update_sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('si', $nuevo_hash, $row['id']);
            $update_stmt->execute();
        }
        
        if ($password_valido) {
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['login_time'] = time();
            
            // Redirigir a panel admin
            header('Location: admin.php');
            exit();
        } else {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Usuario o contraseña incorrectos';
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
