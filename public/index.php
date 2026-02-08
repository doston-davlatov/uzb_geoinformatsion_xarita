<?php
// public/index.php

// Error reporting (faqat development uchun)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Start session
require_once __DIR__ . '/../core/Auth.php';
Auth::startSession();

// Core files
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Security.php';

// Simple Router
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$request = strtok($request, '?');

// Route definitions
$routes = [
    // Auth routes
    '/login' => ['AuthController', 'login'],
    '/logout' => ['AuthController', 'logout'],
    '/register' => ['AuthController', 'register'],
    
    // Main routes
    '/' => ['HomeController', 'index'],
    '/dashboard' => ['HomeController', 'dashboard'],
    
    // Mahalla routes
    '/mahalla' => ['MahallaController', 'index'],
    '/mahalla/(\d+)' => ['MahallaController', 'show'],
    '/mahalla/create' => ['MahallaController', 'create'],
    '/mahalla/(\d+)/edit' => ['MahallaController', 'edit'],
    '/mahalla/(\d+)/delete' => ['MahallaController', 'delete'],
    
    // Crime routes
    '/crimes' => ['CrimeController', 'index'],
    '/crimes/create' => ['CrimeController', 'create'],
    '/crimes/(\d+)' => ['CrimeController', 'show'],
    '/crimes/(\d+)/edit' => ['CrimeController', 'edit'],
    
    // Admin routes
    '/admin/dashboard' => ['AdminController', 'dashboard'],
    '/admin/users' => ['AdminController', 'users'],
    '/admin/users/create' => ['AdminController', 'createUser'],
    '/admin/users/(\d+)/edit' => ['AdminController', 'editUser'],
    '/admin/operators' => ['AdminController', 'operators'],
    '/admin/assign-operators' => ['AdminController', 'assignOperators'],
    '/admin/statistics' => ['AdminController', 'statistics'],
    '/admin/settings' => ['AdminController', 'settings'],
    
    // API routes
    '/api/v1/mahalla/(\d+)/stats' => ['ApiController', 'getMahallaStats'],
    '/api/v1/crimes' => ['ApiController', 'getCrimes'],
    
    // Error routes
    '/404' => ['ErrorController', 'notFound'],
    '/403' => ['ErrorController', 'forbidden'],
    '/500' => ['ErrorController', 'serverError'],
];

// Find matching route
$controller = null;
$action = null;
$params = [];

foreach ($routes as $route => $handler) {
    $pattern = "#^" . preg_replace('/\\\:([a-zA-Z0-9_]+)/', '(?P<$1>[a-zA-Z0-9_\-\.]+)', preg_quote($route)) . "$#";
    
    if (preg_match($pattern, $request, $matches)) {
        $controller = $handler[0];
        $action = $handler[1];
        
        // Extract parameters
        foreach ($matches as $key => $value) {
            if (!is_numeric($key)) {
                $params[$key] = $value;
            }
        }
        break;
    }
}

// If no route matched, show 404
if (!$controller) {
    header("Location: /404");
    exit;
}

// Load controller
$controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    $controllerClass = $controller;
    $controllerInstance = new $controllerClass();
    
    // Check if method exists
    if (method_exists($controllerInstance, $action)) {
        // Call controller method with parameters
        call_user_func_array([$controllerInstance, $action], $params);
    } else {
        header("Location: /404");
        exit;
    }
} else {
    header("Location: /404");
    exit;
}

// Output buffering for clean output
ob_end_flush();
?>