<?php

namespace Nanbando\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Backup started');
    }
}
