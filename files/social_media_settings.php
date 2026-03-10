<?php
session_start();
require_once '../config.php';

// Verificar si el usuario está autenticado y es admin
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario de guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $red_social = isset($_POST['red_social']) ? sanitizarEntrada($_POST['red_social']) : '';
    $access_token = isset($_POST['access_token']) ? trim($_POST['access_token']) : '';
    $page_id = isset($_POST['page_id']) ? sanitizarEntrada($_POST['page_id']) : '';
    $account_id = isset($_POST['account_id']) ? sanitizarEntrada($_POST['account_id']) : '';
    $bearer_token = isset($_POST['bearer_token']) ? trim($_POST['bearer_token']) : '';
    $activo = isset($_POST['activo']) ? 1 : 0;

    if (empty($red_social)) {
        $mensaje = 'Debes seleccionar una red social';
        $tipo_mensaje = 'error';
    } else {
        // Actualizar o crear credenciales
        $sql = "UPDATE social_media_credenciales 
                SET access_token = ?, page_id = ?, account_id = ?, bearer_token = ?, activo = ?
                WHERE red_social = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $mensaje = 'Error en la consulta: ' . $conn->error;
            $tipo_mensaje = 'error';
        } else {
            $stmt->bind_param('ssssss', $access_token, $page_id, $account_id, $bearer_token, $activo, $red_social);
            
            if ($stmt->execute()) {
                $mensaje = "Credenciales de $red_social guardadas exitosamente";
                $tipo_mensaje = 'exito';
            } else {
                $mensaje = 'Error al guardar: ' . $conn->error;
                $tipo_mensaje = 'error';
            }
        }
    }
}

// Obtener credenciales actuales
$credenciales = [];
$sql = "SELECT * FROM social_media_credenciales";
$resultado = $conn->query($sql);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $credenciales[$fila['red_social']] = $fila;
    }
}

function sanitizarEntrada($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

// Obtener credenciales seleccionadas para editar
$red_seleccionada = isset($_GET['red']) ? sanitizarEntrada($_GET['red']) : 'facebook';
$credencial_actual = isset($credenciales[$red_seleccionada]) ? $credenciales[$red_seleccionada] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Redes Sociales - El Tiempo de Hidalgo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 950px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid #d0d0d0;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000000;
            padding-bottom: 20px;
        }

        .admin-header h1 {
            color: #000000;
            font-size: 28px;
            font-family: 'Georgia', serif;
            font-weight: bold;
            margin: 0;
        }

        .admin-header a {
            background: #333333;
            color: white;
            padding: 10px 20px;
            border-radius: 0;
            text-decoration: none;
            border: 1px solid #555555;
            transition: background 0.3s;
        }

        .admin-header a:hover {
            background: #555555;
        }

        .mensaje {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0;
            text-align: center;
            font-weight: bold;
            border: 1px solid #cccccc;
        }

        .mensaje.exito {
            background: #f0f0f0;
            color: #155724;
        }

        .mensaje.error {
            background: #f0f0f0;
            color: #721c24;
        }

        .container-redes {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .redes-menu {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .red-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            cursor: pointer;
            border-bottom: 1px solid #e0e0e0;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        .red-item:last-child {
            border-bottom: none;
        }

        .red-item:hover {
            background: #f0f0f0;
        }

        .red-item.activo {
            background: #e3f2fd;
            border-left: 4px solid #1877F2;
            padding-left: 11px;
            font-weight: bold;
        }

        .red-item i {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
        }

        .form-container {
            background: #f9f9f9;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #cccccc;
            border-radius: 0;
            font-size: 14px;
            box-sizing: border-box;
            font-family: monospace;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #000000;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group.checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group.checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-group.checkbox label {
            margin: 0;
            cursor: pointer;
        }

        .btn {
            padding: 12px 30px;
            border: 1px solid #555555;
            border-radius: 0;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            background: #333333;
            color: white;
        }

        .btn:hover {
            background: #555555;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .instrucciones {
            background: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
            border-radius: 0;
        }

        .instrucciones h4 {
            margin-top: 0;
            color: #856404;
        }

        .instrucciones ol {
            margin: 10px 0;
            padding-left: 20px;
        }

        .instrucciones li {
            margin-bottom: 8px;
            color: #856404;
        }

        .instrucciones a {
            color: #856404;
            text-decoration: underline;
        }

        .estado-credencial {
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
            font-size: 13px;
        }

        .estado-credencial.configurado {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .estado-credencial.no-configurado {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .token-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
            border-left: 3px solid #999;
        }

        @media (max-width: 768px) {
            .container-redes {
                grid-template-columns: 1fr;
            }

            .redes-menu {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .red-item {
                border-bottom: none;
                border-right: 1px solid #e0e0e0;
            }

            .red-item:nth-child(4n) {
                border-right: none;
            }
        }
    </style>
</head>
<body style="background-color: #f5f5f5;">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-cogs"></i> Configurar Redes Sociales</h1>
            <a href="admin.php"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="container-redes">
            <!-- MENÚ DE REDES SOCIALES -->
            <div class="redes-menu">
                <a href="?red=facebook" class="red-item <?php echo $red_seleccionada === 'facebook' ? 'activo' : ''; ?>">
                    <i class="fab fa-facebook" style="color: #1877F2;"></i>
                    <span>Facebook</span>
                </a>
                <a href="?red=instagram" class="red-item <?php echo $red_seleccionada === 'instagram' ? 'activo' : ''; ?>">
                    <i class="fab fa-instagram" style="color: #E4405F;"></i>
                    <span>Instagram</span>
                </a>
                <a href="?red=twitter" class="red-item <?php echo $red_seleccionada === 'twitter' ? 'activo' : ''; ?>">
                    <i class="fab fa-x-twitter"></i>
                    <span>X (Twitter)</span>
                </a>
            </div>

            <!-- FORMULARIO DE CONFIGURACIÓN -->
            <div>
                <!-- INSTRUCCIONES POR RED SOCIAL -->
                <?php if ($red_seleccionada === 'facebook'): ?>
                    <div class="instrucciones">
                        <h4><i class="fas fa-info-circle"></i> Cómo obtener credenciales de Facebook</h4>
                        <ol>
                            <li>Ve a <a href="https://developers.facebook.com/" target="_blank">developers.facebook.com</a></li>
                            <li>Inicia sesión o crea una cuenta de desarrollador</li>
                            <li>Crea una nueva aplicación tipo "Business"</li>
                            <li>En la sección "Configuración", obtén tu <strong>Access Token</strong></li>
                            <li>Ve a la página de Facebook de tu negocio y obtén el <strong>Page ID</strong></li>
                            <li>Los tokens de acceso tienen una duración limitada, puedes generar uno que no expire (Page Access Token)</li>
                        </ol>
                    </div>
                <?php elseif ($red_seleccionada === 'instagram'): ?>
                    <div class="instrucciones">
                        <h4><i class="fas fa-info-circle"></i> Cómo obtener credenciales de Instagram</h4>
                        <ol>
                            <li>Ve a <a href="https://business.instagram.com/" target="_blank">business.instagram.com</a></li>
                            <li>Convierte tu cuenta de Instagram a una cuenta de negocio</li>
                            <li>Usa la misma aplicación Facebook que creaste anteriormente</li>
                            <li>En Meta Business Suite, obtén el <strong>Account ID</strong> de Instagram (Instagram Business Account ID)</li>
                            <li>Usa el mismo <strong>Access Token</strong> de Facebook</li>
                            <li>Referencia: <a href="https://developers.facebook.com/docs/instagram-api/" target="_blank">Documentación Instagram Graph API</a></li>
                        </ol>
                    </div>
                <?php elseif ($red_seleccionada === 'twitter'): ?>
                    <div class="instrucciones">
                        <h4><i class="fas fa-info-circle"></i> Cómo obtener credenciales de Twitter/X</h4>
                        <ol>
                            <li>Ve a <a href="https://developer.twitter.com/" target="_blank">developer.twitter.com</a></li>
                            <li>Inicia sesión con tu cuenta de Twitter</li>
                            <li>Crea una nueva aplicación</li>
                            <li>Ve a "Keys and tokens" en los ajustes de la aplicación</li>
                            <li>Habilita "Read and Write" permissions y "User context authentication"</li>
                            <li>Genera o copia tu <strong>Bearer Token</strong></li>
                            <li>Referencia: <a href="https://developer.twitter.com/en/docs/twitter-api/guides/authentication" target="_blank">Documentación de Autenticación</a></li>
                        </ol>
                    </div>
                <?php endif; ?>

                <!-- FORMULARIO -->
                <div class="form-container">
                    <form method="POST">
                        <input type="hidden" name="red_social" value="<?php echo $red_seleccionada; ?>">

                        <?php if ($red_seleccionada === 'facebook' || $red_seleccionada === 'instagram'): ?>
                            
                            <div class="form-group">
                                <label for="access_token"><i class="fas fa-key"></i> Access Token de Facebook</label>
                                <textarea name="access_token" id="access_token" placeholder="Pega aquí tu Access Token..."><?php echo isset($credencial_actual['access_token']) ? htmlspecialchars($credencial_actual['access_token']) : ''; ?></textarea>
                                <div class="token-info">
                                    <strong>Importante:</strong> Usa un "Page Access Token" que no expire. Puedes generar uno en Facebook Developer Console.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="page_id"><i class="fas fa-id-card"></i> Page ID (para Facebook)</label>
                                <input type="text" name="page_id" id="page_id" placeholder="Ej: 123456789" value="<?php echo isset($credencial_actual['page_id']) ? htmlspecialchars($credencial_actual['page_id']) : ''; ?>">
                                <div class="token-info">
                                    Lo encontrarás en la URL de tu página: facebook.com/<strong>123456789</strong>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="account_id"><i class="fas fa-id-card"></i> Instagram Account ID (Business Account)</label>
                                <input type="text" name="account_id" id="account_id" placeholder="Ej: 987654321" value="<?php echo isset($credencial_actual['account_id']) ? htmlspecialchars($credencial_actual['account_id']) : ''; ?>">
                                <div class="token-info">
                                    Para obtenerlo: Ve a Meta Business Suite → Instagram → Configuración → Cuenta
                                </div>
                            </div>

                        <?php elseif ($red_seleccionada === 'twitter'): ?>

                            <div class="form-group">
                                <label for="bearer_token"><i class="fas fa-key"></i> Bearer Token de Twitter/X (API v2)</label>
                                <textarea name="bearer_token" id="bearer_token" placeholder="Pega aquí tu Bearer Token..."><?php echo isset($credencial_actual['bearer_token']) ? htmlspecialchars($credencial_actual['bearer_token']) : ''; ?></textarea>
                                <div class="token-info">
                                    <strong>Importante:</strong> Actualiza tus credenciales regularmente. Este token permite la autenticación de tu aplicación.
                                </div>
                            </div>

                        <?php endif; ?>

                        <!-- Estado actual de credenciales -->
                        <div style="margin-bottom: 20px;">
                            <?php 
                            $tiene_credenciales = false;
                            if ($red_seleccionada === 'facebook' || $red_seleccionada === 'instagram') {
                                $tiene_credenciales = !empty($credencial_actual['access_token']);
                            } elseif ($red_seleccionada === 'twitter') {
                                $tiene_credenciales = !empty($credencial_actual['bearer_token']);
                            }
                            ?>
                            <div class="estado-credencial <?php echo $tiene_credenciales ? 'configurado' : 'no-configurado'; ?>">
                                <i class="fas <?php echo $tiene_credenciales ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <?php echo $tiene_credenciales ? '<strong>Credenciales configuradas</strong>' : '<strong>No configurado aún</strong>'; ?>
                                <?php echo $tiene_credenciales && isset($credencial_actual['fecha_actualizacion']) ? ' - Actualizado: ' . $credencial_actual['fecha_actualizacion'] : ''; ?>
                            </div>
                        </div>

                        <!-- Checkbox Activo/Inactivo -->
                        <div class="form-group checkbox">
                            <input type="checkbox" name="activo" id="activo" value="1" <?php echo (isset($credencial_actual['activo']) && $credencial_actual['activo']) ? 'checked' : ''; ?>>
                            <label for="activo">
                                <i class="fas fa-toggle-on"></i> Activar publicación automática en esta red
                            </label>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn">
                                <i class="fas fa-save"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DE PRIVACIDAD Y SEGURIDAD -->
        <div style="background: #e8f4f8; padding: 20px; border-radius: 4px; margin-top: 30px; border-left: 4px solid #0288d1;">
            <h3 style="color: #01579b; margin-top: 0;"><i class="fas fa-shield-alt"></i> Seguridad de Credenciales</h3>
            <ul style="color: #01579b; line-height: 1.8;">
                <li><strong>Nunca</strong> compartas tus tokens o credenciales con terceros</li>
                <li>Los tokens se almacenan en la base de datos de tu servidor</li>
                <li>Considera usar variables de entorno en producción</li>
                <li>Regenera los tokens regularmente para mayor seguridad</li>
                <li>Si sospechas que un token fue comprometido, regeneralo inmediatamente desde el panel del desarrollador correspondiente</li>
                <li>Este sistema implementa acceso restringido solo para usuarios autenticados</li>
            </ul>
        </div>
    </div>
</body>
</html>
