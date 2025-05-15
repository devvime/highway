<?php

namespace Highway\Core\Interfaces;

use Highway\Core\Request;
use Highway\Core\Response;

interface MiddlewareInterface
{
    public function handle(
        Request $request,
        Response $response
    ): void;
}
