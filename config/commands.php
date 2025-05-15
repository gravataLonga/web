<?php

use App\Command\InspireCommand;
use App\Command\TinkerCommand;

return [

    'commands' => [
        'inspire' => InspireCommand::class,
        'tinker' => TinkerCommand::class,
    ],

    TinkerCommand::class => DI\factory(function (array $tinker) {
        return new TinkerCommand($tinker);
    })->parameter('tinker', DI\get('tinker.alias')),

];