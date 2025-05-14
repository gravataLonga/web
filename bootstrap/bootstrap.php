<?php declare(strict_types=1);

use Dotenv\Dotenv;

define('ROOT_PATH', dirname(__DIR__ . '../'));

define('PUBLIC_PATH', dirname(ROOT_PATH . '/public'));

require ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

/** @var \Slim\App $app */
return require ROOT_PATH . '/bootstrap/app.php';