<?php

// Routes

$app->get('/settings', function ($request, $response, $args) {
var_dump($app->get('settings')['urlSettings']);
});

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    $test = "what";
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});