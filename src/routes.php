<?php

// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
$app->get('/info/[{name}]', function ( $request, $response) {
    $name = $request->getAttribute('name');
    ob_start();
    phpinfo();
    $data = ob_get_contents();
    ob_clean();
    $response->getBody()->write($data);

    return $response;
});
