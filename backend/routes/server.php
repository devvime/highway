<?php

use Highway\Modules\Test\TestController;
use Highway\Middlewares\TestMiddleware;

router->get('/', function ($req, $res) {
    view->assign('title','Highway!');
    view->draw('pages/home');
});

router->group('/user', function () {
    router->get('/', TestController::class, 'index');
    router->get('/:id[int]', TestController::class, 'show');
    router->post('/', TestController::class, 'store');
    router->put('/:id[number]', TestController::class, 'update');
    router->delete('/:id[number]', TestController::class, 'destroy');
}, [new TestMiddleware()]);
