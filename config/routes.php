<?php

declare(strict_types=1);

use App\Controller\HomeController;
use App\Controller\CategoryController;
use App\Controller\ArticleController;
use FastRoute\RouteCollector;

return static function (RouteCollector $router): void {

    $router->addRoute(
        'GET',
        '/',
        [HomeController::class, 'index']
    );

    $router->addRoute(
        'GET',
        '/category/{slug}',
        [CategoryController::class, 'show']
    );

    $router->addRoute(
        'GET',
        '/article/{slug}',
        [ArticleController::class, 'show']
    );
};