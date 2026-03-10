# INTEGRACIÓN DE REDES SOCIALES - Guía Completa

## 📋 Resumen

Se ha implementado la integración automática de publicación en **Facebook**, **Instagram** y **Twitter/X**. Cuando publiques una noticia, puedes seleccionar en qué redes deseas que aparezca automáticamente.

---

## 🚀 Inicio Rápido

### 1. **Ejecutar la Migración de Base de Datos**

```sql
-- Ejecuta este archivo SQL en tu administrador de BD (phpMyAdmin, etc.)
migrations/add_social_media_tables.sql
```

O desde la terminal:
```bash
mysql -u root tiempo_hidalgo < migrations/add_social_media_tables.sql
```

Esto creará las tablas necesarias:
- `social_media_credenciales` - Almacena tokens y credenciales
- `social_media_publicaciones` - Registra cada publicación en redes

### 2. **Configurar Credenciales**

Accede a: `files/social_media_settings.php`

En el panel de configuración, ingresa:
- **Facebook**: Access Token + Page ID
- **Instagram**: Account ID (necesita el mismo token de Facebook)
- **Twitter/X**: Bearer Token

### 3. **Publicar una Noticia**

Al crear una noticia en `files/alta_noticia.php`:
1. Completa título y bloques (imagen y párrafo)
2. Marca los checkboxes de las redes donde quieres publicar
3. ¡Hecho! La noticia se publicará automáticamente

---

## 🔐 Obtener Credenciales por Red Social

### Facebook & Instagram (Meta Graph API)

1. **Ve a**: https://developers.facebook.com/
2. **Crea una App**:
   - Tipo: "Business"
   - Nombre: "El Tiempo de Hidalgo"
3. **Obtén Access Token**:
   - Ir a: Settings → Basic
   - O genera uno permanente en: Tools → Token Generator
4. **Page ID**:
   - Abre tu página de Facebook
   - La URL es: `facebook.com/XXXXXXXXX`
   - Ese número es tu **Page ID**
5. **Instagram Account ID** (si usas Instagram):
   - Meta Business Suite → Instagram → Configuración → Cuenta
   - Busca "Account ID"

**Documentación**: https://developers.facebook.com/docs/graph-api/

---

### Twitter/X (API v2)

1. **Ve a**: https://developer.twitter.com/
2. **Crea una App**:
   - Nombre: "El Tiempo de Hidalgo"
3. **Habilita Permisos**:
   - Settings → App Permissions
   - Selecciona "Read and Write"
   - Selecciona "User context authentication"
4. **Obtén Bearer Token**:
   - Keys and tokens → Generate
   - Copia el **Bearer Token**

**Documentación**: https://developer.twitter.com/en/docs/twitter-api/

---

## 📁 Archivos Nuevos Creados

```
proyecto/
├── social_media_config.php          # Configuración base (NO usar en producción)
├── social_media_api.php             # Clase SocialMediaAPI con funciones
├── files/
│   ├── alta_noticia.php             # MODIFICADO: Agrega checkboxes
│   └── social_media_settings.php    # Panel de configuración
├── migrations/
│   └── add_social_media_tables.sql  # Script SQL para la BD
└── README_REDES_SOCIALES.md        # Esta documentación
```

---

## 🔧 Estructura Técnica

### Flujo de Publicación

```
1. Usuario crea noticia + selecciona redes
               ↓
2. Se guarda en BD (noticias + bloques)
               ↓
3. Se llama a publicarEnTodasLasRedes()
               ↓
4. Para cada red seleccionada:
   - SocialMediaAPI::publicarEnFacebook()
   - SocialMediaAPI::publicarEnInstagram()
   - SocialMediaAPI::publicarEnTwitter()
               ↓
5. Se registra en social_media_publicaciones
               ↓
6. Confirmación al usuario
```

### Clase `SocialMediaAPI`

**Métodos disponibles:**

```php
// Publicar
SocialMediaAPI::publicarEnFacebook($titulo, $desc, $img, $link);
SocialMediaAPI::publicarEnInstagram($titulo, $desc, $img);
SocialMediaAPI::publicarEnTwitter($titulo, $desc, $link);

// Auxiliares
SocialMediaAPI::generarResumen($texto, $limite);
SocialMediaAPI::registrarPublicacion($noticia_id, $red, $post_id, $exitoso);
```

**Función global:**

```php
publicarEnTodasLasRedes($noticia_id, $titulo, $desc, $img, $link, $redes_array);
```

---

## 📊 Base de Datos

### Tabla: `social_media_credenciales`

```sql
id                  INT (PK)
red_social          ENUM('facebook','instagram','twitter','linkedin')
access_token        VARCHAR(1000)
page_id             VARCHAR(255)
account_id          VARCHAR(255)
bearer_token        VARCHAR(1000)
refresh_token       VARCHAR(1000)
fecha_expiracion    TIMESTAMP
activo              BOOLEAN
fecha_creacion      TIMESTAMP
fecha_actualizacion TIMESTAMP
```

### Tabla: `social_media_publicaciones`

```sql
id                  INT (PK)
noticia_id          INT (FK)
red_social          ENUM('facebook','instagram','twitter','linkedin')
post_id             VARCHAR(255)
url_publicacion     VARCHAR(500)
exitoso             BOOLEAN
estado              ENUM('publicado','fallido','revisión')
mensaje_error       TEXT
fecha_publicacion   TIMESTAMP
```

### Cambios en tabla: `noticias`

```sql
publicado_redes             BOOLEAN (nueva)
fecha_publicacion_redes     TIMESTAMP (nueva)
```

---

## 🐛 Solução de Problemas

### "No puedo publicar en las redes"

1. **Verifica que:**
   - Los tokens estén configurados en `social_media_settings.php`
   - La casilla "Activar publicación automática" esté marcada
   - `social_media_api.php` esté en la raíz del proyecto

2. **Habilitar debug:**
   - En `social_media_config.php`, cambiar:
   ```php
   define('SOCIAL_MEDIA_DEBUG', true); // Para ver errores
   ```
   - Los errores aparecerán en el log de PHP

### "Token inválido o expirado"

- Los tokens de Facebook pueden expirar
- Ve a Facebook Developer Console y regenera el token
- Los tokens de Twitter/X no expiran, pero pueden revocarse

### "Instagram no se publica"

- Instagram requiere una **Business Account**
- La imagen debe estar en formato válido (JPG, PNG)
- La descripción no puede exceder 2200 caracteres

### "Error 403 o 401"

- El token es inválido o expirado
- Regenera los tokens en los paneles de desarrolladores
- Verifica que tienes permisos de lectura/escritura

---

## 🔐 Seguridad

### Recomendaciones

1. **En Producción:**
   - Usa variables de entorno, NO hardcodes
   ```php
   define('META_ACCESS_TOKEN', $_ENV['META_ACCESS_TOKEN']);
   ```

2. **Encripta los tokens:**
   - Considera usar `openssl_encrypt()` para guardarlos cifrados en BD

3. **Respaldo de tokens:**
   - Guarda tus tokens en un archivo `.env` respaldado

4. **Rotación de tokens:**
   - Regenera los tokens cada 30-90 días

5. **Permisos De Archivo:**
   ```bash
   chmod 600 social_media_config.php
   ```

---

## 📈 Próximas Mejoras

- [ ] Programar publicaciones (tiempo diferido)
- [ ] Enlazar las estadísticas (visualizaciones, likes)
- [ ] Integrar LinkedIn
- [ ] Soporte para reacciones y comentarios
- [ ] Cola de publicación (si la API falla, reintentar)
- [ ] Sincronización de perfiles múltiples

---

## 📞 Soporte

Si tienes problemas:

1. Revisa los logs en `logs/social_media.log`
2. Verifica los errores en la tabla `social_media_publicaciones`
3. Consulta la documentación oficial:
   - Facebook: https://developers.facebook.com/docs/
   - Twitter: https://developer.twitter.com/en/docs/
   - Instagram: https://developers.facebook.com/docs/instagram-api/

---

**Versión**: 1.0  
**Última actualización**: 9 de marzo de 2026  
**Desarrollador**: Sistema de Gestión de Noticias - El Tiempo de Hidalgo
