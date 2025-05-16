<?php

namespace Highway\Core\Interfaces;

use Highway\Core\Request;
use Highway\Core\Response;

interface ControllerInterface
{
    public function index(
        Request $request,
        Response $response
    );

    public function show(
        Request $request,
        Response $response
    );

    public function store(
        Request $request,
        Response $response
    );

    public function update(
        Request $request,
        Response $response
    );

    public function destroy(
        Request $request,
        Response $response
    );
}
