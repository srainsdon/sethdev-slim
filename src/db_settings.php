<?php

$db_creds = unserialize(getenv('sethdev_DB'));
$dsn = "mysql:host=" . $db_creds['host'] . ";dbname=" . $db_creds['database'] . ";charset=utf8";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $db_creds['username'], $db_creds['password'], $opt);

$urlSettings = array();
$stmt = $pdo->query('SELECT * FROM setting');
foreach ($stmt as $result)
{
    $urlSettings[$result['SettingName']] = $result['SettingValue'];
}
return $urlSettings;