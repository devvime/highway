<?php

namespace Highway\Core\Interfaces;

interface RequestInterface 
{
    public function getBody();
    public function getQuery();
    public function getParams();
    public function getHeaders();
}