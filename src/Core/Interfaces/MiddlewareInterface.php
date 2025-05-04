<?php

namespace Highway\Core\Interfaces;

interface MiddlewareInterface
{
    public function handle($request);
}
