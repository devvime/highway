<?php

namespace Highway\Core;

use Highway\Core\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    public $params;
    public $body;
    public $query;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->body = json_decode(file_get_contents('php://input'));
        $this->query = $_GET;
    }

    public function getBody()
    {
        return $this->body;
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
