<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/{id}',
        \item_controler::class . ':get_item')->setName('item');

$app->group('/v1',
        function () {
    $this->group('/auth',
            function () {
        $this->map(['GET', 'POST'], '/login',
                'App\controllers\user_management:login');
        $this->map(['GET', 'POST'], '/logout',
                'App\controllers\user_management:logout');
        $this->map(['GET', 'POST'], '/signup',
                'App\controllers\user_management:signup');
    });
    $this->get('/', function ($request, $response, $args) {
        return $this->renderer->render($response, "index.phtml",
                            ["ip" => \userdata::getRealIpAddr()]);
    });
});
