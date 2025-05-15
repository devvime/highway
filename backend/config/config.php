<?php

use Highway\Core\Router;
#
# Root Path
define('__ROOT__', dirname(dirname(__DIR__)));
#
# .env Settings
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
#
# CSS & JS Version
define('VERSION', round(microtime(true) * 1000));
#
# Database Settings
define('DBDRIVER', $_ENV['DBDRIVER']);
define('DBHOST', $_ENV['DBHOST']);
define('DBUSER', $_ENV['DBUSER']);
define('DBPASS', $_ENV['DBPASS']);
define('DBNAME', $_ENV['DBNAME']);
#
# Email Settings
define('EMAIL_HOST', $_ENV['EMAIL_HOST']);
define('EMAIL_USER', $_ENV['EMAIL_USER']);
define('EMAIL_PASSWORD', $_ENV['EMAIL_PASSWORD']);
define('EMAIL_PORT', $_ENV['EMAIL_PORT']);
#
# JWT Settings
define('SECRET', $_ENV['SECRET']);
#
# Timezone Settings
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');
#
# Router Settings
const router = new Router();
#
# Upload Settings
const UPLOAD_DIR = __ROOT__ . '/uploads';