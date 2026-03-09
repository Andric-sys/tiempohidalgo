<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base de Datos - El Tiempo de Hidalgo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            text-align: center;
        }

        .header h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 18px;
            opacity: 0.9;
        }

        .content {
            padding: 50px;
        }

        .section {
            margin-bottom: 50px;
        }

        .section h2 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: #f9f9f9;
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 25px;
            transition: all 0.3s;
            border-left: 4px solid #667eea;
        }

        .card:hover {
            border-left-color: #764ba2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .card h3 {
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-icon {
            font-size: 24px;
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .table-responsive {
            overflow-x: auto;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin: 20px 0;
        }

        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
        }

        .code-block code {
            color: #f8f8f2;
        }

        .highlight {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .highlight strong {
            color: #856404;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #666;
        }

        .diagram {
            background: #f5f5f5;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            line-height: 1.8;
        }

        .list-item {
            display: flex;
            align-items: flex-start;
            margin: 15px 0;
            gap: 15px;
        }

        .list-icon {
            flex-shrink: 0;
            color: #28a745;
            font-size: 24px;
            width: 30px;
            text-align: center;
        }

        .list-content {
            flex-grow: 1;
        }

        .list-content strong {
            color: #333;
        }

        .list-content p {
            color: #666;
            margin-top: 5px;
            font-size: 14px;
        }

        .warning {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            color: #721c24;
        }

        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            color: #155724;
        }

        .footer {
            background: #f5f5f5;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #eee;
            color: #666;
        }

        @media (max-width: 768px) {
            .header {
                padding: 30px;
            }

            .header h1 {
                font-size: 28px;
            }

            .content {
                padding: 20px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Base de Datos</h1>
            <p>El Tiempo de Hidalgo - Estructura Completa</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Sección 1: Inicio Rápido -->
            <div class="section">
                <h2>🚀 Inicio Rápido</h2>
                
                <div class="success">
                    <strong>✅ ¡Todo está listo!</strong> La base de datos ya ha sido configurada automáticamente.
                </div>

                <div class="grid">
                    <div class="card">
                        <h3><span class="card-icon">1️⃣</span> Ejecutar Migraciones</h3>
                        <p>Si es tu primera vez, accede a:</p>
                        <div class="code-block">http://localhost/tiempo_hidalgo/migrations/migrations.php</div>
                    </div>

                    <div class="card">
                        <h3><span class="card-icon">2️⃣</span> Ir al Sitio</h3>
                        <p>Accede a la página principal:</p>
                        <div class="code-block">http://localhost/tiempo_hidalgo</div>
                    </div>

                    <div class="card">
                        <h3><span class="card-icon">3️⃣</span> Login Admin</h3>
                        <p>Panel administrativo:</p>
                        <div class="code-block">http://localhost/tiempo_hidalgo/files/login.php</div>
                    </div>
                </div>

                <div class="highlight">
                    <strong>👤 Credenciales por defecto:</strong><br>
                    Usuario: <code>adminb</code> | Contraseña: <code>123456</code>
                </div>
            </div>

            <!-- Sección 2: Tablas -->
            <div class="section">
                <h2>📋 Tablas de la Base de Datos</h2>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>📁 Tabla</th>
                                <th>📝 Descripción</th>
                                <th>🔑 Campos Principales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>usuarios</strong></td>
                                <td>Administradores del sitio</td>
                                <td>id, usuario, contrasena, email, estado</td>
                            </tr>
                            <tr>
                                <td><strong>noticias</strong></td>
                                <td>Noticias y reportajes</td>
                                <td>id, titulo, descripcion, estado, autor_id</td>
                            </tr>
                            <tr>
                                <td><strong>bloques</strong></td>
                                <td>Contenido flexible (imagen + párrafo)</td>
                                <td>id, noticia_id, tipo, contenido, orden</td>
                            </tr>
                            <tr>
                                <td><strong>galeria</strong></td>
                                <td>Galería de imágenes</td>
                                <td>id, noticia_id, titulo, imagen, descripcion</td>
                            </tr>
                            <tr>
                                <td><strong>categorias</strong></td>
                                <td>Categorías de noticias</td>
                                <td>id, nombre, descripcion, slug</td>
                            </tr>
                            <tr>
                                <td><strong>noticia_categoria</strong></td>
                                <td>Relación N:M noticia-categoría</td>
                                <td>noticia_id, categoria_id</td>
                            </tr>
                            <tr>
                                <td><strong>contactos</strong></td>
                                <td>Mensajes de contacto</td>
                                <td>id, nombre, email, asunto, mensaje, estado</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sección 3: Estructura -->
            <div class="section">
                <h2>🔗 Estructura de Relaciones</h2>

                <div class="diagram">
USUARIOS (1:N)──────► NOTICIAS (1:N)───────► BLOQUES<br>
                           │                       │<br>
                           ├──► GALERIA            ├─ tipo: 'imagen'<br>
                           │                       ├─ tipo: 'parrafo'<br>
                           └──► NOTICIA_CATEGORIA  └─ orden: 1,2,3...<br>
                                      │<br>
                                      └──► CATEGORIAS<br>
<br>
CONTACTOS (independiente)<br>
                </div>

                <p><strong>Características:</strong></p>
                <div class="list-item">
                    <div class="list-icon">✅</div>
                    <div class="list-content">
                        <strong>Integridad Referencial:</strong>
                        <p>Las relaciones garantizan que no hay datos huérfanos</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">✅</div>
                    <div class="list-content">
                        <strong>Cascada de Eliminación:</strong>
                        <p>Al eliminar una noticia, se eliminan automáticamente todos sus bloques</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">✅</div>
                    <div class="list-content">
                        <strong>Índices Optimizados:</strong>
                        <p>Las búsquedas frecuentes están indexadas para mejor rendimiento</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">✅</div>
                    <div class="list-content">
                        <strong>UTF-8 Completo:</strong>
                        <p>Soporte para caracteres especiales, acentos y emojis</p>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Sistema de Bloques -->
            <div class="section">
                <h2>🔨 Sistema Flexible de Bloques</h2>

                <p>El contenido de cada noticia se divide en bloques que pueden ser:</p>

                <div class="grid">
                    <div class="card">
                        <h3><span class="card-icon">🖼️</span> Imagen</h3>
                        <p>Bloques de tipo 'imagen'. Contiene la ruta al archivo subido.</p>
                    </div>

                    <div class="card">
                        <h3><span class="card-icon">📝</span> Párrafo</h3>
                        <p>Bloques de tipo 'parrafo'. Contiene texto formateado.</p>
                    </div>

                    <div class="card">
                        <h3><span class="card-icon">📌</span> Título</h3>
                        <p>Bloques de tipo 'titulo'. Para títulos de sección.</p>
                    </div>

                    <div class="card">
                        <h3><span class="card-icon">📎</span> Subtítulo</h3>
                        <p>Bloques de tipo 'subtitulo'. Para subtítulos.</p>
                    </div>
                </div>

                <p><strong>Ejemplo de estructura de noticia:</strong></p>

                <div class="code-block">Noticia #1: "Elecciones 2026"
├── Bloque 1: imagen → uploads/noticias/portada.jpg
├── Bloque 2: parrafo → "Los ciudadanos..."
├── Bloque 3: imagen → uploads/noticias/votacion.jpg
└── Bloque 4: parrafo → "Los resultados..."
                </div>

                <p>Cada bloque tiene un <code>orden</code> que determina en qué secuencia aparecen en la página.</p>
            </div>

            <!-- Sección 5: Estados -->
            <div class="section">
                <h2>⚙️ Estados y Enumeraciones</h2>

                <div class="grid">
                    <div class="card">
                        <h3>Estados de Noticia</h3>
                        <p><strong>borrador:</strong> En construcción</p>
                        <p><strong>publicado:</strong> Visible en el sitio</p>
                        <p><strong>archivado:</strong> Oculta pero guardada</p>
                    </div>

                    <div class="card">
                        <h3>Estados de Usuario</h3>
                        <p><strong>activo:</strong> Puede hacer login</p>
                        <p><strong>inactivo:</strong> Bloqueado del sistema</p>
                    </div>

                    <div class="card">
                        <h3>Estados de Contacto</h3>
                        <p><strong>nuevo:</strong> Sin responder</p>
                        <p><strong>respondido:</strong> Ya respondido</p>
                        <p><strong>cerrado:</strong> Consulta cerrada</p>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Consultas Útiles -->
            <div class="section">
                <h2>💾 Consultas SQL Útiles</h2>

                <p><strong>Obtener últimas noticias publicadas:</strong></p>
                <div class="code-block">SELECT n.id, n.titulo, n.fecha_creacion
FROM noticias n
WHERE n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC
LIMIT 10;</div>

                <p><strong>Obtener bloques de una noticia en orden:</strong></p>
                <div class="code-block">SELECT * FROM bloques
WHERE noticia_id = 1
ORDER BY orden ASC;</div>

                <p><strong>Obtener noticias de una categoría:</strong></p>
                <div class="code-block">SELECT n.* FROM noticias n
JOIN noticia_categoria nc ON n.id = nc.noticia_id
WHERE nc.categoria_id = 1 AND n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC;</div>

                <p><strong>Obtener la primera imagen de cada noticia:</strong></p>
                <div class="code-block">SELECT n.id, n.titulo,
  (SELECT contenido FROM bloques 
   WHERE noticia_id = n.id AND tipo = 'imagen' 
   ORDER BY orden LIMIT 1) as primera_imagen
FROM noticias n
WHERE n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC;</div>
            </div>

            <!-- Sección 7: Funciones Auxiliares -->
            <div class="section">
                <h2>🛠️ Funciones Auxiliares en PHP</h2>

                <p>El archivo <code>config.php</code> proporciona funciones útiles:</p>

                <div class="code-block">// Escapar caracteres especiales
$string_seguro = escape_string($entrada);

// Preparar consulta parametrizada
$stmt = prepare_query("SELECT * FROM noticias WHERE id = ?");

// Ejecutar consulta
$resultado = execute_query("SELECT * FROM noticias");

// Obtener último ID insertado
$id_nuevo = get_last_id();

// Obtener filas afectadas
$filas = get_affected_rows();</div>
            </div>

            <!-- Sección 8: Seguridad -->
            <div class="section">
                <h2>🔒 Consideraciones de Seguridad</h2>

                <div class="warning">
                    <strong>⚠️ Importante para Producción:</strong>
                </div>

                <div class="list-item">
                    <div class="list-icon">⚠️</div>
                    <div class="list-content">
                        <strong>Contraseñas:</strong>
                        <p>Actualmente usa MD5. En producción usa bcrypt o argon2.</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">⚠️</div>
                    <div class="list-content">
                        <strong>Validación:</strong>
                        <p>Siempre valida y sanitiza la entrada del usuario.</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">⚠️</div>
                    <div class="list-content">
                        <strong>HTTPS:</strong>
                        <p>En producción, siempre usa HTTPS para proteger datos sensibles.</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">⚠️</div>
                    <div class="list-content">
                        <strong>Backups:</strong>
                        <p>Realiza backups regulares de la base de datos.</p>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon">⚠️</div>
                    <div class="list-content">
                        <strong>Permisos:</strong>
                        <p>Configura permisos correctos en las carpetas de upload.</p>
                    </div>
                </div>
            </div>

            <!-- Sección 9: Documentación -->
            <div class="section">
                <h2>📚 Documentación</h2>

                <div class="grid">
                    <div class="card">
                        <h3>README.md</h3>
                        <p>Guía completa de migraciones y estructura.</p>
                        <p><small>/migrations/README.md</small></p>
                    </div>

                    <div class="card">
                        <h3>DIAGRAMA_BD.md</h3>
                        <p>Diagrama visual y explicación de cada tabla.</p>
                        <p><small>/migrations/DIAGRAMA_BD.md</small></p>
                    </div>

                    <div class="card">
                        <h3>schema.sql</h3>
                        <p>SQL completo para crear la BD manualmente.</p>
                        <p><small>/migrations/schema.sql</small></p>
                    </div>
                </div>

                <div class="btn-group">
                    <a href="migrations/README.md" class="btn">📖 Ver README</a>
                    <a href="migrations/DIAGRAMA_BD.md" class="btn">📊 Ver Diagrama</a>
                    <a href="migrations/schema.sql" class="btn btn-secondary">💾 Ver SQL</a>
                </div>
            </div>

            <!-- Sección 10: Botones -->
            <div class="section" style="text-align: center;">
                <h2>🎯 Acciones</h2>
                <div class="btn-group" style="justify-content: center;">
                    <a href="index.php" class="btn">🏠 Ir al Inicio</a>
                    <a href="files/login.php" class="btn">🔐 Panel Admin</a>
                    <a href="migrations/migrations.php" class="btn">⚙️ Migraciones</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2026 El Tiempo de Hidalgo - Sistema de Gestión de Noticias</p>
            <p style="margin-top: 10px; font-size: 12px;">Última actualización: 23 de febrero de 2026</p>
        </div>
    </div>
</body>
</html>
