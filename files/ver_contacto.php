<?php
session_start();
require_once '../config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del contacto
$contacto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($contacto_id <= 0) {
    header('Location: admin.php?tab=contactos');
    exit();
}

// Obtener datos del contacto
$sql = "SELECT * FROM contactos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $contacto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: admin.php?tab=contactos');
    exit();
}

$contacto = $result->fetch_assoc();

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_estado'])) {
    $nuevo_estado = $_POST['nuevo_estado'];
    $estados_validos = ['nuevo', 'respondido', 'cerrado'];
    
    if (in_array($nuevo_estado, $estados_validos)) {
        $sql = "UPDATE contactos SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nuevo_estado, $contacto_id);
        $stmt->execute();
        
        $contacto['estado'] = $nuevo_estado;
        $_SESSION['mensaje'] = 'Estado actualizado correctamente';
        $_SESSION['tipo_mensaje'] = 'exito';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Contacto - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .btn-volver {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-volver:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .mensaje {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .mensaje.exito {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .contacto-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #667eea;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-valor {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            word-break: break-word;
        }

        .mensaje-text {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            line-height: 1.6;
            color: #333;
        }

        .estado-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .estado-nuevo {
            background: #fff3cd;
            color: #856404;
        }

        .estado-respondido {
            background: #d1ecf1;
            color: #0c5460;
        }

        .estado-cerrado {
            background: #d4edda;
            color: #155724;
        }

        .acciones {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .acciones h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-grupo {
            margin-bottom: 15px;
        }

        .form-grupo label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-grupo select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-grupo select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-actualizar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-actualizar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-eliminar {
            background: #dc3545;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            margin-left: 10px;
        }

        .btn-eliminar:hover {
            background: #c82333;
        }

        .botones-grupo {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 10px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .contacto-card,
            .acciones {
                padding: 20px;
            }

            .botones-grupo {
                flex-direction: column;
            }

            .btn-eliminar {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-envelope-open"></i> Mensaje de Contacto</h1>
            <a href="admin.php?tab=contactos" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje <?php echo $_SESSION['tipo_mensaje']; ?>">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <div class="contacto-card">
            <div class="info-item">
                <div class="info-label">Estado</div>
                <div class="info-valor">
                    <span class="estado-badge estado-<?php echo htmlspecialchars($contacto['estado']); ?>">
                        <?php 
                        $estados = ['nuevo' => 'Nuevo', 'respondido' => 'Respondido', 'cerrado' => 'Cerrado'];
                        echo $estados[$contacto['estado']] ?? 'Desconocido';
                        ?>
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-user"></i> Nombre</div>
                <div class="info-valor"><?php echo htmlspecialchars($contacto['nombre']); ?></div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                <div class="info-valor">
                    <a href="mailto:<?php echo htmlspecialchars($contacto['email']); ?>" style="color: #667eea; text-decoration: none;">
                        <?php echo htmlspecialchars($contacto['email']); ?>
                    </a>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-phone"></i> Teléfono</div>
                <div class="info-valor">
                    <?php 
                    if ($contacto['telefono']) {
                        echo htmlspecialchars($contacto['telefono']);
                    } else {
                        echo '<span style="color: #999;">No proporcionado</span>';
                    }
                    ?>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-heading"></i> Asunto</div>
                <div class="info-valor"><?php echo htmlspecialchars($contacto['asunto']); ?></div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-file-alt"></i> Mensaje</div>
                <div class="mensaje-text">
                    <?php echo nl2br(htmlspecialchars($contacto['mensaje'])); ?>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label"><i class="fas fa-calendar"></i> Fecha de Recepción</div>
                <div class="info-valor"><?php echo date('d/m/Y H:i:s', strtotime($contacto['fecha_creacion'])); ?></div>
            </div>
        </div>

        <div class="acciones">
            <h3><i class="fas fa-cogs"></i> Acciones</h3>

            <form method="POST" style="margin-bottom: 20px;">
                <div class="form-grupo">
                    <label for="nuevo_estado">Cambiar Estado:</label>
                    <select id="nuevo_estado" name="nuevo_estado" required>
                        <option value="">Selecciona un estado...</option>
                        <option value="nuevo" <?php echo $contacto['estado'] === 'nuevo' ? 'selected' : ''; ?>>Nuevo</option>
                        <option value="respondido" <?php echo $contacto['estado'] === 'respondido' ? 'selected' : ''; ?>>Respondido</option>
                        <option value="cerrado" <?php echo $contacto['estado'] === 'cerrado' ? 'selected' : ''; ?>>Cerrado</option>
                    </select>
                </div>

                <div class="botones-grupo">
                    <button type="submit" class="btn-actualizar">
                        <i class="fas fa-save"></i> Actualizar Estado
                    </button>

                    <form method="POST" action="admin.php" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este contacto?');">
                        <input type="hidden" name="accion" value="eliminar_contacto">
                        <input type="hidden" name="contacto_id" value="<?php echo $contacto_id; ?>">
                        <button type="submit" class="btn-eliminar">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </form>

            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; font-size: 13px; color: #666;">
                <i class="fas fa-info-circle"></i>
                <strong>Sugerencia:</strong> Responde a este correo en tu cliente de email directamente, luego cambia el estado a "Respondido" o "Cerrado".
            </div>
        </div>
    </div>
</body>
</html>
