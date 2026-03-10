<?php
/**
 * FUNCIONES DE INTEGRACIÓN CON REDES SOCIALES
 * El Tiempo de Hidalgo
 * 
 * Publica automáticamente en:
 * - Facebook
 * - Instagram
 * - Twitter/X
 */

require_once 'social_media_config.php';

class SocialMediaAPI {
    
    /**
     * PUBLICAR EN FACEBOOK
     */
    public static function publicarEnFacebook($titulo, $descripcion, $imagen_url, $enlace) {
        if (!SOCIAL_MEDIA_ENABLED || empty(META_ACCESS_TOKEN) || empty(META_PAGE_ID)) {
            return ['success' => false, 'error' => 'Facebook no configurado'];
        }

        $url = "https://graph.facebook.com/v18.0/" . META_PAGE_ID . "/feed";
        
        $datos = [
            'message' => "$titulo\n\n$descripcion",
            'link' => $enlace,
            'access_token' => META_ACCESS_TOKEN
        ];

        if (!empty($imagen_url)) {
            $datos['picture'] = $imagen_url;
        }

        return self::enviarRequestHTTP($url, $datos);
    }

    /**
     * PUBLICAR EN INSTAGRAM
     */
    public static function publicarEnInstagram($titulo, $descripcion, $imagen_url) {
        if (!SOCIAL_MEDIA_ENABLED || empty(META_ACCESS_TOKEN) || empty(META_INSTAGRAM_ID)) {
            return ['success' => false, 'error' => 'Instagram no configurado'];
        }

        // Instagram requiere subir imagen primero, luego crear post
        $url_upload = "https://graph.instagram.com/v18.0/" . META_INSTAGRAM_ID . "/media";
        
        $datos_upload = [
            'image_url' => $imagen_url,
            'caption' => "$titulo\n\n$descripcion",
            'access_token' => META_ACCESS_TOKEN
        ];

        $respuesta_upload = self::enviarRequestHTTP($url_upload, $datos_upload, 'POST');

        if (!isset($respuesta_upload['success']) || !$respuesta_upload['success']) {
            return ['success' => false, 'error' => 'Error al subir imagen a Instagram'];
        }

        // Publicar el media
        if (!isset($respuesta_upload['media_id'])) {
            return ['success' => false, 'error' => 'No se obtuvo ID de media'];
        }

        $url_publish = "https://graph.instagram.com/v18.0/" . META_INSTAGRAM_ID . "/media_publish";
        
        $datos_publish = [
            'creation_id' => $respuesta_upload['media_id'],
            'access_token' => META_ACCESS_TOKEN
        ];

        return self::enviarRequestHTTP($url_publish, $datos_publish, 'POST');
    }

    /**
     * PUBLICAR EN TWITTER/X
     */
    public static function publicarEnTwitter($titulo, $descripcion, $enlace) {
        if (!SOCIAL_MEDIA_ENABLED || empty(TWITTER_BEARER_TOKEN)) {
            return ['success' => false, 'error' => 'Twitter/X no configurado'];
        }

        $url = "https://api.twitter.com/2/tweets";
        
        // Limitar a 280 caracteres
        $texto = substr("$titulo\n\n$descripcion\n\n$enlace", 0, 280);
        
        $datos = [
            'text' => $texto
        ];

        return self::enviarRequestHTTP($url, $datos, 'POST', true);
    }

    /**
     * ENVIAR REQUEST HTTP A LAS APIs
     * @param string $url URL de destino
     * @param array $datos Datos a enviar
     * @param string $metodo GET o POST
     * @param bool $es_twitter Si es Twitter (usa Bearer Token)
     */
    private static function enviarRequestHTTP($url, $datos, $metodo = 'POST', $es_twitter = false) {
        try {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            if ($metodo === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                
                if ($es_twitter) {
                    // Twitter usa JSON
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . TWITTER_BEARER_TOKEN,
                        'Content-Type: application/json'
                    ]);
                } else {
                    // Facebook e Instagram usan form-urlencoded
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                }
            }

            $respuesta = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error_curl = curl_error($ch);
            curl_close($ch);

            if ($error_curl) {
                return ['success' => false, 'error' => 'Error CURL: ' . $error_curl];
            }

            $datos_respuesta = json_decode($respuesta, true);

            if ($http_code >= 200 && $http_code < 300) {
                return [
                    'success' => true,
                    'http_code' => $http_code,
                    'data' => $datos_respuesta,
                    'post_id' => $datos_respuesta['id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'http_code' => $http_code,
                    'error' => $datos_respuesta['error']['message'] ?? 'Error en la API'
                ];
            }

        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * GENERAR RESUMEN DE LA NOTICIA PARA REDES SOCIALES
     */
    public static function generarResumen($titulo, $contenido, $limite = 150) {
        // Eliminar etiquetas HTML
        $texto = strip_tags($contenido);
        
        // Limitar caracteres
        $resumen = substr($texto, 0, $limite);
        
        // Si sobrepasa, buscar último espacio para cortar palabra completa
        if (strlen($texto) > $limite) {
            $resumen = substr($resumen, 0, strrpos($resumen, ' ')) . '...';
        }
        
        return $resumen;
    }

    /**
     * REGISTRAR INTENTO DE PUBLICACIÓN EN BASE DE DATOS
     */
    public static function registrarPublicacion($noticia_id, $red_social, $post_id, $exitoso = true) {
        global $conn;
        
        $sql = "INSERT INTO social_media_publicaciones (noticia_id, red_social, post_id, exitoso, fecha_publicacion) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            if (SOCIAL_MEDIA_DEBUG) {
                error_log("Error preparando statement: " . $conn->error);
            }
            return false;
        }
        
        $stmt->bind_param('issi', $noticia_id, $red_social, $post_id, $exitoso);
        return $stmt->execute();
    }
}

/**
 * FUNCIÓN AUXILIAR PARA PUBLICAR EN TODAS LAS REDES
 */
function publicarEnTodasLasRedes($noticia_id, $titulo, $descripcion, $imagen_url, $enlace, $publicar_redes) {
    $resultados = [];

    // FACEBOOK
    if (isset($publicar_redes['facebook']) && $publicar_redes['facebook']) {
        $resultado = SocialMediaAPI::publicarEnFacebook($titulo, $descripcion, $imagen_url, $enlace);
        $resultados['facebook'] = $resultado;
        
        if ($resultado['success']) {
            SocialMediaAPI::registrarPublicacion($noticia_id, 'facebook', $resultado['post_id'] ?? null, true);
        } else if (SOCIAL_MEDIA_DEBUG) {
            error_log("Error Facebook: " . ($resultado['error'] ?? 'Desconocido'));
        }
    }

    // INSTAGRAM
    if (isset($publicar_redes['instagram']) && $publicar_redes['instagram']) {
        $resultado = SocialMediaAPI::publicarEnInstagram($titulo, $descripcion, $imagen_url);
        $resultados['instagram'] = $resultado;
        
        if ($resultado['success']) {
            SocialMediaAPI::registrarPublicacion($noticia_id, 'instagram', $resultado['post_id'] ?? null, true);
        } else if (SOCIAL_MEDIA_DEBUG) {
            error_log("Error Instagram: " . ($resultado['error'] ?? 'Desconocido'));
        }
    }

    // TWITTER/X
    if (isset($publicar_redes['twitter']) && $publicar_redes['twitter']) {
        $resultado = SocialMediaAPI::publicarEnTwitter($titulo, $descripcion, $enlace);
        $resultados['twitter'] = $resultado;
        
        if ($resultado['success']) {
            SocialMediaAPI::registrarPublicacion($noticia_id, 'twitter', $resultado['post_id'] ?? null, true);
        } else if (SOCIAL_MEDIA_DEBUG) {
            error_log("Error Twitter: " . ($resultado['error'] ?? 'Desconocido'));
        }
    }

    return $resultados;
}
?>
