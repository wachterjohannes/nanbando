<?php

namespace Nanbando\Console;

use Nanbando\Client\CommandBuilder;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $this->getApplication()->getHost();
        if ($host->isLocalhost()) {
            return $this->doExecute($input, $output);
        }

        $options = $input->getOptions();
        $options['host'] = 'localhost';
        $options['config'] = str_replace(getcwd(), '%cwd%', $options['config']);

        $command = new CommandBuilder('bin/nanbando', $input->getArguments(), $options);

        return $client = $this->getApplication()->getClient()->run($command);
    }

    abstract protected function doExecute(InputInterface $input, OutputInterface $output);

    public function getApplication(): Application
    {
        return parent::getApplication();
    }
}
