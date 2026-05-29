<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');

define('BASE_URL', '/medicines/public');

require_once __DIR__ . '/core/dbconn.php';
require_once __DIR__ . '/core/dbfunc.php';
require_once __DIR__ . '/core/DataBase.php';

spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});