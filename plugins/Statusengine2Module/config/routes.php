<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\DashedRoute;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin(
    'Statusengine2Module',
    ['path' => '/statusengine2-module'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);
