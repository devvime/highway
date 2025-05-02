<?php

namespace Devvime\Route\controllers;

class TestController
{
    public function index() {
        echo "OK";
    }

    public function show($request) {
        var_dump($request);
    }

    public function profile($request) {
        var_dump($request);
    }

    public function users() {
        echo "list users OK";
    }

    public function users2() {
        echo "list users 2 OK";
    }
}