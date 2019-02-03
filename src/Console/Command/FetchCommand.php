<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCommand extends Command
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(Storage $storage, OutputFormatter $output)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $name */
        $name = $input->getArgument('name');

        $this->output->headline('Fetch backup %s started', $name);

        $this->storage->fetch($name, $this->output);

        $this->output->info('Fetch finished');
    }
}
