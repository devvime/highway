<?php

namespace Highway\Modules\Test;

use Highway\Core\Interfaces\ControllerInterface;
use Highway\Core\Interfaces\RequestInterface;
use Highway\Core\Interfaces\ResponseInterface;

class UserController implements ControllerInterface
{
    public function index(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json(['message'=>'GET ok']);
    }

    public function show(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json([
            'message'=>'GET SHOW ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params
        ]);
    }

    public function store(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json([
            'message'=>'POST ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params,
        ]);
    }

    public function update(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json([
            'message'=>'PUT ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->getParams(),
        ]);
    }

    public function destroy(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->json([
            'message'=>'DELETE ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->getParams(),
        ]);
    }
}
