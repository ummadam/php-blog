<?php

declare(strict_types=1);

use App\Service\View;
use FastRoute\Dispatcher;

use function FastRoute\simpleDispatcher;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$routes = require dirname(__DIR__) . '/config/routes.php';

$dispatcher = simpleDispatcher($routes);

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
) ?: '/';

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch(
    $httpMethod,
    $uri
);

try {
    switch ($routeInfo[0]) {
        case Dispatcher::NOT_FOUND:
            http_response_code(404);

            $view = new View();

            $view->render('404.tpl', [
                'title' => 'Страница не найдена',
            ]);

            break;

        case Dispatcher::METHOD_NOT_ALLOWED:
            http_response_code(405);

            header(
                'Allow: ' . implode(', ', $routeInfo[1])
            );

            echo 'Method Not Allowed';

            break;

        case Dispatcher::FOUND:
            [$controllerClass, $method] = $routeInfo[1];

            $parameters = $routeInfo[2];

            $controller = new $controllerClass();

            $controller->{$method}(
                ...array_values($parameters)
            );

            break;
    }

} catch (Throwable $exception) {
    error_log((string) $exception);

    http_response_code(500);

    echo 'Internal Server Error';
}