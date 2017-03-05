<?php

// Routes
var_dump($app->getContainer()->routeSettings);
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/item/[{id}]',
        function ($request, $response, $args) {
     $item['id'] = (int)$args['id'];
    // Render index view
    return $this->renderer->render($response, 'item.phtml', $item);
})->setName('item');
