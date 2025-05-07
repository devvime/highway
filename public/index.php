<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../backend/config/bootstrap.php');

$files = glob(__DIR__ . '/../server/cache/*.rtpl.php');
foreach ($files as $file) {
    unlink($file);
}
