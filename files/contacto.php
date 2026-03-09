<?php
require '../config.php';
include '../header.php';

$mensaje_exito = '';
$mensaje_error = '';

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos requeridos
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        $mensaje_error = 'Por favor completa todos los campos requeridos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = 'El email no es válido.';
    } else {
        // Preparar la consulta SQL
        $sql = "INSERT INTO contactos (nombre, email, telefono, asunto, mensaje, estado, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, 'nuevo', NOW())";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Escapar y vincular parámetros
            $nombre_escaped = htmlspecialchars($nombre);
            $email_escaped = htmlspecialchars($email);
            $telefono_escaped = htmlspecialchars($telefono);
            $asunto_escaped = htmlspecialchars($asunto);
            $mensaje_escaped = htmlspecialchars($mensaje);
            
            $stmt->bind_param('sssss', 
                $nombre_escaped, 
                $email_escaped, 
                $telefono_escaped, 
                $asunto_escaped, 
                $mensaje_escaped
            );
            
            if ($stmt->execute()) {
                $mensaje_exito = '¡Gracias por tu mensaje! Nos pondremos en contacto pronto.';
                // Limpiar formulario
                $_POST = [];
            } else {
                $mensaje_error = 'Error al enviar el mensaje. Por favor intenta de nuevo.';
            }
            $stmt->close();
        } else {
            $mensaje_error = 'Error de base de datos. Por favor intenta de nuevo.';
        }
    }
}
?>

<style>
    .contacto-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 20px;
    }

    .contacto-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .contacto-header h2 {
        color: #000000;
        font-size: 32px;
        margin-bottom: 10px;
        font-family: 'Georgia', serif;
        font-weight: bold;
    }

    .contacto-header p {
        color: #666666;
        font-size: 16px;
    }

    .mensaje-exito {
        background: #f0f0f0;
        color: #155724;
        padding: 15px;
        border-radius: 0;
        margin-bottom: 20px;
        border-left: 4px solid #333333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mensaje-exito i {
        font-size: 20px;
    }

    .mensaje-error {
        background: #f0f0f0;
        color: #721c24;
        padding: 15px;
        border-radius: 0;
        margin-bottom: 20px;
        border-left: 4px solid #333333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mensaje-error i {
        font-size: 20px;
    }

    .formulario-grupo {
        margin-bottom: 20px;
    }

    .formulario-grupo label {
        display: block;
        margin-bottom: 8px;
        color: #333333;
        font-weight: bold;
        font-size: 14px;
    }

    .formulario-grupo input[type="text"],
    .formulario-grupo input[type="email"],
    .formulario-grupo input[type="tel"],
    .formulario-grupo textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #cccccc;
        border-radius: 0;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
        box-sizing: border-box;
        background-color: #ffffff;
    }

    .formulario-grupo input:focus,
    .formulario-grupo textarea:focus {
        outline: none;
        border-color: #000000;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .formulario-grupo textarea {
        resize: vertical;
        min-height: 150px;
    }

    .campo-requerido {
        color: #333333;
    }

    .formulario-acciones {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-enviar {
        background: #000000;
        color: white;
        padding: 12px 40px;
        border: 1px solid #333333;
        border-radius: 0;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 150px;
    }

    .btn-enviar:hover {
        background: #333333;
    }

    .btn-enviar:active {
        background: #000000;
    }

    .btn-limpiar {
        background: #f0f0f0;
        color: #333333;
        padding: 12px 40px;
        border: 2px solid #cccccc;
        border-radius: 0;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-limpiar:hover {
        background: #e0e0e0;
        border-color: #999;
    }

    .info-contacto {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-top: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .info-contacto h3 {
        color: #333;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        color: #666;
    }

    .info-item i {
        color: #667eea;
        font-size: 20px;
        min-width: 20px;
    }

    .info-item-texto {
        display: flex;
        flex-direction: column;
    }

    .info-item-label {
        font-weight: bold;
        color: #333;
        font-size: 14px;
    }

    .info-item-valor {
        color: #666;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .contacto-container {
            margin: 30px auto;
            padding: 15px;
        }

        .contacto-header h2 {
            font-size: 24px;
        }

        .formulario-acciones {
            flex-direction: column;
        }

        .btn-enviar,
        .btn-limpiar {
            width: 100%;
        }
    }
</style>

<div class="contacto-container">
    <div class="contacto-header">
        <h2>📧 Contáctanos</h2>
        <p>Envíanos tu mensaje y nos pondremos en contacto pronto</p>
    </div>

    <?php if ($mensaje_exito): ?>
        <div class="mensaje-exito">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($mensaje_exito); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <div class="mensaje-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($mensaje_error); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="formulario-contacto">
        <div class="formulario-grupo">
            <label for="nombre">
                Nombre <span class="campo-requerido">*</span>
            </label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
        </div>

        <div class="formulario-grupo">
            <label for="email">
                Email <span class="campo-requerido">*</span>
            </label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="formulario-grupo">
            <label for="telefono">
                Teléfono
            </label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
        </div>

        <div class="formulario-grupo">
            <label for="asunto">
                Asunto <span class="campo-requerido">*</span>
            </label>
            <input type="text" id="asunto" name="asunto" required value="<?php echo htmlspecialchars($_POST['asunto'] ?? ''); ?>">
        </div>

        <div class="formulario-grupo">
            <label for="mensaje">
                Mensaje <span class="campo-requerido">*</span>
            </label>
            <textarea id="mensaje" name="mensaje" required><?php echo htmlspecialchars($_POST['mensaje'] ?? ''); ?></textarea>
        </div>

        <div class="formulario-acciones">
            <button type="submit" class="btn-enviar">
                <i class="fas fa-paper-plane"></i> Enviar Mensaje
            </button>
            <button type="reset" class="btn-limpiar">
                <i class="fas fa-redo"></i> Limpiar
            </button>
        </div>
    </form>

    <div class="info-contacto">
        <h3><i class="fas fa-info-circle"></i> Información de Contacto</h3>
        
        <div class="info-item">
            <i class="fas fa-user-edit"></i>
            <div class="info-item-texto">
                <span class="info-item-label">Editor responsable</span>
                <span class="info-item-valor">José Enrique Flores León</span>
            </div>
        </div>
        
        <div class="info-item">
            <i class="fas fa-map-marker-alt"></i>
            <div class="info-item-texto">
                <span class="info-item-label">Dirección</span>
                <span class="info-item-valor">Puerto Madero Edif. 20 Dep. 3 Fracc. El Palmar, Pachuca de Soto, Hgo.</span>
            </div>
        </div>

        <div class="info-item">
            <i class="fas fa-phone"></i>
            <div class="info-item-texto">
                <span class="info-item-label">Teléfono</span>
                <span class="info-item-valor">7141740</span>
            </div>
        </div>

        <div class="info-item">
            <i class="fas fa-mobile-alt"></i>
            <div class="info-item-texto">
                <span class="info-item-label">Celular</span>
                <span class="info-item-valor">7711812072</span>
            </div>
        </div>

        <div class="info-item">
            <i class="fas fa-envelope"></i>
            <div class="info-item-texto">
                <span class="info-item-label">Correo</span>
                <span class="info-item-valor">enryflo@prodigy.net.mx</span>
            </div>
        </div>
    </div>
</div>

<?php include '../pie_pagina.php'; ?>
