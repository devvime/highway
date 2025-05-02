<?php

namespace Devvime\Route\middlewares;

class TestMiddleware
{
    public static function index() {
        echo "Middleware OK";
    }
}