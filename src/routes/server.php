<?php

use Highway\Modules\Test\TestController;
use Highway\Middlewares\TestMiddleware;

router->group('/user', function () {
    router->get('/', TestController::class, 'index');
    router->get('/:id', TestController::class, 'show');
    router->post('/', TestController::class, 'store');
    router->put('/:id', TestController::class, 'update');
    router->delete('/:id', TestController::class, 'delete');
}, [new TestMiddleware()]);
