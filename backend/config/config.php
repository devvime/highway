<?php

use Highway\Core\Router;
use Rain\Tpl;

Tpl::configure(array(
    "tpl_dir"   =>  __DIR__ . "/../../frontend/",
    "cache_dir" => __DIR__ . "/../../server/cache"
));

const view = new Tpl;
const router = new Router();