<?php

namespace Devvime\Route\middlewares;

class TestMiddleware
{
    public static function index() {
        echo "Middleware OK";
    }

    public static function handle() {
        echo "Middleware handle OK";
    }
}