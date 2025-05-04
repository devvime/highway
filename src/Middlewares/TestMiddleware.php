<?php

namespace Highway\Middlewares;

use Highway\Core\Interfaces\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface
{
    public function handle($request)
    {
        echo "Middleware ok";
        echo "<br>";
    }
}
