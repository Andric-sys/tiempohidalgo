# ⚡ PASOS PARA ACTIVAR PUBLICACIÓN EN REDES SOCIALES

## PASO 1: Preparar la Base de Datos (5 minutos)

### Opción A: Usando phpMyAdmin

1. Abre phpMyAdmin: http://localhost/phpmyadmin
2. Selecciona la BD `tiempo_hidalgo`
3. Haz clic en "SQL"
4. Copia el contenido de: `migrations/add_social_media_tables.sql`
5. Pega en el editor SQL
6. Haz clic en "Ejecutar"

### Opción B: Desde terminal

```bash
cd C:\xampp\htdocs\tiempohidalgo
mysql -u root tiempo_hidalgo < migrations/add_social_media_tables.sql
```

✅ **Listo**: Se crearán las tablas automáticamente

---

## PASO 2: Obtener Credenciales de Facebook (10-15 minutos)

1. Ve a: https://developers.facebook.com/
2. Inicia sesión con tu cuenta de Facebook
3. Click en "Mis Apps" → "Crear aplicación"
4. Selecciona "Business" → Siguiente
5. Nombre: "El Tiempo de Hidalgo"
6. Email: tu email
7. Click "Crear aplicación"
8. En el dashboard, busca "Configuración Básica"
9. Copia el **App ID** y **App Secret**
10. Click "Token Generator" (arriba a la derecha)
11. Selecciona tu página de Facebook
12. Copia el **Page Access Token** (Token largo)

**Necesitas guardar:**
- ✏️ Access Token (token que copiaste)
- ✏️ Page ID (encuentra en: facebook.com/TU_PAGINA - el número es el ID)

---

## PASO 3: Obtener Credenciales de Instagram (5 minutos)

1. Abre: https://business.instagram.com/
2. Inicia sesión
3. Convierte tu Instagram a "Cuenta de Negocio" (si no está)
4. Ve a "Configuración" → "Información de la Cuenta"
5. Busca y copia el **Account ID** (número)

**Necesitas guardar:**
- ✏️ Account ID de Instagram

---

## PASO 4: Obtener Credenciales de Twitter/X (10 minutos)

1. Ve a: https://developer.twitter.com/
2. Inicia sesión con tu cuenta de X/Twitter
3. Click "Create an app"
4. Nombre: "El Tiempo de Hidalgo"
5. Lee y acepta los términos
6. Click "Create"
7. Ve a "Keys and tokens"
8. Bajo "Authentication Tokens", haz click "Generate"
9. Copia el **Bearer Token** (el token largo que empieza con "Bearer ")

**Necesitas guardar:**
- ✏️ Bearer Token

---

## PASO 5: Ingresar Credenciales en el Panel (5 minutos)

1. Abre: http://localhost/tiempohidalgo/files/social_media_settings.php
2. **Para Facebook:**
   - Pega el Access Token
   - Pega el Page ID
   - Marca ✓ "Activar"
   - Haz click "Guardar Configuración"

3. **Para Instagram:**
   - Haz click en "Instagram" (arriba a la izquierda)
   - Pega el Access Token (mismo de Facebook)
   - Pega el Account ID de Instagram
   - Marca ✓ "Activar"
   - Haz click "Guardar Configuración"

4. **Para Twitter/X:**
   - Haz click en "X (Twitter)" (arriba a la izquierda)
   - Pega el Bearer Token
   - Marca ✓ "Activar"
   - Haz click "Guardar Configuración"

---

## PASO 6: Probar (1 minuto)

1. Ve a: http://localhost/tiempohidalgo/files/admin.php
2. Haz click en "Agregar Nueva Noticia"
3. Completa:
   - Título
   - Una imagen (Agregar Imagen)
   - Un párrafo (Agregar Párrafo)
4. **Marca las redes donde quieres publicar**:
   - ☑️ Facebook
   - ☑️ Instagram  
   - ☑️ X (Twitter)
5. Haz click "Publicar Noticia"

✅ **Éxito**: Verás el mensaje de confirmación

---

## 🎯 Checklist Final

- [ ] Base de datos actualizada (Paso 1)
- [ ] Tengo Access Token de Facebook
- [ ] Tengo Page ID de Facebook
- [ ] Tengo Account ID de Instagram
- [ ] Tengo Bearer Token de Twitter/X
- [ ] Ingresé las credenciales en `social_media_settings.php`
- [ ] Activé la publicación automática (✓ marcado)
- [ ] Probé publicando una noticia
- [ ] La noticia aparece en mis redes sociales

---

## ❓ Preguntas Frecuentes

**P: ¿Mis tokens aparecerán públicamente?**
A: No. Los tokens se almacenan en la BD del servidor, no en el código público.

**P: ¿Qué pasa si desactivo la publicación?**
A: Las nuevas noticias no se publicarán, pero las anteriores permanecen en las redes.

**P: ¿Puedo editar noticias publicadas?**
A: Las noticias editadas NO se actualizan en las redes. Cada publicación es independiente.

**P: ¿Qué hago si algo falla?**
A: 
1. Verifica que los tokens no hayan expirado
2. Regenera los tokens desde los paneles de desarrolladores
3. Prueba nuevamente
4. Si sigue fallando, revisa el archivo `social_media_api.php` en las líneas de debug

**P: ¿Cuánto cuesta?**
A: ¡Es gratis! Facebook e Instagram permiten cierta cantidad de publicaciones gratuitas.

---

## 📚 Documentación Completa

Para más detalles, revisa: `README_REDES_SOCIALES.md`

---

**Versión**: 1.0  
**Fecha**: 9 de marzo de 2026
