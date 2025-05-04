<?php declare(strict_types=1);

use Symfony\Component\Console\Application;

$application = new Application($_ENV['APP_NAME'], '1.0.0');
$container = require ROOT_PATH . '/bootstrap/container.php';

$commands = $container->get('commands');

foreach ($commands as $command) {
    $application->add($container->get($command));
}

return $application;
