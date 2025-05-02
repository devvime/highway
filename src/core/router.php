<?php

namespace Devvime\Route\core;

class Router {

    private static $routes = [];
    private static $path;
    private static $method;

    public static function get($path, $controller, $function, $middleware = null) 
    {
        self::set_route('GET', $path, $controller, $function, $middleware);
    }

    public static function post($path, $controller, $function, $middleware = null) 
    {
        self::set_route('POST', $path, $controller, $function, $middleware);
    }

    public static function put($path, $controller, $function, $middleware = null) 
    {
        self::set_route('PUT', $path, $controller, $function, $middleware);
    }

    public static function delete($path, $controller, $function, $middleware = null) 
    {
        self::set_route('DELETE', $path, $controller, $function, $middleware);
    }

    public static function patch($path, $controller, $function, $middleware = null) 
    {
        self::set_route('PATCH', $path, $controller, $function, $middleware);
    }

    public static function options($path, $controller, $function, $middleware = null) 
    {
        self::set_route('OPTIONS', $path, $controller, $function, $middleware);
    }

    private static function set_route($method, $path, $controller, $function, $middleware)
    {
        self::$routes[self::formate_path($path)] = [
            "method"=>$method,
            "controller"=>$controller,
            "function"=>$function,
            "middleware"=>$middleware
        ];
    }

    public static function init()
    {
        self::$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        self::$method = $_SERVER['REQUEST_METHOD'];
        $current_route = self::$routes[self::$path];
        self::execute_route($current_route);
        
    }

    private static function execute_route($current_route)
    {
        // echo json_encode($current_route);
        if (self::$method === $current_route['method']) {

            $middleware = $current_route['middleware'];
            if ($middleware !== null) {
                $middlewareClass = new $middleware[0];
                $middlewareFucnction = $middleware[1];
                $middlewareClass->$middlewareFucnction();
            }

            $controller = new $current_route['controller'];
            $function = $current_route['function'];
            $controller->$function();
        }
    }

    private static function get_request() 
    {
        $request = new \stdClass;
        $request->body =  json_decode(file_get_contents('php://input'));
        $request->query = json_encode($_GET);
    }

    private static function formate_path($path)
    {
        $pattern = preg_replace('/\/:[^\/]+/', '/([^/]+)', $path);
        if (preg_match("#^$pattern$#", self::$path, $matches)) {
            array_shift($matches);
            echo($matches);
        }
        return $pattern;
    }

}