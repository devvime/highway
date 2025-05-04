<?php

namespace Highway\Core\Interfaces;

use Highway\Core\Interfaces\RequestInterface;
use Highway\Core\Interfaces\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(
        RequestInterface $request,
        ResponseInterface $response
    );
}
