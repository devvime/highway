<?php

namespace Highway\Middlewares;

use Highway\Core\Interfaces\MiddlewareInterface;
use Highway\Core\Request;
use Highway\Core\Response;

class TestMiddleware implements MiddlewareInterface
{
    public function handle(
        Request $request,
        Response $response
    ): void {
        $response->json(['message'=>'Middleware ok']);
    }
}
