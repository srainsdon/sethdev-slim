<?php
require_once 'db_settings.php';
$data = [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];

$stmt = $pdo->query('SELECT * FROM setting');
foreach ($stmt as $result)
{
    $data['settings'][$result['SettingName']] = $result['SettingValue'];
}
return $data;