<?php

use Highway\Core\Router;
use Rain\Tpl;

Tpl::configure(array(
    "tpl_dir"   =>  __DIR__ . "/../vendor/rain/raintpl/templates/test/",
    "cache_dir" => __DIR__ . "/../vendor/rain/raintpl/cache/"
));

const view = new Tpl;
const router = new Router();