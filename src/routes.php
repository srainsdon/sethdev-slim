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
    return $this->renderer->render($response, "login.phtml", ["ip" => \userdata::getRealIpAddr()]);
})->setname('login-form');
$app->post('/login', function ($request, $response, $args) {
    $username = $request->post('UserName');
    $password = $request->post('Password');
    var_dump(array($username, $password));
})->setname('login');
