<?php

namespace Highway\Core;

use Highway\Core\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getBody()
    {
        return json_decode(file_get_contents('php://input'));
    }

    public function getQuery()
    {
        return $_GET;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getHeaders()
    {
        return $_SERVER;
    }
}
