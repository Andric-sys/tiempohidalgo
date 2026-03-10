# 🚀 ShareThis - Compartir en Redes Sociales

## ¿Qué es ShareThis?

**ShareThis** es una librería de JavaScript **gratuita** que permite a tus usuarios compartir contenido en múltiples redes sociales sin límites de publicaciones ni verificaciones de pago.

**Características:**
- ✅ Totalmente gratis
- ✅ Sin límites de compartidas
- ✅ Soporta 200+ redes sociales
- ✅ Bonitos botones con iconos
- ✅ Automático (sin código adicional)

---

## 🎯 Redes Sociales Disponibles

Con ShareThis puedes compartir en:

**Populares:**
- Facebook
- Twitter/X
- Instagram
- LinkedIn
- Pinterest
- WhatsApp
- Telegram
- Reddit
- TikTok
- Snapchat

**Y muchas más:** Viber, Pocket, Tumblr, Email, etc.

---

## 📍 Dónde Encontrar los Botones

### 1. Cuando Publicas una Noticia
- Archivo: `files/alta_noticia.php`
- Después de publicar exitosamente, verás el mensaje de confirmación
- Abre la noticia publicada para compartir

### 2. En Cada Noticia Individual
- Archivo: `noticia.php`
- Al final de cada artículo, debajo del contenido
- Sección: **"Compartir en Redes Sociales"**

---

## 🖱️ Cómo Usar

### Paso 1: Abre una Noticia
```
http://localhost/tiempohidalgo/noticia.php?id=1
```

### Paso 2: Baja hasta "Compartir en Redes Sociales"
Verás una fila de botones con los logos de las redes sociales.

### Paso 3: Haz Clic en la Red que Quieres
Ejemplo: Click en Facebook
```
[f] Facebook
```

### Paso 4: Comparte
- Se abrirá la red social
- La noticia ya vendrá con el título, URL y vista previa
- Solo tienes que hacer clic en "Compartir" o "Publicar"

---

## ✨ Características de los Botones

**Automáticos:**
- Los botones se generan automáticamente
- Se adaptan a dispositivos móviles y desktop
- Detectan tu idioma automáticamente

**Personalizables:**
- Puedes cambiar el mensaje que aparece al compartir
- Puedes elegir qué redes mostrar
- Puedes cambiar el diseño de los botones

---

## 🔧 Implementación Técnica

### Código en `noticia.php`

```html
<div class="sharethis-inline-share-buttons"></div>

<script src="https://platform-api.sharethis.com/js/sharethis.js#property=67a6f8c000000000000000000&product=inline-share-buttons" async></script>
```

**Eso es todo.** ShareThis se encarga del resto automáticamente.

---

## 📊 Analytics (Estadísticas Gratuito)

Con ShareThis puedes ver:

1. **Cuántos usuarios compartieron** tus noticias
2. **En qué redes** se compartió más
3. **Tendencias** de compartidas
4. **Datos demográficos**

Para ver estadísticas:
- Ve a: https://platform.sharethis.com/
- Accede con tu cuenta
- Ve a "Analytics"

---

## 🎨 Personalizar los Botones

Si quieres cambiar cómo se ven los botones:

### Editar en `noticia.php`

Busca esta línea:
```html
<div class="sharethis-inline-share-buttons"></div>
```

Puedes cambiar a:

**Botones Verticales:**
```html
<div class="sharethis-inline-share-buttons" data-layout="vertical"></div>
```

**Botones Grandes:**
```html
<div class="sharethis-inline-share-buttons" data-size="32"></div>
```

**Con Contador:**
```html
<div class="sharethis-inline-share-buttons" data-count="true"></div>
```

---

## 🚀 Ventajas sobre APIs Nativas

| Opción | Setup | Costo | Límites | Facilidad |
|--------|-------|-------|---------|-----------|
| **ShareThis** ⭐ | 5 min | Gratis | Ninguno | ⭐⭐⭐⭐⭐ |
| Facebook API | 1+ hora | Gratis* | Verificación | ⭐⭐⭐ |
| Twitter API | 30 min | Gratis | Rate limit | ⭐⭐⭐ |
| IFTTT | 30 min | Pago | 2 applets | ⭐⭐⭐⭐ |

*Meta requiere verificación de identidad y pago

---

## ❓ Preguntas Frecuentes

**P: ¿Necesito crear una cuenta en ShareThis?**
A: No necesariamente. Los botones funcionan sin cuenta. Pero si quieres ver estadísticas, crea una gratis en platform.sharethis.com.

**P: ¿Me cuesta dinero?**
A: No. ShareThis es completamente gratuito.

**P: ¿Hay límite de compartidas?**
A: No hay límite.

**P: ¿Puedo personalizar los mensajes?**
A: Sí. Cuando alguien da clic para compartir, aparece una vista previa de la noticia que puede editar.

**P: ¿Funciona en móviles?**
A: Sí, perfectamente. Se adapta automáticamente.

**P: ¿Qué pasa si algo falla?**
A: Los botones son adicionales. Si falla ShareThis, la página igual funciona normalmente.

---

## 📱 Vista en Móviles

En dispositivos móviles, los botones se muestran:
- En una fila o columna (según el espacio)
- Más grandes y fáciles de tocar
- Con nombres de las redes bajo los iconos

---

## 🔒 Privacidad y Seguridad

**ShareThis respeta la privacidad:**
- No almacena datos personales
- No hace tracking sin consentimiento
- Compatible con GDPR (Reglamento Europeo)
- Las URLs compartidas son públicas (es lo esperado)

---

## 📚 Más Información

**Documentación Oficial:**
- https://www.sharethis.com/

**Dashboard de Analytics:**
- https://platform.sharethis.com/

**Soporte:**
- support@sharethis.com

---

## 🎉 ¡Listo!

Tu sistema de compartir en redes sociales está completamente funcional y listo para usar.

**Próximas publicaciones de tus noticias serán compartibles automáticamente en:**
- Facebook
- Twitter/X
- Instagram
- LinkedIn
- Pinterest
- WhatsApp
- ¡Y más de 200 redes sociales!

---

**Versión:** 1.0  
**Fecha:** 10 de marzo de 2026  
**Tipo de Integración:** ShareThis (Gratuita, Sin Límites)
