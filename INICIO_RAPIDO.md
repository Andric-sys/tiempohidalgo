# 🎯 GUÍA RÁPIDA DE INICIO - El Tiempo de Hidalgo

## ⚡ Primeros Pasos (3 minutos)

### Paso 1: Verificar que XAMPP esté corriendo
- Abre el Panel de Control de XAMPP
- Verifica que **Apache** está corriendo (botón "Start" verde)
- Verifica que **MySQL** está corriendo (botón "Start" verde)

### Paso 2: Ejecutar las Migraciones
Abre tu navegador e ingresa:
```
http://localhost/tiempo_hidalgo/migrations/migrations.php
```

Espera a que el script termine. Deberías ver muchos ✅ si todo está bien.

### Paso 3: Ir al sitio
```
http://localhost/tiempo_hidalgo
```

### Paso 4: Login en el Panel Admin
```
http://localhost/tiempo_hidalgo/files/login.php
```

**Credenciales:**
- Usuario: `adminb`
- Contraseña: `123456`

---

## 📁 Estructura de Archivos Creada

```
tiempo_hidalgo/
│
├── config.php              ← Configuración y conexión BD
├── header.php              ← Encabezado y navbar
├── index.php               ← Página principal
├── noticia.php             ← Ver noticia completa
├── pie_pagina.php          ← Footer
├── install.php             ← Página de instalación
│
├── files/                  ← Panel administrativo
│   ├── admin.php           ← Dashboard del admin
│   ├── alta_noticia.php    ← Crear noticias
│   ├── editar_noticia.php  ← Editar noticias
│   ├── ver_noticia.php     ← Ver noticia (admin)
│   ├── login.php           ← Formulario login
│   ├── auth.php            ← Verificación usuarios
│   └── logout.php          ← Cerrar sesión
│
├── migrations/             ← Scripts de BD
│   ├── migrations.php      ← Script principal (EJECUTAR ESTO)
│   ├── schema.sql          ← SQL directo
│   ├── README.md           ← Documentación BD
│   └── DIAGRAMA_BD.md      ← Diagrama de tablas
│
├── assets/
│   ├── css/
│   │   └── style.css       ← Estilos principales
│   ├── js/
│   │   └── script.js       ← JavaScript
│   └── images/             ← Imágenes estáticas
│
└── uploads/                ← Carpeta para imágenes subidas
    ├── noticias/
    └── galeria/
```

---

## 🗄️ Base de Datos Creada

**Nombre:** `tiempo_hidalgo`

### Tablas:
1. **usuarios** - Administradores del sitio
2. **noticias** - Información de noticias
3. **bloques** - Contenido flexible (imagen + párrafo)
4. **galeria** - Galería de imágenes
5. **categorias** - Categorización
6. **noticia_categoria** - Relación N:M
7. **contactos** - Formulario de contacto

---

## 🎨 Características del Sitio

### 🏠 Frontend Público
- ✅ Carrusel con 5 últimas noticias
- ✅ Grid de noticias con cards
- ✅ Búsqueda en tiempo real
- ✅ Vista completa de noticia
- ✅ Compartir en redes sociales
- ✅ Diseño responsive (móvil, tablet, desktop)

### 🔐 Panel Administrativo
- ✅ Login seguro
- ✅ Crear noticias con bloques dinámicos
- ✅ Agregar múltiples imágenes y párrafos
- ✅ Editar noticias existentes
- ✅ Eliminar noticias
- ✅ Ver todas las noticias en tabla
- ✅ Estadísticas y dashboard

---

## 📝 Cómo Crear una Noticia

1. Login en: `http://localhost/tiempo_hidalgo/files/login.php`
2. Click en "Panel Admin"
3. Click en "Nueva Noticia"
4. Ingresa el título
5. Agrega bloques:
   - Click "Agregar Imagen" - Sube una foto
   - Click "Agregar Párrafo" - Escribe texto
6. Puedes agregar cuantos bloques quieras
7. Click en "Publicar Noticia"

---

## 🔑 Credenciales y Acceso

| Dato | Valor |
|------|-------|
| Usuario Admin | `adminb` |
| Contraseña | `123456` |
| Email | `admin@tiempoHidalgo.local` |
| URL Login | `http://localhost/tiempo_hidalgo/files/login.php` |
| URL Admin | `http://localhost/tiempo_hidalgo/files/admin.php` |
| URL Inicio | `http://localhost/tiempo_hidalgo` |

⚠️ **IMPORTANTE:** Cambia la contraseña después del primer login

---

## 🆘 Troubleshooting

### "No puedo acceder a las migraciones"
→ Verifica que XAMPP esté corriendo (Apache + MySQL)

### "Error de conexión a BD"
→ Abre `migrations/migrations.php` y ejecuta nuevamente

### "Usuario admin no funciona"
→ Copia la contraseña tal como está: `123456` (no es 12345 ni 1234567)

### "Las imágenes no se cargan"
→ Verifica que la carpeta `uploads` tenga permisos de escritura (755)

### "¿Cómo cambio el diseño?"
→ Edita el archivo `assets/css/style.css`

### "¿Cómo agrego más funciones?"
→ Los archivos están bien organizados, puedes modificar o crear nuevos

---

## 📚 Documentación Completa

Para documentación más detallada, consulta:

1. **Migraciones BD**: `/migrations/README.md`
2. **Diagrama BD**: `/migrations/DIAGRAMA_BD.md`
3. **SQL Completo**: `/migrations/schema.sql`

---

## 🚀 Próximos Pasos Recomendados

### Nivel 1: Básico
- [ ] Ejecutar las migraciones
- [ ] Acceder al sitio
- [ ] Crear 2-3 noticias de prueba
- [ ] Publicarlas y verlas en el sitio

### Nivel 2: Intermedio
- [ ] Cambiar la contraseña del admin
- [ ] Personalizar los colores en CSS
- [ ] Agregar más categorías
- [ ] Subir más noticias

### Nivel 3: Avanzado
- [ ] Crear más usuarios administradores
- [ ] Implementar búsqueda avanzada
- [ ] Agregar comentarios a noticias
- [ ] Implementar newsletter

---

## 💡 Tips Útiles

1. **Carrusel automático**: Gira cada 5 segundos con transiciones suaves
2. **Búsqueda en tiempo real**: Sin necesidad de botón, escribe y filtra
3. **Bloques dinámicos**: Puedes reordenarlos en la BD
4. **Imágenes responsivas**: Se adaptan a cualquier tamaño de pantalla
5. **URLs amigables**: Las noticias usan IDs simples (noticia.php?id=1)

---

## 📞 Contacto y Soporte

Si tienes problemas:
1. Revisa el archivo `README.md` en la carpeta `/migrations/`
2. Verifica el archivo `DIAGRAMA_BD.md` para entender la estructura
3. Abre el navegador en `http://localhost/tiempo_hidalgo/install.php`

---

## ✨ ¡Éxito!

Tu plataforma de noticias está lista. Ahora puedes comenzar a crear contenido.

**Última actualización:** 23 de febrero de 2026  
**Versión:** 1.0
