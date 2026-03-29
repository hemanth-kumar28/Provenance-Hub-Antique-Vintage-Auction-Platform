<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        $parsedUrl = parse_url($uri);
        $path = $parsedUrl['path'];

        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseDir = dirname($scriptName);

        // Normalize path by stripping the script name or base directory (for XAMPP)
        if (str_starts_with($path, $scriptName)) {
            $path = substr($path, strlen($scriptName));
        } elseif (str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            if (is_array($handler)) {
                $controller = new $handler[0]();
                $action = $handler[1];
                $controller->$action();
            } else {
                call_user_func($handler);
            }
        } else {
            http_response_code(404);
            require_once __DIR__ . '/../../views/partials/header.php';
            echo '<main class="max-w-7xl mx-auto px-6 py-24 text-center"><h1 class="text-4xl font-display mb-4">404 - Page Not Found</h1><p class="font-body text-lg text-outline">The requested artifact could not be located in our archives.</p></main>';
            require_once __DIR__ . '/../../views/partials/footer.php';
        }
    }
}
