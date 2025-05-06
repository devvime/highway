<?php

namespace Highway\Middlewares;

use Highway\Core\Interfaces\MiddlewareInterface;
use Highway\Core\Interfaces\RequestInterface;
use Highway\Core\Interfaces\ResponseInterface;

class TestMiddleware implements MiddlewareInterface
{
    public function handle(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json(['message'=>'Middleware ok']);
    }
}
