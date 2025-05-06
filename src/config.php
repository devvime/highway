<?php

use Highway\Core\Router;
use Rain\Tpl;

Tpl::configure(array(
    "tpl_dir"   =>  __DIR__ . "/../frontend/"
));

const view = new Tpl;
const router = new Router();