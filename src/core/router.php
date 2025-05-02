<?php

namespace Devvime\Route\core;

class Router {

    private static $routes = [];
    private static $path;
    private static $method;

    public static function get($path, $controller, $function, $middleware = null) {
        self::set_route('GET', $path, $controller, $function, $middleware);
    }

    public static function post($path, $controller, $function, $middleware = null) {
        self::set_route('POST', $path, $controller, $function, $middleware);
    }

    public static function put($path, $controller, $function, $middleware = null) {
        self::set_route('PUT', $path, $controller, $function, $middleware);
    }

    public static function delete($path, $controller, $function, $middleware = null) {
        self::set_route('DELETE', $path, $controller, $function, $middleware);
    }

    public static function patch($path, $controller, $function, $middleware = null) {
        self::set_route('PATCH', $path, $controller, $function, $middleware);
    }

    public static function options($path, $controller, $function, $middleware = null) {
        self::set_route('OPTIONS', $path, $controller, $function, $middleware);
    }

    private static function set_route($method, $path, $controller, $function, $middleware) {
        self::$routes[$path] = [
            "method" => $method,
            "controller" => $controller,
            "function" => $function,
            "middleware" => $middleware
        ];
    }

    public static function init() {
        self::$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        self::$method = $_SERVER['REQUEST_METHOD'];

        $matchedRoute = null;
        $params = null;

        foreach (self::$routes as $routePattern => $route) {
            $matchedParams = self::matchPath($routePattern);
            if ($matchedParams !== null) {
                $matchedRoute = $route;
                $params = $matchedParams;
                break;
            }
        }

        if ($matchedRoute) {
            $request = self::get_request($params);
            self::execute_route($matchedRoute, $request);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Route not found"]);
        }
    }

    private static function execute_route($current_route, $request) {
        if (self::$method === $current_route['method']) {
            $middleware = $current_route['middleware'];
            if ($middleware !== null) {
                $middlewareClass = new $middleware[0];
                $middlewareFunction = $middleware[1];
                $middlewareClass->$middlewareFunction($request);
            }

            $controller = new $current_route['controller'];
            $function = $current_route['function'];
            $controller->$function($request);
        }
    }

    private static function get_request($params) {
        $request = new \stdClass;
        $request->body = json_decode(file_get_contents('php://input'));
        $request->query = $_GET;
        $request->params = $params;
        return $request;
    }

    private static function matchPath($pattern) {
        $patternParts = explode('/', trim($pattern, '/'));
        $pathParts = explode('/', trim(self::$path, '/'));
    
        // Fail if there are too few path parts for non-optional params
        $minParts = count(array_filter($patternParts, function($part) {
            return !(strpos($part, ':') === 0 && substr($part, -1) === '?');
        }));
        if (count($pathParts) < $minParts || count($pathParts) > count($patternParts)) {
            return null;
        }
    
        $params = [];
    
        foreach ($patternParts as $index => $part) {
            if (!isset($pathParts[$index])) {
                // If it's an optional param, skip; else fail
                if (strpos($part, ':') === 0 && substr($part, -1) === '?') {
                    $paramName = substr($part, 1, -1);
                    $params[$paramName] = null;
                    continue;
                } else {
                    return null;
                }
            }
    
            if (strpos($part, ':') === 0) {
                $isOptional = substr($part, -1) === '?';
                $paramName = $isOptional ? substr($part, 1, -1) : substr($part, 1);
                $params[$paramName] = $pathParts[$index];
            } elseif ($part !== $pathParts[$index]) {
                return null;
            }
        }
    
        return $params;
    }
    
}
