<?php

namespace Highway\Core;

class Request
{
    public $params;
    public $body;
    public $query;
    public $headers;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->body = json_decode(file_get_contents('php://input'));
        $this->query = $_GET;
        $this->headers = $_SERVER;
    }
}
