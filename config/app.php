<?php declare(strict_types=1);

use Gravatalonga\Developer;

return [

    'app.name' => \DI\env('APP_NAME', 'Lumice'),

    'app.env' => \DI\env('APP_ENV', 'production'),

    'app.is_production' => DI\factory(function (string $env) {
        return $env === 'production';
    })->parameter('env', 'app.env'),

    'app.debug' => \DI\env('APP_DEBUG', false),

    'app.url' => \DI\env('APP_URL', 'http://localhost'),

    'path.public' => PUBLIC_PATH,

    'path.root' => ROOT_PATH,

    'tinker.alias' => [
        'Developer' => Developer::class,
    ]

];