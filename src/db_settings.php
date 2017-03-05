<?php

$db_creds = unserialize(getenv('sethdev_DB'));

$dsn = "mysql:host=" . $db_creds['host'] . ";dbname=" . $db_creds['database'] . ";charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $db_creds['username'], $db_creds['password'], $opt);
