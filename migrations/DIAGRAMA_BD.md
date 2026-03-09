# 📊 Diagrama de Base de Datos - El Tiempo de Hidalgo

## Estructura Visual

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                                                                              ║
║                    BASE DE DATOS: tiempo_hidalgo                            ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝

┌─────────────────────────┐
│      USUARIOS           │
├─────────────────────────┤
│ PK: id                  │
│    usuario (UNIQUE)     │
│    contrasena (MD5)     │
│    email                │
│    estado               │
│    fecha_creacion       │
│    fecha_actualizacion  │
└─────────────────────────┘
         │
         │ (1:N)
         │
         ├──────────────────────────────────────────┐
         │                                          │
         ▼                                          ▼
┌─────────────────────────┐              ┌─────────────────────────┐
│      NOTICIAS           │              │    CATEGORIAS           │
├─────────────────────────┤              ├─────────────────────────┤
│ PK: id                  │◄──────┐      │ PK: id                  │
│    titulo               │       │      │    nombre (UNIQUE)      │
│    descripcion          │       │      │    descripcion          │
│    estado               │       │      │    slug (UNIQUE)        │
│ FK: autor_id────────────┼───────┘      │    fecha_creacion       │
│    fecha_creacion       │              └─────────────────────────┘
│    fecha_actualizacion  │                       ▲
│    fecha_publicacion    │                       │ (M:N)
└─────────────────────────┘                       │
         │                              ┌─────────┴─────────────┐
         │                              │                       │
         │ (1:N)            ┌───────────────────────────────────┤
         │                  │  NOTICIA_CATEGORIA                │
         │                  ├────────────────────────────────────┤
         │                  │ PK: (noticia_id, categoria_id)     │
         │                  │ FK: noticia_id ──────┐             │
         │                  │ FK: categoria_id ────┼─────────────┤
         │                  └────────────────────────────────────┘
         │
         ├──────────────────────────┬──────────────────────────────┐
         │                          │                              │
         ▼                          ▼                              ▼
┌─────────────────────────┐ ┌──────────────────────┐ ┌──────────────────────┐
│      BLOQUES            │ │      GALERIA         │ │      CONTACTOS       │
├─────────────────────────┤ ├──────────────────────┤ ├──────────────────────┤
│ PK: id                  │ │ PK: id               │ │ PK: id               │
│ FK: noticia_id          │ │ FK: noticia_id (OPT) │ │    nombre            │
│    tipo (ENUM)          │ │    titulo            │ │    email             │
│    contenido (LONGTEXT) │ │    imagen            │ │    telefono          │
│    orden                │ │    descripcion       │ │    asunto            │
│    fecha_creacion       │ │    orden             │ │    mensaje           │
└─────────────────────────┘ │    fecha_creacion    │ │    estado (ENUM)     │
                            └──────────────────────┘ │    fecha_creacion    │
                                                      └──────────────────────┘
```

## Descripción de Tablas

### 1️⃣ USUARIOS
Gestiona los administradores del sitio.

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario),
    INDEX idx_estado (estado)
);
```

**Campos:**
- `id`: Identificador único
- `usuario`: Nombre de usuario para login (único)
- `contrasena`: Contraseña hasheada en MD5
- `email`: Correo electrónico del administrador
- `estado`: Indica si la cuenta está activa o inactiva
- Timestamps automáticos

**Ejemplo:**
```sql
INSERT INTO usuarios VALUES (
    1, 'adminb', 'e807f1fcf82d132f9bb018ca6738a19f', 
    'admin@tiempoHidalgo.local', 'activo', NOW(), NOW()
);
```

---

### 2️⃣ NOTICIAS
Almacena la información principal de cada noticia.

```sql
CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    estado ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
    autor_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_publicacion TIMESTAMP NULL,
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_estado (estado),
    INDEX idx_autor_id (autor_id),
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
```

**Campos:**
- `id`: Identificador único
- `titulo`: Título de la noticia
- `descripcion`: Descripción breve
- `estado`: borrador, publicado o archivado
- `autor_id`: Referencia al usuario que la creó
- `fecha_creacion`: Cuándo se creó
- `fecha_actualizacion`: Última actualización
- `fecha_publicacion`: Cuándo se publicó

**Estados:**
- **borrador**: Noticia en construcción
- **publicado**: Visible en el sitio
- **archivado**: Oculta pero conservada

---

### 3️⃣ BLOQUES
Sistema flexible para contenido: imágenes y párrafos.

```sql
CREATE TABLE bloques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    tipo ENUM('imagen', 'parrafo', 'titulo', 'subtitulo') DEFAULT 'parrafo',
    contenido LONGTEXT NOT NULL,
    orden INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_noticia_id (noticia_id),
    INDEX idx_tipo (tipo),
    INDEX idx_orden (orden),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
);
```

**Campos:**
- `id`: Identificador único
- `noticia_id`: Referencia a la noticia padre
- `tipo`: imagen, parrafo, titulo o subtitulo
- `contenido`: El contenido real (ruta de archivo o texto)
- `orden`: Posición del bloque en la noticia (1, 2, 3...)
- `fecha_creacion`: Cuándo se creó el bloque

**Tipos:**
- **imagen**: Ruta a archivo de imagen
- **parrafo**: Texto del artículo
- **titulo**: Título del bloque
- **subtitulo**: Subtítulo del bloque

**Ejemplo de estructura:**
```
Noticia #5: "Elecciones Municipales"
├── Bloque 1: imagen → uploads/noticias/img1.jpg
├── Bloque 2: parrafo → "En las elecciones de hoy..."
├── Bloque 3: imagen → uploads/noticias/img2.jpg
└── Bloque 4: parrafo → "Los resultados muestran..."
```

---

### 4️⃣ GALERIA
Galería de imágenes (puede estar asociada a noticias).

```sql
CREATE TABLE galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT,
    titulo VARCHAR(255),
    imagen VARCHAR(255) NOT NULL,
    descripcion TEXT,
    orden INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_noticia_id (noticia_id),
    INDEX idx_orden (orden),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
);
```

**Características:**
- Puedes tener imágenes sin asociar a noticia (noticia_id NULL)
- Sistema de ordenamiento
- Descripciones para cada imagen

---

### 5️⃣ CATEGORIAS
Clasificación de noticias.

```sql
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    slug VARCHAR(100) UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
);
```

**Ejemplo:**
```sql
INSERT INTO categorias VALUES (
    1, 'Local', 'Noticias locales de Hidalgo', 'local', NOW()
);
```

---

### 6️⃣ NOTICIA_CATEGORIA
Tabla de relación (N:M) entre noticias y categorías.

```sql
CREATE TABLE noticia_categoria (
    noticia_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (noticia_id, categoria_id),
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);
```

**Ejemplo:**
```sql
-- Noticia 1 pertenece a categorías 1 (Local) y 3 (Política)
INSERT INTO noticia_categoria VALUES (1, 1);
INSERT INTO noticia_categoria VALUES (1, 3);
```

---

### 7️⃣ CONTACTOS
Mensajes de contacto del formulario en el sitio.

```sql
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('nuevo', 'respondido', 'cerrado') DEFAULT 'nuevo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_fecha_creacion (fecha_creacion)
);
```

**Estados:**
- **nuevo**: Sin responder
- **respondido**: Ya fue respondido
- **cerrado**: Consulta cerrada

---

## 🔄 Relaciones

### 1:N (Uno a Muchos)
- **Usuarios → Noticias**: Un usuario puede crear muchas noticias
- **Noticias → Bloques**: Una noticia puede tener muchos bloques
- **Noticias → Galería**: Una noticia puede tener muchas imágenes

### M:N (Muchos a Muchos)
- **Noticias ↔ Categorías**: Una noticia puede estar en muchas categorías, y una categoría puede tener muchas noticias

---

## 📈 Índices

Se han agregado índices en campos frecuentemente consultados para optimizar las búsquedas:

```sql
usuarios:
- idx_usuario (para login)
- idx_estado (para filtros)

noticias:
- idx_fecha_creacion (para ordenar por fecha)
- idx_estado (para publicadas/borradores)
- idx_autor_id (para noticias del usuario)

bloques:
- idx_noticia_id (para obtener bloques de noticia)
- idx_tipo (para filtrar por tipo)
- idx_orden (para ordenar)

galeria:
- idx_noticia_id (para imágenes de noticia)
- idx_orden (para ordenamiento)

contactos:
- idx_estado (para filtrar por estado)
- idx_fecha_creacion (para ordenar recientes)
```

---

## 💾 Charset y Collation

Todas las tablas usan:
- **Charset**: `utf8mb4` (Soporte completo de Unicode)
- **Collation**: `utf8mb4_unicode_ci` (Comparación sin acento)

Esto permite:
- ✅ Caracteres especiales (acentos, ñ, emojis)
- ✅ Búsquedas sin distinguir mayúsculas/minúsculas
- ✅ Búsquedas sin acento

---

## 🔐 Integridad Referencial

Las claves foráneas están configuradas con:
- **ON DELETE CASCADE**: Cuando se elimina un padre, se eliminan todos los hijos

Ejemplo:
```sql
-- Si se elimina una noticia, se eliminan automáticamente:
DELETE FROM noticias WHERE id = 5;
-- → Deletes all bloques with noticia_id = 5
-- → Deletes all galeria records with noticia_id = 5
-- → Deletes all noticia_categoria records with noticia_id = 5
```

---

## 📝 Notas Importantes

1. **Contraseñas**: Se guardan en MD5 (usar bcrypt en producción)
2. **Rutas de Imagen**: Se guardan como rutas relativas (uploads/noticias/img.jpg)
3. **Timestamps**: Se actualizan automáticamente
4. **Estado de Noticias**: Control completo del ciclo de vida
5. **Optimización**: Índices para consultas frecuentes

---

## 🚀 Consultas Útiles

### Obtener últimas noticias publicadas
```sql
SELECT n.id, n.titulo, n.fecha_creacion
FROM noticias n
WHERE n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC
LIMIT 10;
```

### Obtener bloques de una noticia
```sql
SELECT * FROM bloques
WHERE noticia_id = 1
ORDER BY orden ASC;
```

### Obtener noticias de una categoría
```sql
SELECT n.* FROM noticias n
JOIN noticia_categoria nc ON n.id = nc.noticia_id
WHERE nc.categoria_id = 1
AND n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC;
```

### Obtener la primera imagen de cada noticia
```sql
SELECT n.id, n.titulo, 
  (SELECT contenido FROM bloques 
   WHERE noticia_id = n.id AND tipo = 'imagen' 
   ORDER BY orden LIMIT 1) as primera_imagen
FROM noticias n
WHERE n.estado = 'publicado'
ORDER BY n.fecha_creacion DESC;
```

### Contar noticias por estado
```sql
SELECT estado, COUNT(*) as total
FROM noticias
GROUP BY estado;
```

---

**Última actualización:** 23 de febrero de 2026  
**Versión:** 1.0
