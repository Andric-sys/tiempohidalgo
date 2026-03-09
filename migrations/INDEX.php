<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guía de Archivos - El Tiempo de Hidalgo</title>
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
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .file-item {
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .file-item:hover {
            background: #f0f0f0;
            border-left-color: #764ba2;
        }

        .file-name {
            font-weight: bold;
            color: #333;
            font-family: 'Courier New', monospace;
            margin-bottom: 5px;
        }

        .file-path {
            color: #999;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            margin-bottom: 8px;
        }

        .file-description {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            margin: 3px 3px 3px 0;
        }

        .badge-important {
            background: #ff6b6b;
            color: white;
        }

        .badge-migration {
            background: #4ecdc4;
            color: white;
        }

        .badge-doc {
            background: #95e1d3;
            color: #333;
        }

        .quick-start {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .quick-start h3 {
            color: #155724;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .quick-start a {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .quick-start a:hover {
            background: #218838;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .tree {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            margin: 15px 0;
            line-height: 1.6;
        }

        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Guía de Archivos</h1>
            <p>El Tiempo de Hidalgo - Base de Datos y Migraciones</p>
        </div>

        <div class="content">
            <!-- Quick Start -->
            <div class="quick-start">
                <h3>⚡ COMIENZA AQUÍ</h3>
                <p style="margin-bottom: 15px;">Ejecuta las migraciones para crear la base de datos:</p>
                <a href="migrations/migrations.php">▶️ Ejecutar Migraciones Ahora</a>
            </div>

            <!-- Sección 1: Migraciones -->
            <div class="section">
                <h2>🔧 Archivos de Migraciones (Carpeta: /migrations/)</h2>

                <div class="file-item">
                    <div class="file-name">📝 migrations.php</div>
                    <div class="file-path">/migrations/migrations.php</div>
                    <span class="badge badge-important">⭐ EJECUTAR ESTO</span>
                    <span class="badge badge-migration">PRINCIPAL</span>
                    <div class="file-description">
                        Script interactivo PHP que crea automáticamente toda la estructura de la base de datos. 
                        Crea tablas, índices, relaciones y el usuario admin. <strong>Este es el archivo más importante.</strong>
                        <br><br>
                        <strong>Cómo usar:</strong> Abre en tu navegador: 
                        <code style="background: #f0f0f0; padding: 2px 5px;">http://localhost/tiempo_hidalgo/migrations/migrations.php</code>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">📄 schema.sql</div>
                    <div class="file-path">/migrations/schema.sql</div>
                    <span class="badge badge-doc">SQL</span>
                    <span class="badge badge-migration">ALTERNATIVA</span>
                    <div class="file-description">
                        SQL completo para crear la base de datos. Útil si prefieres hacerlo manualmente en phpMyAdmin.
                        Contiene todos los comandos CREATE TABLE necesarios.
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">📖 README.md</div>
                    <div class="file-path">/migrations/README.md</div>
                    <span class="badge badge-doc">DOCUMENTACIÓN</span>
                    <div class="file-description">
                        Documentación completa sobre las migraciones. Incluye instrucciones paso a paso, 
                        descripción de cada tabla, credenciales y solución de problemas.
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">📊 DIAGRAMA_BD.md</div>
                    <div class="file-path">/migrations/DIAGRAMA_BD.md</div>
                    <span class="badge badge-doc">REFERENCIA</span>
                    <div class="file-description">
                        Diagrama visual de la base de datos. Muestra la estructura de cada tabla, 
                        las relaciones entre ellas y ejemplos de consultas SQL útiles.
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">📋 RESUMEN_COMPLETO.txt</div>
                    <div class="file-path">/migrations/RESUMEN_COMPLETO.txt</div>
                    <span class="badge badge-doc">RESUMEN</span>
                    <div class="file-description">
                        Resumen ejecutivo de todo lo creado. Rápida referencia de tablas, campos y características.
                    </div>
                </div>
            </div>

            <!-- Sección 2: Documentación General -->
            <div class="section">
                <h2>📚 Archivos de Documentación (Raíz del proyecto)</h2>

                <div class="file-item">
                    <div class="file-name">⚡ INICIO_RAPIDO.md</div>
                    <div class="file-path">/INICIO_RAPIDO.md</div>
                    <span class="badge badge-doc">GUÍA</span>
                    <div class="file-description">
                        Guía de inicio rápido (3 minutos). Primeros pasos, URLs principales y credenciales.
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">🔧 install.php</div>
                    <div class="file-path">/install.php</div>
                    <span class="badge badge-doc">PÁGINA WEB</span>
                    <div class="file-description">
                        Página web interactiva de instalación. Muestra los requisitos, pasos y acciones.
                        Accede en: <code style="background: #f0f0f0; padding: 2px 5px;">http://localhost/tiempo_hidalgo/install.php</code>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-name">📊 database_info.php</div>
                    <div class="file-path">/database_info.php</div>
                    <span class="badge badge-doc">INFORMACIÓN</span>
                    <div class="file-description">
                        Página informativa visual sobre la base de datos. Muestra tablas, relaciones y consultas útiles.
                        Accede en: <code style="background: #f0f0f0; padding: 2px 5px;">http://localhost/tiempo_hidalgo/database_info.php</code>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Archivos de Configuración -->
            <div class="section">
                <h2>⚙️ Archivos de Configuración</h2>

                <div class="file-item">
                    <div class="file-name">⚙️ config.php</div>
                    <div class="file-path">/config.php</div>
                    <span class="badge badge-important">CRÍTICO</span>
                    <div class="file-description">
                        Configuración general del sitio. Conexión a base de datos, definiciones de constantes 
                        y funciones auxiliares. Se incluye en prácticamente todos los archivos PHP.
                    </div>
                </div>
            </div>

            <!-- Sección 4: Estructura de Carpetas -->
            <div class="section">
                <h2>📁 Estructura de Carpetas</h2>

                <div class="tree">
tiempo_hidalgo/
│
├── 🔧 ARCHIVOS PRINCIPALES
│   ├── config.php            (Configuración)
│   ├── index.php             (Página de inicio)
│   ├── header.php            (Encabezado y navbar)
│   ├── pie_pagina.php        (Footer)
│   ├── noticia.php           (Ver noticia completa)
│   └── install.php           (Instalación web)
│
├── 📖 DOCUMENTACIÓN
│   ├── INICIO_RAPIDO.md      (Guía de 3 min)
│   ├── database_info.php     (Info BD visual)
│   ├── migrations/
│   │   ├── migrations.php    ⭐ (EJECUTAR ESTO)
│   │   ├── schema.sql        (SQL completo)
│   │   ├── README.md         (Documentación)
│   │   ├── DIAGRAMA_BD.md    (Diagrama)
│   │   └── RESUMEN_COMPLETO.txt
│   │
│   └── files/
│       ├── admin.php         (Panel admin)
│       ├── alta_noticia.php  (Crear noticia)
│       ├── editar_noticia.php
│       ├── ver_noticia.php
│       ├── login.php         (Formulario login)
│       ├── auth.php          (Verificación)
│       └── logout.php        (Cerrar sesión)
│
├── 🎨 ASSETS
│   ├── css/
│   │   └── style.css         (Estilos)
│   ├── js/
│   │   └── script.js         (JavaScript)
│   └── images/               (Imágenes estáticas)
│
└── 📦 DATOS
    └── uploads/
        ├── noticias/         (Imágenes de noticias)
        └── galeria/          (Imágenes de galería)
                </div>
            </div>

            <!-- Sección 5: URLs Importantes -->
            <div class="section">
                <h2>🔗 URLs Importantes</h2>

                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                        <th style="padding: 10px; text-align: left;">Función</th>
                        <th style="padding: 10px; text-align: left;">URL</th>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">⭐ Migraciones (PRIMERO)</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo/migrations/migrations.php</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">🏠 Página Principal</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">🔐 Login Admin</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo/files/login.php</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">📊 Panel Admin</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo/files/admin.php</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">➕ Crear Noticia</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo/files/alta_noticia.php</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">📚 Info BD</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/tiempo_hidalgo/database_info.php</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">💻 phpMyAdmin</td>
                        <td style="padding: 10px; font-family: monospace; color: #0066cc;">http://localhost/phpmyadmin</td>
                    </tr>
                </table>
            </div>

            <!-- Sección 6: Credenciales -->
            <div class="section">
                <h2>🔑 Credenciales por Defecto</h2>

                <div class="info-box">
                    <strong>Usuario Admin:</strong><br>
                    Usuario: <code>adminb</code><br>
                    Contraseña: <code>123456</code><br>
                    Email: <code>admin@tiempoHidalgo.local</code>
                    <br><br>
                    <strong>⚠️ Importante:</strong> Cambia la contraseña después del primer login
                </div>
            </div>

            <!-- Sección 7: Orden de Ejecución -->
            <div class="section">
                <h2>📋 Orden Recomendado de Lectura</h2>

                <ol style="padding-left: 20px; line-height: 2;">
                    <li><strong>1️⃣ PRIMERO:</strong> Ejecuta <code>migrations.php</code></li>
                    <li><strong>2️⃣ SEGUNDO:</strong> Lee <code>INICIO_RAPIDO.md</code></li>
                    <li><strong>3️⃣ TERCERO:</strong> Consulta <code>database_info.php</code> en navegador</li>
                    <li><strong>4️⃣ REFERENCIA:</strong> Lee <code>DIAGRAMA_BD.md</code> para entender la BD</li>
                    <li><strong>5️⃣ PROFUNDO:</strong> Lee <code>README.md</code> en /migrations/</li>
                </ol>
            </div>

            <!-- Sección 8: Tabla de Archivos -->
            <div class="section">
                <h2>📋 Tabla Resumen de Archivos</h2>

                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px;">
                    <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                        <th style="padding: 10px; text-align: left;">Archivo</th>
                        <th style="padding: 10px; text-align: left;">Tipo</th>
                        <th style="padding: 10px; text-align: left;">Propósito</th>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><strong>migrations.php</strong></td>
                        <td style="padding: 10px;">PHP</td>
                        <td style="padding: 10px;">⭐ Crear BD automáticamente</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">schema.sql</td>
                        <td style="padding: 10px;">SQL</td>
                        <td style="padding: 10px;">SQL completo alternativo</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">README.md</td>
                        <td style="padding: 10px;">Markdown</td>
                        <td style="padding: 10px;">Documentación detallada</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">DIAGRAMA_BD.md</td>
                        <td style="padding: 10px;">Markdown</td>
                        <td style="padding: 10px;">Diagrama visual y consultas</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">INICIO_RAPIDO.md</td>
                        <td style="padding: 10px;">Markdown</td>
                        <td style="padding: 10px;">Guía rápida (3 min)</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">install.php</td>
                        <td style="padding: 10px;">PHP Web</td>
                        <td style="padding: 10px;">Instalación visual</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">database_info.php</td>
                        <td style="padding: 10px;">PHP Web</td>
                        <td style="padding: 10px;">Información visual de BD</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">RESUMEN_COMPLETO.txt</td>
                        <td style="padding: 10px;">Texto</td>
                        <td style="padding: 10px;">Resumen ejecutivo</td>
                    </tr>
                </table>
            </div>

            <!-- Botones finales -->
            <div style="text-align: center; margin-top: 40px;">
                <h2 style="color: #667eea; margin-bottom: 20px;">🚀 Acciones Rápidas</h2>
                <a href="migrations/migrations.php" style="display: inline-block; background: #28a745; color: white; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 5px; transition: background 0.3s; font-size: 16px;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                    ⭐ Ejecutar Migraciones
                </a>
                <a href="index.php" style="display: inline-block; background: #667eea; color: white; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 5px; transition: background 0.3s; font-size: 16px;" onmouseover="this.style.background='#764ba2'" onmouseout="this.style.background='#667eea'">
                    🏠 Ir al Inicio
                </a>
                <a href="files/login.php" style="display: inline-block; background: #0066cc; color: white; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 5px; transition: background 0.3s; font-size: 16px;" onmouseover="this.style.background='#0052a3'" onmouseout="this.style.background='#0066cc'">
                    🔐 Login Admin
                </a>
            </div>
        </div>

        <div class="footer">
            <p>© 2026 El Tiempo de Hidalgo - Sistema de Gestión de Noticias</p>
            <p style="margin-top: 10px;">Guía de Archivos - Base de Datos y Migraciones</p>
        </div>
    </div>
</body>
</html>
