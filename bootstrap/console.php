<?php declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

$application = new Application($_ENV['APP_NAME'], '1.0.0');
$container = require ROOT_PATH . '/bootstrap/container.php';
$application->setCommandLoader(new ContainerCommandLoader($container, $container->get('commands')));

return $application;
