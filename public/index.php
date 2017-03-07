<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($classname) {
    require (__DIR__ . '/../src/classes/' . $classname . '.php');
});

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
//$settings['settings']['urlSettings'] = require_once __DIR__ . '/../src/db_settings.php';
$app = new \Slim\App($settings);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db_creds = unserialize(getenv('sethdev_DB'));
    $pdo = new PDO("mysql:host=" . $db_creds['host'] . ";dbname=" . $db_creds['database'],
            $db_creds['username'], $db_creds['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
// route config
$container['routeSettings'] = function ($c) {
    $urlSettings = array();
    $stmt = $c['db']->query('SELECT * FROM setting');
    foreach ($stmt as $result) {
        $urlSettings[$result['SettingName']] = $result['SettingValue'];
    }
    return $urlSettings;
};



// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
