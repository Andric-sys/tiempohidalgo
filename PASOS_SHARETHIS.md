# ⚡ INICIAR CON SHARETHIS - PASOS RÁPIDOS

## 1️⃣ Elimina o desactiva archivos no necesarios

Estos archivos ya NO se necesitan (los creamos antes):

```
✖️ social_media_config.php     (ELIMINAR)
✖️ social_media_api.php        (ELIMINAR)
✖️ ifttt_api.php               (No crear)
✖️ files/social_media_settings.php  (ELIMINAR)
```

Los botones de ShareThis funcionan solos.

---

## 2️⃣ Verifica que los cambios estén hechos

✅ **Modificados:**
- `noticia.php` - Agrega botones de ShareThis
- `files/alta_noticia.php` - Simplificado, sin APIs

✅ **Nuevos:**
- `SHARETHIS_GUIA.md` - Esta guía

---

## 3️⃣ Prueba en tu navegador

### Crear una noticia:
```
http://localhost/tiempohidalgo/files/admin.php
→ Agregar Nueva Noticia
→ Completa título, imagen, párrafo
→ Publicar
```

### Verla con botones de compartir:
```
http://localhost/tiempohidalgo/noticia.php?id=1
→ Baja hasta la sección "Compartir en Redes Sociales"
→ Verás los botones coloridos 🎨
```

---

## 4️⃣ Prueba a compartir

1. Click en cualquier botón (ej: Facebook)
2. Se abrirá Facebook
3. Verá la noticia con:
   - Título
   - URL
   - Vista previa de imagen
4. Click en "Compartir"

**¡Listo!** Tus amigos ven la noticia.

---

## 5️⃣ (Opcional) Crear cuenta en ShareThis para estadísticas

Si quieres ver cuántos compartieron:

1. Ve a: https://platform.sharethis.com/
2. Click "Sign Up"
3. Email: tunombre@correo.com
4. Verás un dashboard con:
   - Número de shares
   - Qué redes se usan más
   - Tendencias

**Pero es opcional.** Los botones funcionan igual sin cuenta.

---

## 6️⃣ ¡Eso es todo!

No necesitas:
- ❌ APIs de Facebook
- ❌ APIs de Twitter
- ❌ Verificación de pago
- ❌ Tokens secretos
- ❌ Más código

**Solo funciona automáticamente.**

---

## 🎯 Resumen de lo que tenés:

```
ANTES (Complicado):
  - Pedir credenciales
  - APIs de Meta
  - APIs de Twitter
  - Verificación de pago
  - 100+ líneas de código
  - Mantenimiento constante

AHORA (ShareThis):
  - 3 líneas de código
  - Gratuito
  - Sin verificación
  - 200+ redes soportadas
  - Estadísticas gratis (opcional)
  - ¡Funciona!
```

---

## ❓ Si algo no funciona

### "No veo los botones"
1. Verifica que estés en `noticia.php?id=X`
2. Baja al final de la página
3. Recarga (Ctrl+F5)

### "Quiero cambiar los botones"
1. Abre `noticia.php`
2. Busca `sharethis-inline-share-buttons`
3. Cambia atributos:
   ```html
   <div class="sharethis-inline-share-buttons" data-size="32"></div>
   ```

### "Quiero más redes"
ShareThis automáticamente muestra 5-8 redes principales.
Para ver todas, haz click en "..." (más opciones).

---

## 🎉 ¡Completado!

Tu sistema de redes sociales está 100% funcional con **ShareThis**.

✅ **Ventajas:**
- Gratis
- Sin límites
- 200+ redes
- Sin configuración
- Responsive
- Rápido
- Confiable

---

**Fecha:** 10 de marzo de 2026  
**Versión:** 1.0  
**Estado:** ✅ Listo para usar
