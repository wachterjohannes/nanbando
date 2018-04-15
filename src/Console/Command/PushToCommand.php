<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\StorageRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PushToCommand extends Command
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var StorageRegistry
     */
    private $registry;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(Finder $finder, StorageRegistry $registry, OutputFormatter $output)
    {
        parent::__construct();

        $this->finder = $finder;
        $this->registry = $registry;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->addArgument('storage', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output->headline('Push to %s started', $input->getArgument('storage'));

        $storage = $this->registry->get($input->getArgument('storage'));

        foreach ($this->finder as $file) {
            // TODO extract and upload json file

            if (!$storage->exists($file->getPathname())) {
                $storage->push($file->getPathname());
            }
        }

        $this->output->info('Push finished');
    }
}
