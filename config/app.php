<?php declare(strict_types=1);

return [

    'app.name' => \DI\env('APP_NAME', 'Lumice'),

    'app.env' => \DI\env('APP_ENV', 'production'),

    'app.debug' => \DI\env('APP_DEBUG', false),

    'app.url' => \DI\env('APP_URL', 'http://localhost'),

    'path.public' => PUBLIC_PATH,

    'path.root' => ROOT_PATH,

];