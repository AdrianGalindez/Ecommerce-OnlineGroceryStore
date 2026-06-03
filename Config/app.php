<?php

/**
 * Configuración global de la aplicación — AnimaMarket
 */

// ══════════════════════════════════════════════════
// 1. AYUDA: ENV VARIABLES SEGURAS
// Render + Docker no siempre llenan $_ENV correctamente
// usamos getenv() como fuente principal
// ══════════════════════════════════════════════════
function env($key, $default = null) {
    $value = getenv($key);
    return ($value !== false && $value !== null) ? $value : $default;
}

// ══════════════════════════════════════════════════
// 2. URL BASE
// ══════════════════════════════════════════════════
define('BASE_URL', env('APP_URL', 'https://ecommerce-onlinegrocerystore-2.onrender.com'));

// ══════════════════════════════════════════════════
// 3. NOMBRE DE LA APP
// ══════════════════════════════════════════════════
define('APP_NAME', env('APP_NAME', 'AnimaMarket'));

// ══════════════════════════════════════════════════
// 4. ENTORNO (CRÍTICO PARA DEBUG)
// ══════════════════════════════════════════════════
define('APP_ENV', env('APP_ENV', 'development'));

// ══════════════════════════════════════════════════
// 5. RUTAS INTERNAS
// ══════════════════════════════════════════════════

// Raíz del proyecto
define('ROOT_PATH', dirname(__DIR__));

// Storage de imágenes
define('STORAGE_PATH', ROOT_PATH . '/public/img/products/');

// URL pública de imágenes
define('STORAGE_URL', BASE_URL . 'img/products/');

// ══════════════════════════════════════════════════
// 6. SESIÓN (SEGURO PARA PRODUCCIÓN)
// ══════════════════════════════════════════════════
$sessionName = env('SESSION_NAME', 'animamarket_session');
session_name($sessionName);

// ══════════════════════════════════════════════════
// 7. ZONA HORARIA
// ══════════════════════════════════════════════════
date_default_timezone_set('America/Bogota');
