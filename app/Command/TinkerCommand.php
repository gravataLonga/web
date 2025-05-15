<?php declare(strict_types=1);

namespace App\Command;

use DI\FactoryInterface;
use Gravatalonga\ClassAliasAutoloader;
use Psy\Configuration;
use Psy\Shell;
use Psy\VersionUpdater\Checker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'tinker',
    description: 'Interact with your application'
)]
final class TinkerCommand extends Command
{
    public function __construct(private array $aliases = [])
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addOption('execute', 'e', InputOption::VALUE_OPTIONAL);
        $this->addArgument('include', InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Configuration::fromInput($input);
        $config->setUpdateCheck(Checker::NEVER);

        $config->getPresenter()->addCasters(
            $this->getCasters()
        );

        if ($input->hasOption('execute')) {
            $config->setRawOutput(true);
        }

        $shell = new Shell($config);
        $shell->addCommands($this->getCommands());
        $shell->setIncludes($input->getArgument('include'));

        $loader = ClassAliasAutoloader::register(
            $shell, $this->aliases
        );

        if ($code = $input->getOption('execute')) {
            try {
                $shell->setOutput($output);
                $shell->execute($code);
            } finally {
                $loader->unregister();
            }

            return 0;
        }

        try {
            return $shell->run();
        } finally {
            $loader->unregister();
        }
    }

    private function getCasters(): array
    {
        return [];
    }

    private function getCommands(): array
    {
        return [];
    }
}
