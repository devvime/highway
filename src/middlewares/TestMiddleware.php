<?php

namespace highway\middlewares;

use highway\core\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface
{
  public function handle($request) {
    echo "Middleware ok";
    echo "<br>";
  }
}
