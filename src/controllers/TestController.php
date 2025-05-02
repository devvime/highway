<?php

namespace Devvime\Route\controllers;

class TestController
{
    public static function index() {
        echo "OK";
    }

    public static function show($request) {
        var_dump($request);
    }
}