<?php

namespace Nanbando\Console\Command;

use Nanbando\Backup\BackupArchiveFactory;
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

    /**
     * @var BackupArchiveFactory
     */
    private $factory;

    public function __construct(BackupRunner $backupRunner, BackupArchiveFactory $factory)
    {
        parent::__construct();

        $this->backupRunner = $backupRunner;
        $this->factory = $factory;
    }

    protected function configure()
    {
        $this->addArgument('label', InputArgument::OPTIONAL);
        $this->addOption('message', 'm', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $backupArchive = $this->factory->create();
        $backupArchive->set('label', $input->getArgument('label'));
        $backupArchive->set('message', $input->getOption('message'));

        $this->backupRunner->run($backupArchive);
    }
}
