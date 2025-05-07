<?php

namespace Highway\Core;

use Highway\Core\Interfaces\MiddlewareInterface;
use Highway\Core\Request;
use Highway\Core\Response;

class Router
{
    private array $routes = [];
    private string $groupPrefix = '';
    private array $groupMiddleware = [];

    public function get(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('GET', $path, $controller, $function, $middleware);
    }

    public function post(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('POST', $path, $controller, $function, $middleware);
    }

    public function put(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('PUT', $path, $controller, $function, $middleware);
    }

    public function delete(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('DELETE', $path, $controller, $function, $middleware);
    }

    public function patch(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('PATCH', $path, $controller, $function, $middleware);
    }

    public function options(
        string $path,
        string | object $controller,
        string | null $function = '',
        array $middleware = []
    ) {
        $this->setRoute('OPTIONS', $path, $controller, $function, $middleware);
    }

    private function setRoute(
        string $method,
        string $path,
        string | object $controller,
        string $function,
        array $middleware
    ) {
        $fullPath = rtrim($this->groupPrefix . $path, '/');
        $finalMiddleware = array_merge($this->groupMiddleware, $middleware);

        $this->routes[$fullPath][$method] = [
            'controller' => $controller,
            'function' => $function,
            'middleware' => $finalMiddleware
        ];
    }

    public function group(
        string $prefix,
        object $callback,
        array $middleware = []
    ) {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->groupPrefix .= $prefix;
        $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    private function executeRoute(
        array $route,
        array $params
    ) {
        $request = new Request($params);
        $response = new Response();

        foreach ($route['middleware'] as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $middleware->handle($request, $response);
            } elseif (is_array($middleware)) {
                $instance = new $middleware[0]();
                $method = $middleware[1];
                $instance->$method($request, $response);
            }
        }

        if (is_string($route['controller'])) {
            $controller = new $route['controller']();
            $function = $route['function'];
            $controller->$function($request, $response);
        } else {
            $route['controller']($request, $response);
        }
    }

    // private function matchPath($pattern, $path)
    // {
    //     $patternParts = explode('/', trim($pattern, '/'));
    //     $pathParts = explode('/', trim($path, '/'));

    //     $params = [];

    //     if (count($pathParts) < count(array_filter($patternParts, fn($part) => !(strpos($part, ':') === 0 && substr($part, -1) === '?'))) || count($pathParts) > count($patternParts)) {
    //         return null;
    //     }

    //     foreach ($patternParts as $i => $part) {
    //         if (!isset($pathParts[$i])) {
    //             if (strpos($part, ':') === 0 && substr($part, -1) === '?') {
    //                 $params[substr($part, 1, -1)] = null;
    //                 continue;
    //             } else {
    //                 return null;
    //             }
    //         }

    //         if (strpos($part, ':') === 0) {
    //             $paramName = rtrim(substr($part, 1), '?');
    //             $params[$paramName] = $pathParts[$i];
    //         } elseif ($part !== $pathParts[$i]) {
    //             return null;
    //         }
    //     }

    //     return $params;
    // }

    private function matchPath(
        string $pattern,
        string $path
    ) {
        $patternParts = explode('/', trim($pattern, '/'));
        $pathParts = explode('/', trim($path, '/'));

        $params = [];

        if (count($pathParts) < count(array_filter($patternParts, fn($part) => !(strpos($part, ':') === 0 && substr($part, -1) === '?'))) || count($pathParts) > count($patternParts)) {
            return null;
        }

        foreach ($patternParts as $i => $part) {
            if (!isset($pathParts[$i])) {
                if (strpos($part, ':') === 0 && substr($part, -1) === '?') {
                    $paramName = substr($part, 1, -1);
                    $params[$paramName] = null;
                    continue;
                } else {
                    return null;
                }
            }

            if (strpos($part, ':') === 0) {
                $paramPattern = rtrim(substr($part, 1), '?');
                if (preg_match('/(\w+)\[(\w+)\]/', $paramPattern, $matches)) {
                    $paramName = $matches[1];
                    $paramType = $matches[2];
                } else {
                    $paramName = $paramPattern;
                    $paramType = 'string';
                }

                $value = $pathParts[$i];

                if (!$this->validateType($value, $paramType)) {
                    return null;
                }

                $params[$paramName] = $this->convertType($value, $paramType);
            } elseif ($part !== $pathParts[$i]) {
                return null;
            }
        }

        return $params;
    }

    private function validateType(
        string $value,
        string $type
    ) {
        switch ($type) {
            case 'number':
                return is_numeric($value);
            case 'boolean':
                return $value === 'true' || $value === 'false' || $value === '1' || $value === '0';
            case 'string':
            default:
                return is_string($value);
        }
    }

    private function convertType(
        string $value,
        string $type
    ) {
        switch ($type) {
            case 'number':
            case 'int':
                return $value + 0;
            case 'boolean':
            case 'bool':
                return $value === 'true' || $value === '1';
            case 'string':
            default:
                return $value;
        }
    }

    public function init(
        string | null $path = null,
        string | null $method = null
    ) {
        $path = $path ?? rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $method = $method ?? $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $routePattern => $methods) {
            $params = $this->matchPath($routePattern, $path);
            if ($params !== null && isset($methods[$method])) {
                $this->executeRoute($methods[$method], $params);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
