<?php

namespace Highway\Modules\Test;

use Highway\Core\Interfaces\ControllerInterface;
use Highway\Core\Request;
use Highway\Core\Response;

class TestController implements ControllerInterface
{
    public function index(
        Request $request,
        Response $response
    ) {
        $response->json(['message'=>'GET ok']);
    }

    public function show(
        Request $request,
        Response $response
    ) {
        $response->json([
            'message'=>'GET SHOW ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params
        ]);
    }

    public function store(
        Request $request,
        Response $response
    ) {
        $response->json([
            'message'=>'POST ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params,
        ]);
    }

    public function update(
        Request $request,
        Response $response
    ) {
        $response->json([
            'message'=>'PUT ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params,
        ]);
    }

    public function destroy(
        Request $request,
        Response $response
    ) {
        $response->json([
            'message'=>'DELETE ok',
            'body'=>$request->body,
            'query'=>$request->query,
            'params'=>$request->params,
        ]);
    }
}
