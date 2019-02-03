<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
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

    private $output;

    public function __construct(RestoreRunner $restoreRunner, RestoreReader $restoreReader, OutputFormatter $output)
    {
        parent::__construct();

        $this->restoreRunner = $restoreRunner;
        $this->restoreReader = $restoreReader;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        $this->output->headline('Restoring %s', $file);

        $restoreArchive = $this->restoreReader->open($file);

        $this->restoreRunner->run($restoreArchive);
    }
}
