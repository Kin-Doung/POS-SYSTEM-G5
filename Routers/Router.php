<?php
class Router {
    private $routes = [];

    public function get($path, $callback, $protected = false) {
        $this->routes['GET'][$path] = ['callback' => $callback, 'protected' => $protected];
    }

    public function post($path, $callback, $protected = false) {
        $this->routes['POST'][$path] = ['callback' => $callback, 'protected' => $protected];
    }

    public function put($path, $callback, $protected = false) {
        $this->routes['PUT'][$path] = ['callback' => $callback, 'protected' => $protected];
    }

    public function delete($path, $callback, $protected = false) {
        $this->routes['DELETE'][$path] = ['callback' => $callback, 'protected' => $protected];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($this->routes[$method] ?? [] as $path => $route) {
            $pattern = preg_replace('/\{id\}|\(:num\)/', '([0-9]+)', $path);
            $pattern = str_replace('/', '\/', $pattern);
            if (preg_match("/^$pattern$/", $uri, $matches)) {
                array_shift($matches);
                if ($route['protected'] && !$this->isAuthenticated()) {
                    $_SESSION['error'] = 'Please log in to access this page.';
                    error_log("Unauthenticated access to $uri, redirecting to /");
                    header('Location: /');
                    exit();
                }
                $_SESSION['redirect_count'] = 0;
                $callback = $route['callback'];
                $controller = new $callback[0]();
                call_user_func_array([$controller, $callback[1]], $matches);
                return;
            }
        }
        error_log("404 Not Found for $uri");
        http_response_code(404);
        echo "404 Not Found";
    }

    private function isAuthenticated() {
        return isset($_SESSION['username']);
    }
}
?>