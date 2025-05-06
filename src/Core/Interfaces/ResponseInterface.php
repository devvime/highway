<?php

namespace Highway\Core\Interfaces;

interface ResponseInterface
{
    public function json(
        array $data
    );
    
    public function html(
        string $file,
        array $data = []
    );

    public function code(int $code);
}
