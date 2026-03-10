<?php
/**
 * CONFIGURACIÓN DE REDES SOCIALES
 * El Tiempo de Hidalgo
 * 
 * INSTRUCCIONES PARA OBTENER CREDENCIALES:
 * 
 * 1. FACEBOOK & INSTAGRAM (Meta Graph API):
 *    - Ir a: https://developers.facebook.com/
 *    - Crear App Business
 *    - Obtener: Access Token, Page ID, Instagram Business Account ID
 *    - Documentación: https://developers.facebook.com/docs/graph-api/
 * 
 * 2. TWITTER/X (API v2):
 *    - Ir a: https://developer.twitter.com/
 *    - Crear App
 *    - Habilitar "Read and Write" permissions
 *    - Obtener: Bearer Token
 *    - Documentación: https://developer.twitter.com/en/docs/twitter-api
 */

// ============================================
// REDES SOCIALES - CREDENCIALES Y CONFIGURACIÓN
// ============================================

// FACEBOOK & INSTAGRAM (Meta API)
define('META_ACCESS_TOKEN', ''); // Reemplazar con tu Access Token de Meta
define('META_PAGE_ID', '');      // ID de tu página de Facebook
define('META_INSTAGRAM_ID', ''); // ID de tu cuenta de Instagram (Instagram Business Account)

// TWITTER / X (API v2)
define('TWITTER_BEARER_TOKEN', ''); // Reemplazar con tu Bearer Token de Twitter/X

// ============================================
// CONFIGURACIÓN GENERAL
// ============================================
define('SOCIAL_MEDIA_ENABLED', false); // Cambiar a true cuando configures las credenciales
define('SOCIAL_MEDIA_DEBUG', true);     // Mostrar errores en desarrollo (cambiar a false en producción)

/**
 * CÓMO CONFIGURAR:
 * 
 * 1. Obtén tus credenciales de cada red social
 * 2. Reemplaza los valores vacíos arriba con tus credenciales
 * 3. Cambia SOCIAL_MEDIA_ENABLED a true
 * 4. Coloca este archivo en:
 *    /config/ o en la raíz del proyecto (protegido con .htaccess)
 * 
 * SEGURIDAD:
 * - Nunca commits esto con credenciales reales
 * - Usa variables de entorno en producción
 * - Protege este archivo con permisos 0600
 */
?>
