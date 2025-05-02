<?php

use PHPUnit\Framework\TestCase;
use Devvime\Route\core\Router;

class RouterTest extends TestCase
{
    public function testSimpleGetRoute()
    {
        $router = new Router();
        $called = false;

        $router->get('/test', new class {
            public function handle($req) {
                $GLOBALS['called'] = true;
            }
        }, 'handle');

        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $GLOBALS['called'] = false;

        ob_start();
        $router->init();
        ob_end_clean();

        $this->assertTrue($GLOBALS['called']);
    }

    public function testMiddlewareExecution()
    {
        $router = new Router();
        $middlewareRan = false;

        $middleware = new class implements Devvime\Route\core\MiddlewareInterface {
            public function handle($request) {
                $GLOBALS['middlewareRan'] = true;
            }
        };

        $router->get('/with-middleware', new class {
            public function handle($req) {}
        }, 'handle', [$middleware]);

        $_SERVER['REQUEST_URI'] = '/with-middleware';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $GLOBALS['middlewareRan'] = false;

        ob_start();
        $router->init();
        ob_end_clean();

        $this->assertTrue($GLOBALS['middlewareRan']);
    }

    public function testRouteNotFound()
    {
        $router = new Router();
        $_SERVER['REQUEST_URI'] = '/nonexistent';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        $router->init();
        $output = ob_get_clean();

        $this->assertStringContainsString('Route not found', $output);
    }

    public function testOptionalParam()
    {
        $router = new Router();
        $receivedParam = null;

        $router->get('/optional/:id?', new class {
            public function handle($req) {
                $GLOBALS['receivedParam'] = $req->params['id'];
            }
        }, 'handle');

        $_SERVER['REQUEST_URI'] = '/optional';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        $router->init();
        ob_end_clean();

        $this->assertNull($GLOBALS['receivedParam']);
    }

    public function testGroupRoutes()
    {
        $router = new Router();
        $calledInsideGroup = false;

        $router->group('/api', function() use ($router) {
            $router->get('/test', new class {
                public function handle($req) {
                    $GLOBALS['calledInsideGroup'] = true;
                }
            }, 'handle');
        });

        $_SERVER['REQUEST_URI'] = '/api/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $GLOBALS['calledInsideGroup'] = false;

        ob_start();
        $router->init();
        ob_end_clean();

        $this->assertTrue($GLOBALS['calledInsideGroup']);
    }
}
