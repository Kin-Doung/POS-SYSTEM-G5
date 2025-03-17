<?php
class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function put($uri, $action) {
        $this->routes['PUT'][$uri] = $action;
    }

    public function delete($uri, $action) {
        $this->routes['DELETE'][$uri] = $action;
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Handle PUT and DELETE via POST `_method` override
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $methodOverride = strtoupper($_POST['_method']);
            if (in_array($methodOverride, ['PUT', 'DELETE'])) {
                $requestMethod = $methodOverride;
            }
        }

        if (isset($this->routes[$requestMethod][$requestUri])) {
            $action = $this->routes[$requestMethod][$requestUri];
            $controller = new $action[0]();  // Instantiate the controller
            call_user_func([$controller, $action[1]]);  // Call the action method on the controller
        } else {
            echo "404 Not Found";  // Add a fallback if route not found
        }
    }
}
?>
