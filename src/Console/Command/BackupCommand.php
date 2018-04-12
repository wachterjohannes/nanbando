<?php

namespace Nanbando\Console\Command;

use Nanbando\Backup\BackupRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommand extends Command
{
    /**
     * @var BackupRunner
     */
    private $backupRunner;

    public function __construct(BackupRunner $container)
    {
        parent::__construct();

        $this->backupRunner = $container;
    }

    protected function configure()
    {
        $this->addArgument('tag', InputArgument::OPTIONAL);
        $this->addOption('message', 'm', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->backupRunner->run($input->getArgument('tag'), $input->getOption('message'));
    }
}
