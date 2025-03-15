<?php
class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestMethod][$requestUri])) {
            $action = $this->routes[$requestMethod][$requestUri];
            $controller = new $action[0]();
            call_user_func([$controller, $action[1]]);
        }else {
            echo "<div style='text-align: center;'>";
            // echo "<h3 style='color:red;'>404 HZ PU " . htmlspecialchars($requestUri) . "</h3>";
            echo "<img src='../assets/images/404.avif' alt='Error Image' style='max-width: 100%; height: auto; margin-top: 20px;'>";
            echo "</div>";
        }
        
        
    }
}
?>
