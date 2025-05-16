<?php

namespace Highway\Core\Interfaces;

interface ResponseInterface
{
    public function json(array $data);

    public function code(int $code);
}
