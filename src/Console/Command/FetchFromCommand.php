<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\StorageRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

class FetchFromCommand extends Command
{
    /**
     * @var string
     */
    private $localDirectory;

    /**
     * @var StorageRegistry
     */
    private $registry;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(string $localDirectory, StorageRegistry $registry, OutputFormatter $output)
    {
        parent::__construct();

        $this->localDirectory = $localDirectory;
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

        $storage = $this->registry->get($input->getArgument('storage'));
        foreach ($storage->listFiles() as $file) {
            $storage->fetch($file, Path::join($this->localDirectory, $file));
        }

        $this->output->info('Fetch finished');
    }
}
