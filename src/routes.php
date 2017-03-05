<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/[{id}]',
        function ($request, $response, $args) {
     $item['id'] = (int)$args['id'];
    // Render index view
    return $this->renderer->render($response, 'item.phtml', $item);
})->setName('item');
