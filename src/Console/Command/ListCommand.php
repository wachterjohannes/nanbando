<?php

namespace Nanbando\Console\Command;

use Nanbando\Console\OutputFormatter;
use Nanbando\Storage\ArchiveInfo;
use Nanbando\Storage\LocalStorage;
use Nanbando\Storage\StorageRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    /**
     * @var StorageRegistry
     */
    private $registry;

    /**
     * @var OutputFormatter
     */
    private $output;

    public function __construct(LocalStorage $localStorage, StorageRegistry $registry, OutputFormatter $output)
    {
        parent::__construct();

        $this->localStorage = $localStorage;
        $this->registry = $registry;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->addArgument('storage', InputArgument::OPTIONAL, '', 'local');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $storageName */
        $storageName = $input->getArgument('storage');
        $this->output->headline('List backups from %s', $storageName);

        $files = $this->listFiles($storageName);
        foreach ($files as $file) {
            $this->output->subHeadline('%s:', $file->getName());

            $this->output->list($this->formatFile($file, $storageName));
        }
    }

    /**
     * @return ArchiveInfo[]
     */
    protected function listFiles(string $storageName): array
    {
        if ('local' === $storageName) {
            return $this->localStorage->listFiles();
        }

        $storage = $this->registry->get($storageName);

        return $storage->listFiles();
    }

    protected function formatFile(ArchiveInfo $file, string $storageName): array
    {
        if (!$file->isFetched()) {
            $storage = $this->registry->get($storageName);
            $storage->fetchDatabase($file);
        }

        $database = $file->openDatabase();

        return [
            'label' => $database->get('label'),
            'message' => $database->get('message'),
            'started' => $database->get('started'),
            'finished' => $database->get('finished'),
        ];
    }
}
