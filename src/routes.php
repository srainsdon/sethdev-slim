<?php

// Routes

$routeSettings = $container->routeSettings;

$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($routeSettings);
});

$app->get('/' . $routeSettings['ItemPage'] . '[{id}]',
        function ($request, $response, $args) {
    
    // Render index view
    return $this->renderer->render($response, 'item.phtml', $args);
});
