<?php

namespace Highway\Core;

use Highway\Core\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    public function json(array $data)
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function code(int $code)
    {
        http_response_code($code);
    }
}
