<?php

use Devvime\Route\core\router;
use Devvime\Route\controllers\TestController;
use Devvime\Route\middlewares\TestMiddleware;

Router::get('/', TestController::class, 'index', [TestMiddleware::class, 'index']); 
Router::get('/test/:id/:productId?', TestController::class, 'show', [TestMiddleware::class, 'index']);
Router::get('/user/:userId/:section?', TestController::class, 'profile');


Router::group('/api/v1', function() {
    Router::get('/users', TestController::class, 'users');
}, [TestMiddleware::class, 'handle']);

Router::group('/api/v2', function() {
    Router::get('/users', TestController::class, 'users2');
}, [TestMiddleware::class, 'handle']);