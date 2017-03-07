<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
 function ($request, $response, $args) {
var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/{id}',
 \item_controler::class . ':get_item')->setName('item');
$app->get('/',
 function ($request, $response, $args) {
return $this->renderer->render($response, "index.phtml", ["test" => "test"]);
});
//$app->group('/v1', });

$app->group('/auth', function () {
$this->map(['GET', 'POST'], '/login', \user_management::class . ':login');
$this->map(['GET', 'POST'], '/logout', \user_management::class . ':logout');
$this->map(['GET', 'POST'], '/signup', \user_management::class . ':signup');
});

$app->group('/v1', function () {
$this->map(['GET', 'POST'], '/appdump', nunet\helper::class . ':app_dump');
);


