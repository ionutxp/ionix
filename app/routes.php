<?php

get('test/{id?}', 'App\Controllers\HomeController@c')->where('id', '[0-9]+');
get('shop/{name}/{other}/{id}', 'App\Controllers\HomeController@a');
get('shop/{name?}', 'App\Controllers\HomeController@b');
get('/', 'App\Controllers\HomeController@index');

$router = app('router');

$router->group('test', function ($router) {
    $router->group('bla', function ($router) {
        $router->get('/bla', function () {
            echo 'Bla !';
        });
        $router->get('/tra', function () {
            echo 'Bla !';
        });
    });
});
