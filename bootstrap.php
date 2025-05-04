<?php declare(strict_types=1);

use Dotenv\Dotenv;

define('ROOT_PATH', __DIR__);

define('PUBLIC_PATH', __DIR__ . '/public');

require ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

/** @var \Slim\App $app */
return require ROOT_PATH . '/bootstrap/app.php';