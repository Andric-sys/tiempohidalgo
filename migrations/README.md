# 🚀 Base de Datos - El Tiempo de Hidalgo

## 📋 Descripción

Este archivo contiene toda la estructura de la base de datos para la plataforma de noticias **El Tiempo de Hidalgo**. Se incluyen dos formas de crear la base de datos:

1. **migrations.php** - Script PHP interactivo (Recomendado)
2. **schema.sql** - Script SQL directo

---

## ✅ Forma 1: Usando PHP (Recomendado)

### Pasos:

1. Asegúrate de que **XAMPP** esté corriendo con **Apache** y **MySQL**
2. Abre en tu navegador:
   ```
   http://localhost/tiempo_hidalgo/migrations/migrations.php
   ```
3. El script ejecutará automáticamente:
   - ✅ Creación de la base de datos
   - ✅ Creación de todas las tablas
   - ✅ Creación de carpetas necesarias
   - ✅ Inserción del usuario admin

4. Verás una página con el estado de cada operación

---

## 📊 Estructura de la Base de Datos

### Tabla: `usuarios`
```sql
- id (INT) - Identificador único
- usuario (VARCHAR) - Nombre de usuario único
- contrasena (VARCHAR) - Hash MD5 de la contraseña
- email (VARCHAR) - Correo electrónico
- estado (ENUM) - 'activo' o 'inactivo'
- fecha_creacion (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
```

### Tabla: `noticias`
```sql
- id (INT) - Identificador único
- titulo (VARCHAR) - Título de la noticia
- descripcion (TEXT) - Descripción breve
- estado (ENUM) - 'borrador', 'publicado', 'archivado'
- autor_id (INT) - Referencia al usuario que creó
- fecha_creacion (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
- fecha_publicacion (TIMESTAMP)
```

### Tabla: `bloques`
```sql
- id (INT) - Identificador único
- noticia_id (INT) - Referencia a la noticia
- tipo (ENUM) - 'imagen', 'parrafo', 'titulo', 'subtitulo'
- contenido (LONGTEXT) - Contenido del bloque
- orden (INT) - Orden de aparición
- fecha_creacion (TIMESTAMP)
```

### Tabla: `galeria`
```sql
- id (INT) - Identificador único
- noticia_id (INT) - Referencia a la noticia (opcional)
- titulo (VARCHAR)
- imagen (VARCHAR) - Ruta de la imagen
- descripcion (TEXT)
- orden (INT)
- fecha_creacion (TIMESTAMP)
```

### Tabla: `categorias`
```sql
- id (INT) - Identificador único
- nombre (VARCHAR) - Nombre de la categoría
- descripcion (TEXT)
- slug (VARCHAR) - URL amigable
- fecha_creacion (TIMESTAMP)
```

### Tabla: `noticia_categoria`
```sql
- noticia_id (INT) - Referencia a noticia
- categoria_id (INT) - Referencia a categoría
- PRIMARY KEY (noticia_id, categoria_id)
```

### Tabla: `contactos`
```sql
- id (INT) - Identificador único
- nombre (VARCHAR)
- email (VARCHAR)
- telefono (VARCHAR)
- asunto (VARCHAR)
- mensaje (TEXT)
- estado (ENUM) - 'nuevo', 'respondido', 'cerrado'
- fecha_creacion (TIMESTAMP)
```

---

## 🔑 Credenciales por Defecto

Después de ejecutar la migración:

- **Usuario:** `adminb`
- **Contraseña:** `123456`
- **Email:** `admin@tiempoHidalgo.local`

⚠️ **IMPORTANTE:** Cambia la contraseña después del primer login por seguridad.

---

## 🛠️ Forma 2: Usando SQL Directo

Si prefieres ejecutar el SQL directamente:

1. Abre **phpMyAdmin** (http://localhost/phpmyadmin)
2. Crea una nueva base de datos llamada `tiempo_hidalgo`
3. Selecciona la BD
4. Ve a la pestaña **SQL**
5. Copia y pega el contenido de `schema.sql`
6. Haz click en **Ejecutar**

---

## 📁 Carpetas Creadas

El script también crea automáticamente estas carpetas:

```
tiempo_hidalgo/
├── uploads/
│   ├── noticias/       (Para imágenes de noticias)
│   └── galeria/        (Para imágenes de galería)
└── assets/
    └── images/         (Para imágenes estáticas)
```

---

## 🔄 Reiniciar la Base de Datos

Si necesitas reiniciar la base de datos:

1. Ve a phpMyAdmin
2. Selecciona la BD `tiempo_hidalgo`
3. Haz click en **Operaciones**
4. Haz click en **Borrar base de datos**
5. Ejecuta nuevamente `migrations.php`

O simplemente vuelve a acceder a:
```
http://localhost/tiempo_hidalgo/migrations/migrations.php
```

---

## ✨ Características de la Base de Datos

✅ Relaciones entre tablas (Foreign Keys)
✅ Índices para optimizar búsquedas
✅ Cascada de eliminación (cascade delete)
✅ Timestamps automáticos
✅ Enumeraciones para controlar tipos
✅ UTF-8 completo para caracteres especiales
✅ InnoDB para transacciones

---

## 📝 Notas Importantes

1. **Seguridad:** 
   - La contraseña se almacena en MD5 (considera usar bcrypt en producción)
   - Usa HTTPS en producción
   - Implementa validación y sanitización de datos

2. **Backup:**
   - Realiza backups regulares de la BD
   - Guarda los backups en un lugar seguro

3. **Actualización:**
   - Si necesitas cambiar la estructura, modifica `schema.sql`
   - Crea migraciones adicionales si es necesario

---

## 🆘 Solución de Problemas

### "Error de conexión a MySQL"
- Verifica que XAMPP esté corriendo
- Verifica que el usuario y contraseña sean correctos en `config.php`

### "Base de datos ya existe"
- El script detecta y no sobrescribe datos existentes
- Usa phpMyAdmin para borrar la BD si necesitas comenzar de nuevo

### "Error de permisos en carpetas"
- Verifica que la carpeta `tiempo_hidalgo` tenga permisos 755
- En Windows, generalmente no hay problemas de permisos

---

## 📚 Documentación Adicional

Para más información sobre las funciones PHP ver:
- [config.php](../config.php) - Configuración general
- [files/auth.php](../files/auth.php) - Autenticación
- [files/admin.php](../files/admin.php) - Panel administrativo

---

**Versión:** 1.0  
**Última actualización:** 23 de febrero de 2026  
**Autor:** Sistema de Gestión de Noticias
