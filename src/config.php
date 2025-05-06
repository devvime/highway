<?php

use Highway\Core\Router;
use Rain\Tpl;

const router = new Router();

$view_conf = array(
    "tpl_dir"       =>  __DIR__ . "/../vendor/rain/raintpl/templates/test/",
    "cache_dir"     => __DIR__ . "/../vendor/rain/raintpl/cache/"
);
Tpl::configure($view_conf);
const view = new Tpl;