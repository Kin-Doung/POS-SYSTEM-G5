<?php
require_once __DIR__ . '/../Controllers/TrackingController.php';

class Router
{
    private $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function put($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function delete($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Debugging output (remove after testing)
        // echo "Method: $request_method, URI: $uri<br>";
        // var_dump($this->routes);

        foreach ($this->routes[$request_method] ?? [] as $route => $action) {
            $route = trim($route, '/'); // Normalize route
            $pattern = preg_replace('#\{id\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match, keep captured groups
                $controller = $action[0];
                $method = $action[1];
                $objectController = new $controller;
                call_user_func_array([$objectController, $method], $matches);
                return;
            }
        }

        http_response_code(404);
        require_once "views/errors/404.php";
    }
}