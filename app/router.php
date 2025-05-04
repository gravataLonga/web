<?php

use App\Http\HomeController;
use Slim\App;
use Slim\Csrf\Guard;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->add('csrf');

    $app->post('/register', [HomeController::class, 'index'])->add('csrf');
};