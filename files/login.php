<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #ffffff;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            border: 1px solid #d0d0d0;
        }

        .login-box h1 {
            text-align: center;
            color: #000000;
            margin-bottom: 30px;
            font-size: 28px;
            font-family: 'Georgia', serif;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333333;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #cccccc;
            border-radius: 0;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
            background-color: #ffffff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #000000;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #000000;
            color: white;
            border: 1px solid #333333;
            border-radius: 0;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: #333333;
        }

        .error-message {
            background-color: #f0f0f0;
            color: #721c24;
            padding: 12px;
            border-radius: 0;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #cccccc;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #333333;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1><i class="fas fa-user-shield"></i> Panel Admin</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="auth.php">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="back-link">
                <a href="../index.php">← Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
