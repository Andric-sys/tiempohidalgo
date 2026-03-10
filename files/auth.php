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

    // Si no existe ningun usuario, crear el primero automaticamente
    $count_sql = "SELECT COUNT(*) AS total FROM usuarios";
    $count_result = $conn->query($count_sql);

    if ($count_result) {
        $count_row = $count_result->fetch_assoc();
        $total_usuarios = isset($count_row['total']) ? (int)$count_row['total'] : 0;

        if ($total_usuarios === 0) {
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('ss', $usuario, $contrasena_hash);

            if ($insert_stmt->execute()) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['usuario_id'] = $insert_stmt->insert_id;
                $_SESSION['login_time'] = time();
                $_SESSION['mensaje'] = 'Usuario inicial creado correctamente';
                $_SESSION['tipo_mensaje'] = 'exito';

                header('Location: admin.php');
                exit();
            }

            $_SESSION['error'] = 'No se pudo crear el usuario inicial. Intenta de nuevo.';
            header('Location: login.php');
            exit();
        }
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
