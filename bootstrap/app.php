<?php declare(strict_types=1);

use DI\Bridge\Slim\Bridge;

$container = require ROOT_PATH . '/bootstrap/container.php';
$router = require ROOT_PATH . '/app/router.php';
$middleware = require ROOT_PATH . '/app/middleware.php';

$app = Bridge::create($container);

$middleware($app);
$router($app);

return $app;