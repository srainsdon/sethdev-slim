<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/{id}',
        \item_controler::class . ':get_item')->setName('item');

$app->get('/login', function ($request, $response, $args) {
    $this->container->renderer->render('login.html');
})->setname('login-form');
