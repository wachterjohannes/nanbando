<?php

namespace Nanbando\Console\Command;

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

    public function __construct(RestoreRunner $restoreRunner)
    {
        parent::__construct();

        $this->restoreRunner = $restoreRunner;
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->restoreRunner->run($input->getArgument('file'));
    }
}
