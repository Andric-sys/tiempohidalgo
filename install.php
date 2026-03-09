<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - El Tiempo de Hidalgo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .paso {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .paso h3 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .paso-numero {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .paso p {
            color: #666;
            margin: 10px 0;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-large {
            display: block;
            text-align: center;
            padding: 18px;
            font-size: 18px;
            margin-top: 30px;
        }

        .info-box {
            background: #e7f3ff;
            border: 2px solid #0066cc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #0066cc;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }

        .code {
            background: #333;
            color: #0f0;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            font-size: 13px;
        }

        .checklist {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            margin: 8px 0;
            color: #333;
        }

        .check {
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
            font-size: 16px;
        }

        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 13px;
        }

        .credentials {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .credentials-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }

        .credentials-item {
            color: #856404;
            margin: 5px 0;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 El Tiempo de Hidalgo</h1>
            <p>Sistema de Gestión de Noticias y Reportajes</p>
        </div>

        <div class="content">
            <h2 style="color: #333; margin-bottom: 30px; text-align: center;">📋 Guía de Instalación</h2>

            <!-- Paso 1 -->
            <div class="paso">
                <h3>
                    <span class="paso-numero">1</span>
                    Verificar Requisitos
                </h3>
                <p>Asegúrate de tener instalados los siguientes requisitos:</p>
                <div class="checklist">
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span><strong>XAMPP</strong> instalado con Apache y MySQL corriendo</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span><strong>PHP 7.4</strong> o superior</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span><strong>MySQL 5.7</strong> o superior</span>
                    </div>
                </div>
                <p style="margin-top: 15px; color: #666; font-size: 14px;">
                    ℹ️ Los archivos del proyecto deben estar en: <code style="background: #f0f0f0; padding: 2px 5px;">C:\xampp\htdocs\tiempo_hidalgo</code>
                </p>
            </div>

            <!-- Paso 2 -->
            <div class="paso">
                <h3>
                    <span class="paso-numero">2</span>
                    Ejecutar las Migraciones
                </h3>
                <p>Este es el paso más importante. Accede a la página de migraciones:</p>
                <div class="code">http://localhost/tiempo_hidalgo/migrations/migrations.php</div>
                <p style="color: #666; margin-top: 15px;">El script ejecutará automáticamente:</p>
                <div class="checklist">
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Creación de la base de datos <strong>tiempo_hidalgo</strong></span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Creación de todas las tablas</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Creación del usuario administrador</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Creación de carpetas necesarias</span>
                    </div>
                </div>
                <button class="btn btn-large" onclick="window.open('migrations/migrations.php', '_blank')">
                    ▶️ Ejecutar Migraciones Ahora
                </button>
            </div>

            <!-- Paso 3 -->
            <div class="paso">
                <h3>
                    <span class="paso-numero">3</span>
                    Credenciales de Acceso
                </h3>
                <p>Después de ejecutar las migraciones, usa estas credenciales:</p>
                <div class="credentials">
                    <div class="credentials-title">🔐 Datos de Login</div>
                    <div class="credentials-item">Usuario: <strong>adminb</strong></div>
                    <div class="credentials-item">Contraseña: <strong>123456</strong></div>
                    <div class="credentials-item">Email: <strong>admin@tiempoHidalgo.local</strong></div>
                </div>
                <div class="info-box">
                    ⚠️ <strong>Importante:</strong> Cambia la contraseña después de tu primer login por seguridad.
                </div>
            </div>

            <!-- Paso 4 -->
            <div class="paso">
                <h3>
                    <span class="paso-numero">4</span>
                    Acceder a la Plataforma
                </h3>
                <p>Una vez completadas las migraciones, accede a:</p>
                <div class="code">http://localhost/tiempo_hidalgo</div>
                <p style="color: #666; margin-top: 15px;">Para acceder al panel administrativo:</p>
                <div class="code">http://localhost/tiempo_hidalgo/files/login.php</div>
            </div>

            <!-- Paso 5 -->
            <div class="paso">
                <h3>
                    <span class="paso-numero">5</span>
                    Comenzar a Crear Noticias
                </h3>
                <p>En el panel administrativo puedes:</p>
                <div class="checklist">
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Crear nuevas noticias</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Agregar imágenes y párrafos dinámicamente</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Editar y eliminar noticias</span>
                    </div>
                    <div class="checklist-item">
                        <span class="check">✓</span>
                        <span>Ver estadísticas</span>
                    </div>
                </div>
            </div>

            <!-- Botones principales -->
            <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <button class="btn" onclick="window.location.href='index.php'">
                    🏠 Ir al Inicio
                </button>
                <button class="btn" onclick="window.location.href='files/login.php'">
                    🔐 Panel de Login
                </button>
            </div>

            <!-- Solución de problemas -->
            <div class="info-box" style="margin-top: 40px;">
                <strong>¿Tienes problemas?</strong>
                <p style="margin-top: 10px;">
                    1. Verifica que XAMPP esté corriendo con Apache y MySQL<br>
                    2. Abre <a href="migrations/migrations.php" style="color: #0066cc; text-decoration: underline;">migrations.php</a> nuevamente<br>
                    3. Revisa los mensajes de error que aparezcan<br>
                    4. Consulta el archivo <a href="migrations/README.md" style="color: #0066cc; text-decoration: underline;">README.md</a> para más detalles
                </p>
            </div>
        </div>

        <div class="footer">
            <p>© 2026 El Tiempo de Hidalgo - Sistema de Gestión de Noticias</p>
            <p>Para soporte, contacta al administrador del sitio</p>
        </div>
    </div>

    <script>
        // Verificar si la BD ya está creada
        setTimeout(() => {
            fetch('config.php')
                .catch(() => {
                    // Si hay error, las migraciones aún no se han ejecutado
                });
        }, 1000);
    </script>
</body>
</html>
