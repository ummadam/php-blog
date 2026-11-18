<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Service\View;

$view = new View();

$view->render('home.tpl', [
    'title' => 'PHP Blog',
    'message' => 'PHP, Nginx and Smarty are working!'
]);