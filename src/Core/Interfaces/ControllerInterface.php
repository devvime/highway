<?php

namespace Highway\Core\Interfaces;

use Highway\Core\Interfaces\RequestInterface;
use Highway\Core\Interfaces\ResponseInterface;

interface ControllerInterface
{
    public function index(
        RequestInterface $request,
        ResponseInterface $response
    );

    public function show(
        RequestInterface $request,
        ResponseInterface $response
    );

    public function store(
        RequestInterface $request,
        ResponseInterface $response
    );

    public function update(
        RequestInterface $request,
        ResponseInterface $response
    );

    public function destroy(
        RequestInterface $request,
        ResponseInterface $response
    );
}
