<?php
class Router
{
    private $routes = [];

    function get($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'GET',
            'action' => $action
        ];
    }
    function post($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'POST',
            'action' => $action
        ];
    }
    function put($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'POST',
            'action' => $action
        ];
    }
    function delete($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'POST',
            'action' => $action
        ];
    }

    public function dispatch()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'])["path"];
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        foreach ($this->routes as $routesLink => $routes) {
            if ($uri == $routesLink && $request_method == $routes['method']) {
                $action = $routes['action'];
                $controler = $action[0];
                $method =  $action[1];

                $objectController = new $controler;
                $objectController->$method($id);
                exit();
            }
        }
        http_response_code(404);
        require_once  "Views/errors/404.php";
    }

    // login routes------------------------------------------------------------

    public function add($route, $action) {
        // Store routes as a closure (function) or controller/action pair
        $this->routes[$route] = $action;
    }

    public function match($url) {
        // If the route matches, return the action (closure or controller)
        foreach ($this->routes as $route => $action) {
            if ($url == $route) {
                return $action;
            }
        }
        return null;
    }
}


