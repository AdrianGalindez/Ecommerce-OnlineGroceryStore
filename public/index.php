<?php

// ══════════════════════════════════════════════════
// 1. ENV (PROTEGIDO)
// ══════════════════════════════════════════════════
require_once __DIR__ . '/../Config/env.php';

if (file_exists(__DIR__ . '/../.env')) {
    loadEnv(__DIR__ . '/../.env');
}

// ══════════════════════════════════════════════════
// 2. CONFIG BASE
// ══════════════════════════════════════════════════
require_once __DIR__ . '/../Config/app.php';
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Config/session.php';

// ══════════════════════════════════════════════════
// 3. SESIÓN
// ══════════════════════════════════════════════════
session_start();

// ══════════════════════════════════════════════════
// 4. HELPERS
// ══════════════════════════════════════════════════
require_once __DIR__ . '/../Helpers/Auth.php';

// ══════════════════════════════════════════════════
// 5. ERRORES
// ══════════════════════════════════════════════════
if (defined('APP_ENV') && APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ══════════════════════════════════════════════════
// 6. AUTOLOADER
// ══════════════════════════════════════════════════
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../Controller/' . $class . '.php',
        __DIR__ . '/../Model/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ══════════════════════════════════════════════════
// 7. ROUTER
// ══════════════════════════════════════════════════
require_once __DIR__ . '/../Routes/Router.php';

$router = new Router();
$router->dispatch();
