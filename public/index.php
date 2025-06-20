<?php
// Simple front controller for legacy food-ordering monolith
// Autoload (very lightweight)
spl_autoload_register(function ($class) {
    // If class belongs to Core namespace, map directly without repeating 'Core/' in path
    if (str_starts_with($class, 'Core\\')) {
        $relative = substr($class, 5); // strip 'Core\'
        $file = __DIR__ . '/../core/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    // Default: look in app/ for Controllers, Models, etc.
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Redis session handler disabled for now due to persistence issue
/*
if (extension_loaded('redis')) {
    $redisHost = \Core\Config::get('REDIS_HOST', '127.0.0.1');
    $redisPort = \Core\Config::get('REDIS_PORT', 6379);
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', "tcp://{$redisHost}:{$redisPort}");
}
*/

session_start();



// Basic routing based on REQUEST_URI (no .htaccess required when using PHP built-in server)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
    case '/menu':
        $controller = new Controllers\MenuController();
        $controller->showMenu();
        break;

    case '/cart':
        $controller = new Controllers\CartController();
        $controller->showCart();
        break;

    case '/cart/add':
        $controller = new Controllers\CartController();
        $controller->addItem();
        break;

    case '/checkout':
        $controller = new Controllers\CheckoutController();
        $controller->checkout();
        break;

    case '/kitchen':
        $controller = new Controllers\KitchenController();
        $controller->showQueue();
        break;

    case '/kitchen/ready':
        $controller = new Controllers\KitchenController();
        $controller->markReady();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        break;
}
