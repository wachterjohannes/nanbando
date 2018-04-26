<?php

namespace Nanbando\Console\Command;

use Nanbando\Restore\RestoreReader;
use Nanbando\Restore\RestoreRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreCommand extends Command
{
    /**
     * @var RestoreRunner
     */
    private $restoreRunner;

    /**
     * @var RestoreReader
     */
    private $restoreReader;

    public function __construct(RestoreRunner $restoreRunner, RestoreReader $restoreReader)
    {
        parent::__construct();

        $this->restoreRunner = $restoreRunner;
        $this->restoreReader = $restoreReader;
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $restoreArchive = $this->restoreReader->open($input->getArgument('file'));

        $this->restoreRunner->run($restoreArchive);
    }
}
