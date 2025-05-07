<?php

require_once('backend/config/config.php');

switch (DBDRIVER) {
  case 'mysql':
    return
      [
        'paths' => [
          'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
          'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
        ],
        'environments' => [
          'default_migration_table' => 'phinxlog',
          'default_environment' => 'development',
          'production' => [
            'adapter' => 'mysql',
            'host' => DBHOST,
            'name' => DBNAME,
            'user' => DBUSER,
            'pass' => DBPASS,
            'port' => '3306',
            'charset' => 'utf8',
          ],
          'development' => [
            'adapter' => 'mysql',
            'host' => DBHOST,
            'name' => DBNAME,
            'user' => DBUSER,
            'pass' => DBPASS,
            'port' => '3306',
            'charset' => 'utf8',
          ],
          'testing' => [
            'adapter' => 'mysql',
            'host' => DBHOST,
            'name' => DBNAME,
            'user' => DBUSER,
            'pass' => DBPASS,
            'port' => '3306',
            'charset' => 'utf8',
          ]
        ],
        'version_order' => 'creation'
      ];
    break;
  case 'sqlite':
    $db_dir = __DIR__ . '/database/sqlite';
    if (!is_dir($db_dir)) {
      mkdir($db_dir, 0755, true);
    }
    return
      [
        'paths' => [
          'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
          'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
        ],
        'environments' => [
          'default_migration_table' => 'phinxlog',
          'default_environment' => 'development',
          'production' => [
            'adapter' => 'sqlite',
            'name' => '%%PHINX_CONFIG_DIR%%/database/sqlite/database',
          ],
          'development' => [
            'adapter' => 'sqlite',
            'name' => '%%PHINX_CONFIG_DIR%%/database/sqlite/database',
          ],
          'testing' => [
            'adapter' => 'sqlite',
            'name' => '%%PHINX_CONFIG_DIR%%/database/sqlite/database',
          ]
        ],
        'version_order' => 'creation'
      ];
    break;
}
