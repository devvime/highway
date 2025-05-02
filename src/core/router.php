<?php

namespace highway\core;

interface MiddlewareInterface
{
  public function handle($request);
}

class Router
{
  private $routes = [];
  private $groupPrefix = '';
  private $groupMiddleware = [];

  public function get($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('GET', $path, $controller, $function, $middleware);
  }

  public function post($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('POST', $path, $controller, $function, $middleware);
  }

  public function put($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('PUT', $path, $controller, $function, $middleware);
  }

  public function delete($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('DELETE', $path, $controller, $function, $middleware);
  }

  public function patch($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('PATCH', $path, $controller, $function, $middleware);
  }

  public function options($path, $controller, $function, $middleware = [])
  {
    $this->setRoute('OPTIONS', $path, $controller, $function, $middleware);
  }

  public function group($prefix, $callback, $middleware = [])
  {
    $previousPrefix = $this->groupPrefix;
    $previousMiddleware = $this->groupMiddleware;

    $this->groupPrefix .= $prefix;
    $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);

    $callback($this);

    $this->groupPrefix = $previousPrefix;
    $this->groupMiddleware = $previousMiddleware;
  }

  private function setRoute($method, $path, $controller, $function, $middleware)
  {
    $fullPath = rtrim($this->groupPrefix . $path, '/');
    $finalMiddleware = array_merge($this->groupMiddleware, $middleware);

    $this->routes[$fullPath][$method] = [
      'controller' => $controller,
      'function' => $function,
      'middleware' => $finalMiddleware
    ];
  }

  public function init($path = null, $method = null)
  {
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

  private function executeRoute($route, $params)
  {
    $request = $this->getRequest($params);

    foreach ($route['middleware'] as $middleware) {
      if ($middleware instanceof MiddlewareInterface) {
        $middleware->handle($request);
      } elseif (is_array($middleware)) {
        $instance = new $middleware[0]();
        $method = $middleware[1];
        $instance->$method($request);
      }
    }

    $controller = new $route['controller']();
    $function = $route['function'];
    $controller->$function($request);
  }

  private function getRequest($params)
  {
    $request = new \stdClass;
    $request->body = json_decode(file_get_contents('php://input'));
    $request->query = $_GET;
    $request->params = $params;
    return $request;
  }

  private function matchPath($pattern, $path)
  {
    $patternParts = explode('/', trim($pattern, '/'));
    $pathParts = explode('/', trim($path, '/'));

    $params = [];

    if (count($pathParts) < count(array_filter($patternParts, fn($part) => !(strpos($part, ':') === 0 && substr($part, -1) === '?'))) || count($pathParts) > count($patternParts)) {
      return null;
    }

    foreach ($patternParts as $i => $part) {
      if (!isset($pathParts[$i])) {
        if (strpos($part, ':') === 0 && substr($part, -1) === '?') {
          $params[substr($part, 1, -1)] = null;
          continue;
        } else {
          return null;
        }
      }

      if (strpos($part, ':') === 0) {
        $paramName = rtrim(substr($part, 1), '?');
        $params[$paramName] = $pathParts[$i];
      } elseif ($part !== $pathParts[$i]) {
        return null;
      }
    }

    return $params;
  }
}
