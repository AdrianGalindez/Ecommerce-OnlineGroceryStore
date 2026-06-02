<?php

class Router {

    private array $routes = [];

    public function __construct(){
        $this->registerRoutes();
    }

    private function registerRoutes(){

        // ── AUTENTICACIÓN ──────────────────────────────
        $this->add('auth:login',        'AuthController', 'login');
        $this->add('auth:loginPost',    'AuthController', 'loginPost');
        $this->add('auth:register',     'AuthController', 'register');
        $this->add('auth:registerPost', 'AuthController', 'registerPost');
        $this->add('auth:logout',       'AuthController', 'logout', auth: true);

        // ── HOME ───────────────────────────────────────
        $this->add('home:index', 'HomeController', 'index');

        // ── PRODUCTOS ─────────────────────────────────
        $this->add('product:index',  'ProductController', 'index');
        $this->add('product:show',   'ProductController', 'show');

        $this->add('product:indexAdmin','ProductController','indexAdmin', admin: true);
        $this->add('product:create', 'ProductController', 'create', admin: true);
        $this->add('product:store',  'ProductController', 'store',  admin: true);
        $this->add('product:edit',   'ProductController', 'edit',   admin: true);
        $this->add('product:update', 'ProductController', 'update', admin: true);
        $this->add('product:delete', 'ProductController', 'delete', admin: true);

        // ── CATEGORÍAS ─────────────────────────────────
        $this->add('category:index',  'CategoryController', 'index', admin: true);
        $this->add('category:show',   'CategoryController', 'show');
        $this->add('category:create', 'CategoryController', 'create', admin: true);
        $this->add('category:store',  'CategoryController', 'store', admin: true);
        $this->add('category:edit',   'CategoryController', 'edit', admin: true);
        $this->add('category:update', 'CategoryController', 'update', admin: true);
        $this->add('category:delete', 'CategoryController', 'delete', admin: true);

        // ── MARCAS ─────────────────────────────────────
        $this->add('brand:index', 'BrandController', 'index', admin: true);
        $this->add('brand:show',  'BrandController', 'show');
        $this->add('brand:shop',  'BrandController', 'shop');

        // ── USUARIOS ───────────────────────────────────
        $this->add('user:index', 'UserController', 'index', admin: true);
        $this->add('user:profile', 'UserController', 'profile', auth: true);

        // ── CARRITO ────────────────────────────────────
        $this->add('cart:index', 'CartController', 'index', auth: true);

        // ── DEFAULT / HOME FALLBACK ────────────────────
        $this->add('default:home', 'HomeController', 'index');
    }

    private function add(
        string $key,
        string $class,
        string $method,
        bool $auth = false,
        bool $admin = false
    ){
        $this->routes[$key] = [
            'class'  => $class,
            'method' => $method,
            'auth'   => $auth,
            'admin'  => $admin,
        ];
    }

    public function dispatch(){

        // ── INPUT SAFE ────────────────────────────────
        $controller = isset($_GET['controller'])
            ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['controller'])
            : 'home';

        $action = isset($_GET['action'])
            ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['action'])
            : 'index';

        $key = $controller . ':' . $action;

        // ── FALLBACK SEGURO ──────────────────────────
        if(!isset($this->routes[$key])){

            // evita crash en Render (NO die crudo)
            http_response_code(404);
            error_log("Route not found: " . $key);
            echo "404 - Ruta no encontrada";
            return;
        }

        $route = $this->routes[$key];

        // ── PERMISOS (SAFE CALLS) ─────────────────────
        if($route['admin']){
            if(function_exists('checkAdmin')){
                checkAdmin();
            }
        }

        if($route['auth'] && !$route['admin']){
            if(function_exists('requireUser')){
                requireUser();
            }
        }

        $class  = $route['class'];
        $method = $route['method'];

        // ── CLASE SEGURA ──────────────────────────────
        if(!class_exists($class, true)){
            http_response_code(500);
            error_log("Controller missing: " . $class);
            echo "500 - Controlador no encontrado";
            return;
        }

        $instance = new $class();

        // ── MÉTODO SEGURO ─────────────────────────────
        if(!method_exists($instance, $method)){
            http_response_code(500);
            error_log("Method missing: {$class}::{$method}");
            echo "500 - Método no encontrado";
            return;
        }

        // ── EJECUCIÓN FINAL ───────────────────────────
        $instance->$method();
    }
}
