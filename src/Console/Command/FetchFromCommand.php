<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\StorageRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchFromCommand extends Command
{
    /**
     * @var StorageRegistry
     */
    private $registry;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(StorageRegistry $registry, OutputFormatter $output)
    {
        parent::__construct();

        $this->registry = $registry;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->addArgument('storage', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output->headline('Fetch from %s started', $input->getArgument('storage'));

        /** @var string $storageName */
        $storageName = $input->getArgument('storage');
        $storage = $this->registry->get($storageName);
        $storage->fetch($this->output);

        $this->output->info('Fetch finished');
    }
}
