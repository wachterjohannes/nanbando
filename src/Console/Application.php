<?php

namespace Nanbando\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputOption;

class Application extends SymfonyApplication
{
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(
            new InputOption(
                'config', 'c', InputOption::VALUE_REQUIRED, 'Path to configuration file', getcwd() . '/nanbando.php'
            )
        );

        return $definition;
    }
}
