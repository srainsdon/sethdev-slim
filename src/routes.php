<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/{id}',
        \item_controler::class . ':get_item')->setName('item');
$app->get('/', function ($request, $response, $args) {
        return $this->renderer->render($response, "index.phtml",
                            ["test" => "test"]);
    });
//$app->group('/v1', });

    $app->group('/auth',
            function () {
        $this->map(['GET', 'POST'], '/login',
                '\user_management:login');
        $this->map(['GET', 'POST'], '/logout',
                '\user_management:logout');
        $this->map(['GET', 'POST'], '/signup',
                '\user_management:signup');
    });
    

