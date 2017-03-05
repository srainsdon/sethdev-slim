<?php

// Routes
$route_settings = $app->getContainer()->routeSettings;
$app->get('/settings',
        function ($request, $response, $args) {
    var_dump($this->routeSettings);
});

$app->get('/' . $route_settings['ItemPage'] . '/[{id}]',
        function ($request, $response, $args) {
    
     $item_id = (int)$args['id'];
     
     $this->logger->addInfo("Item: " . $item_id);
     $item_map = new item_mapper($this->db);
     $item = $item_map->get_item($item_id);
    // Render index view
    var_dump($item);
    //return $this->renderer->render($response, 'item.phtml', $item);
})->setName('item');
