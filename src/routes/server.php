<?php

use Devvime\Route\core\router;
use Devvime\Route\controllers\TestController;
use Devvime\Route\middlewares\TestMiddleware;

Router::get('/', TestController::class, 'index', [TestMiddleware::class, 'index']);
Router::get('/:id', TestController::class, 'show', [TestMiddleware::class, 'index']);
Router::get('/:id/:productId', TestController::class, 'show', [TestMiddleware::class, 'index']);