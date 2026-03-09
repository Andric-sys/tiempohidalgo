# 🏗️ ESTRUCTURA COMPLETA DEL PROYECTO v2.5

## 📂 Árbol de Directorios

```
tiempo_hidalgo/
│
├── 📄 index.php                          ← Página principal con carousel y noticias
├── 📄 galeria.php                        ← ✨ NUEVO: Galería de imágenes interactiva
├── 📄 noticia.php                        ← Vista pública de noticia individual
├── 📄 header.php                         ← 🔄 ACTUALIZADO: Header con navegación
├── 📄 pie_pagina.php                     ← Footer del sitio
├── 📄 config.php                         ← Configuración y conexión a BD
│
├── 📂 files/
│   ├── 📄 admin.php                      ← 🔄 ACTUALIZADO: Panel admin con pestañas
│   ├── 📄 contacto.php                   ← ✨ NUEVO: Formulario de contacto
│   ├── 📄 ver_contacto.php               ← ✨ NUEVO: Detalle de contacto en admin
│   ├── 📄 alta_noticia.php               ← Crear nueva noticia
│   ├── 📄 editar_noticia.php             ← Editar noticia
│   ├── 📄 ver_noticia.php                ← Vista de noticia en admin
│   ├── 📄 login.php                      ← Formulario de login
│   ├── 📄 auth.php                       ← Autenticación
│   ├── 📄 logout.php                     ← Cerrar sesión
│   └── 📂 uploads/
│       └── (Imágenes de contactos - carpeta vacía por defecto)
│
├── 📂 assets/
│   └── 📂 css/
│       └── 📄 style.css                  ← Estilos globales
│   └── 📂 js/
│       └── 📄 script.js                  ← JavaScript global
│
├── 📂 uploads/
│   ├── 📂 noticias/
│   │   └── (Imágenes de noticias)
│   └── 📂 galeria/
│       └── (Imágenes de galería)
│
├── 📂 ajax/
│   └── (Archivos para peticiones AJAX - carpeta vacía)
│
├── 📂 migrations/
│   ├── 📄 migrations.php                 ← Script de migración
│   ├── 📄 schema.sql                     ← SQL schema
│   ├── 📄 INDEX.php                      ← Índice de documentación
│   ├── 📄 README.md                      ← Readme de BD
│   ├── 📄 DIAGRAMA_BD.md                 ← Diagrama de relaciones
│   └── ... (otros archivos de documentación)
│
├── 📄 install.php                        ← Guía de instalación
├── 📄 database_info.php                  ← Información de BD visual
├── 📄 BD_GENERADA.md                     ← Resumen de BD
├── 📄 INICIO_RAPIDO.md                   ← Inicio rápido
├── 📄 NUEVAS_FUNCIONALIDADES.md          ← ✨ NUEVO: Documentación de v2.5
├── 📄 RESUMEN_CAMBIOS_v2.5.txt           ← ✨ NUEVO: Resumen de cambios
├── 📄 INSTRUCCIONES_v2.5.md              ← ✨ NUEVO: Instrucciones de implementación
└── 📄 ESTRUCTURA_PROYECTO.md             ← Este archivo
```

---

## 📋 Archivos por Categoría

### 🎯 Páginas Públicas
| Archivo | Descripción | Acceso |
|---------|-------------|--------|
| `index.php` | Página principal | `/tiempo_hidalgo/` |
| `galeria.php` | ✨ Galería de imágenes | `/tiempo_hidalgo/galeria.php` |
| `noticia.php` | Vista de noticia individual | `/tiempo_hidalgo/noticia.php?id=X` |
| `files/contacto.php` | ✨ Formulario de contacto | `/tiempo_hidalgo/files/contacto.php` |

### 🔐 Páginas Administrativas
| Archivo | Descripción | Acceso |
|---------|-------------|--------|
| `files/login.php` | Login del admin | `/tiempo_hidalgo/files/login.php` |
| `files/admin.php` | 🔄 Panel principal (noticias + contactos) | `/tiempo_hidalgo/files/admin.php` |
| `files/alta_noticia.php` | Crear nueva noticia | `/tiempo_hidalgo/files/alta_noticia.php` |
| `files/editar_noticia.php` | Editar noticia | `/tiempo_hidalgo/files/editar_noticia.php?id=X` |
| `files/ver_noticia.php` | Ver noticia en admin | `/tiempo_hidalgo/files/ver_noticia.php?id=X` |
| `files/ver_contacto.php` | ✨ Ver contacto en admin | `/tiempo_hidalgo/files/ver_contacto.php?id=X` |
| `files/logout.php` | Cerrar sesión | `/tiempo_hidalgo/files/logout.php` |

### 🔧 Archivos de Configuración
| Archivo | Descripción |
|---------|-------------|
| `config.php` | Conexión a BD y funciones helper |
| `assets/css/style.css` | Estilos globales |
| `assets/js/script.js` | JavaScript global |
| `header.php` | Encabezado y navegación |
| `pie_pagina.php` | Pie de página |

### 📖 Documentación
| Archivo | Descripción |
|---------|-------------|
| `NUEVAS_FUNCIONALIDADES.md` | ✨ Documentación completa v2.5 |
| `RESUMEN_CAMBIOS_v2.5.txt` | ✨ Resumen rápido de cambios |
| `INSTRUCCIONES_v2.5.md` | ✨ Instrucciones de implementación |
| `install.php` | Guía de instalación |
| `database_info.php` | Información de BD |
| `BD_GENERADA.md` | Resumen de BD |
| `INICIO_RAPIDO.md` | Guía de inicio rápido |

---

## 🗄️ Base de Datos

### Tablas

```sql
usuarios
├── id (PK)
├── usuario
├── contrasena
├── email
├── estado
└── timestamps

noticias
├── id (PK)
├── titulo
├── descripcion
├── estado
├── autor_id (FK → usuarios)
└── timestamps

bloques
├── id (PK)
├── noticia_id (FK → noticias)
├── tipo (ENUM: imagen, parrafo, titulo, subtitulo)
├── contenido (LONGTEXT)
├── orden
└── timestamps

galeria ← ✨ Utilizado por galeria.php
├── id (PK)
├── noticia_id (FK → noticias) [NULL]
├── titulo
├── imagen
├── descripcion
├── orden
└── fecha_creacion

contactos ← ✨ Utilizado por contacto.php y admin.php
├── id (PK)
├── nombre
├── email
├── telefono
├── asunto
├── mensaje
├── estado (ENUM: nuevo, respondido, cerrado)
└── fecha_creacion

categorias
├── id (PK)
├── nombre
├── descripcion
└── slug

noticia_categoria
├── noticia_id (FK)
├── categoria_id (FK)
└── (relación M:N)
```

---

## 🎯 Flujos de Trabajo

### Flujo 1: Usuario Visitante
```
Accede a index.php
    ↓
Ve carousel de noticias
    ↓
Click en noticia → noticia.php
    ↓
Lee contenido con bloques (imágenes + texto)
    ↓
Click "Galería" → galeria.php
    ↓
Ve todas las imágenes
    ↓
Click "Contacto" → files/contacto.php
    ↓
Llena formulario y envía
    ↓
Mensaje guardado en BD (contactos table)
```

### Flujo 2: Administrador
```
Accede a files/login.php
    ↓
Ingresa credenciales
    ↓
Redirecciona a files/admin.php
    ↓
VE DOS PESTAÑAS:
│
├─ PESTAÑA NOTICIAS
│  ├─ Ver tabla de noticias
│  ├─ Click "Nueva Noticia" → alta_noticia.php
│  ├─ Click "Ver" → ver_noticia.php
│  ├─ Click "Editar" → editar_noticia.php
│  └─ Click "Eliminar" → Deletes from DB
│
└─ PESTAÑA CONTACTOS ← ✨ NUEVO
   ├─ Ver contador de contactos nuevos
   ├─ Ver tabla de contactos
   ├─ Filtrados por estado (Nuevo, Respondido, Cerrado)
   ├─ Click "Ver" → ver_contacto.php
   ├─ Cambiar estado del contacto
   ├─ Click "Responder" → Abre email del usuario
   └─ Click "Eliminar" → Deletes from DB
```

---

## 🎨 Tecnologías Utilizadas

### Backend
```
✓ PHP 7.4+
✓ MySQLi (procedural)
✓ Prepared Statements
✓ Sessions
```

### Frontend
```
✓ HTML5
✓ CSS3 (Flexbox, Grid)
✓ Vanilla JavaScript
✓ Font Awesome 6.0 (Icons)
✓ Swiper 9 (Carousel)
```

### Base de Datos
```
✓ MySQL / MariaDB
✓ UTF-8 charset
✓ InnoDB engine
✓ Foreign Keys con CASCADE
✓ Timestamps automáticos
```

---

## 🔒 Seguridad

### Autenticación
- ✅ Login con sesiones PHP
- ✅ Validación de usuario en cada página admin
- ✅ Logout seguro con session_destroy()

### SQL
- ✅ Prepared statements en todas las consultas
- ✅ Bind parameters contra inyección
- ✅ Foreign keys con constrains

### XSS
- ✅ htmlspecialchars() en salida HTML
- ✅ Validación de entrada en formularios

### Contraseñas
- ⚠️ Actualmente MD5 (considerar bcrypt en producción)

---

## 📱 Responsividad

### Breakpoints
```css
/* Desktop: > 1024px */
Grid de 3+ columnas

/* Tablet: 768px - 1024px */
Grid de 2 columnas

/* Mobile: < 768px */
Grid de 1 columna
Menú responsivo
Botones adaptados
```

### Componentes Responsivos
- ✅ Navbar sticky
- ✅ Grid de noticias
- ✅ Galería grid automático
- ✅ Formularios adaptados
- ✅ Tablas scrollables
- ✅ Modal de galería

---

## ⚡ Performance

### Optimizaciones
- ✅ CSS incrustado (sin request adicional)
- ✅ JavaScript vanilla (sin dependencias pesadas)
- ✅ Imágenes lazy load posible
- ✅ Índices en BD
- ✅ Prepared statements (evita recompilación)

### Cachés Sugeridas
- Caché de noticias frecuentes
- Caché de imagen del carousel
- Compresión GZIP en servidor

---

## 🔄 Versiones

| Versión | Cambios | Fecha |
|---------|---------|-------|
| 1.0 | Plataforma base, noticias | - |
| 2.0 | Base de datos completa | - |
| 2.5 | ✨ Galería + Contactos | 2024 |

---

## 📊 Estadísticas del Proyecto

```
Total de archivos PHP:         18
Total de archivos de CSS:      1
Total de archivos de JS:       1
Total de archivos HTML:        0
Total de archivos de datos:    0
Líneas de código (aprox):      5000+
Tablas de BD:                  7
Archivos de documentación:     10+
```

---

## 🚀 Próximas Características

### Corto plazo (v2.6)
- [ ] Email automático de confirmación
- [ ] Búsqueda avanzada
- [ ] Paginación en contactos

### Mediano plazo (v3.0)
- [ ] Sistema de comentarios
- [ ] Calificaciones/ratings
- [ ] Panel de estadísticas
- [ ] Multi-usuario con roles

### Largo plazo (v4.0)
- [ ] API REST
- [ ] Aplicación móvil
- [ ] Sistema de pagos
- [ ] Integración social media

---

## 📞 Contacto y Soporte

Para problemas o sugerencias:
1. Revisa la documentación
2. Activa debug mode en config.php
3. Revisa logs de servidor
4. Verifica consola del navegador (F12)

---

## 📝 Notas

- **Base de datos:** Se asume que existe y está configurada correctamente
- **Permisos:** Asegurar permisos de escritura en /uploads/
- **Hosting:** Compatible con cualquier hosting con PHP 7.4+ y MySQL
- **SSL:** Recomendado en producción
- **Backups:** Realizar backups regulares de BD

---

**Sistema Completo y Funcional ✅**

Versión: 2.5  
Última actualización: 2024  
Proyecto: El Tiempo de Hidalgo - Plataforma de Noticias
