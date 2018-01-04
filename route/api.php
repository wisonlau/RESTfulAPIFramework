<?php
$app->any('/', '\App\Controllers\TestController:test');
$app->any('/lock', '\App\Controllers\TestController:lock');
$app->post('/users', '\App\Controllers\RegisterController:action');


// $mw = function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
//     $response->getBody()->write('BEFORE');
//     $response = $next($request, $response);
//     $response->getBody()->write('AFTER');
//
//     return $response;
// };
$mw = new \App\Middleware\TestMiddleware();
$app->any('/api', function ($request, $response, $args) {
    $response->getBody()->write(' Hello ');
    return $response;
})->add($mw);

