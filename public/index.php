<?php

$files = glob('cache/*.rtpl.php');
foreach ($files as $file) {
    unlink($file);
}

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../src/bootstrap.php');
