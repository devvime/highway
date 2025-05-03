<?php

use highway\core\router;
use highway\core\MiddlewareInterface;

beforeEach(function () {
    $_SERVER['REQUEST_URI'] = '';
    $_SERVER['REQUEST_METHOD'] = '';
    $GLOBALS['called'] = false;
    $GLOBALS['middlewareRan'] = false;
    $GLOBALS['receivedParam'] = null;
    $GLOBALS['calledInsideGroup'] = false;
});

test('handles a simple GET route', function () {
    $router = new Router();

    $router->get('/test', new class {
        public function handle($req) {
            $GLOBALS['called'] = true;
        }
    }, 'handle');

    $_SERVER['REQUEST_URI'] = '/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    $router->init();
    ob_end_clean();

    expect($GLOBALS['called'])->toBeTrue();
});

test('executes middleware', function () {
    $router = new Router();

    $middleware = new class implements MiddlewareInterface {
        public function handle($request) {
            $GLOBALS['middlewareRan'] = true;
        }
    };

    $router->get('/with-middleware', new class {
        public function handle($req) {}
    }, 'handle', [$middleware]);

    $_SERVER['REQUEST_URI'] = '/with-middleware';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    $router->init();
    ob_end_clean();

    expect($GLOBALS['middlewareRan'])->toBeTrue();
});

test('returns route not found', function () {
    $router = new Router();

    $_SERVER['REQUEST_URI'] = '/nonexistent';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    $router->init();
    $output = ob_get_clean();

    expect($output)->toContain('Route not found');
});

test('handles optional param', function () {
    $router = new Router();

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

    expect($GLOBALS['receivedParam'])->toBeNull();
});

test('handles grouped routes', function () {
    $router = new Router();

    $router->group('/api', function() use ($router) {
        $router->get('/test', new class {
            public function handle($req) {
                $GLOBALS['calledInsideGroup'] = true;
            }
        }, 'handle');
    });

    $_SERVER['REQUEST_URI'] = '/api/test';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    $router->init();
    ob_end_clean();

    expect($GLOBALS['calledInsideGroup'])->toBeTrue();
});
